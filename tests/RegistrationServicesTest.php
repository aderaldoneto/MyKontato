<?php

declare(strict_types=1);

use App\Enums\Sexo;
use App\Models\Contact;
use App\Models\User;
use App\Repositories\ContactRepository;
use App\Repositories\UserRepository;
use App\Services\ContactRegistrationService;
use App\Services\UserRegistrationService;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

final class RegistrationServicesTest extends TestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [dirname(__DIR__) . '/app/Models'],
            isDevMode: true
        );
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ], $config);
        $this->entityManager = new EntityManager($connection, $config);

        (new SchemaTool($this->entityManager))->createSchema([
            $this->entityManager->getClassMetadata(User::class),
            $this->entityManager->getClassMetadata(Contact::class),
        ]);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
    }

    public function testCadastraUsuarioComDadosValidos(): void
    {
        $service = new UserRegistrationService(new UserRepository($this->entityManager));

        $result = $service->register($this->dadosUsuario());

        self::assertSame([], $result['errors']);
        self::assertNotNull($result['user_id']);

        $user = $this->entityManager->find(User::class, $result['user_id']);
        self::assertInstanceOf(User::class, $user);
        self::assertSame('ana@example.com', $user->getEmail());
        self::assertTrue($user->verifyPassword('senha-segura'));
    }

    public function testRejeitaUsuarioComDadosInvalidos(): void
    {
        $service = new UserRegistrationService(new UserRepository($this->entityManager));
        $dados = $this->dadosUsuario();
        $dados['email'] = 'email-invalido';
        $dados['password_confirmation'] = 'senha-diferente';

        $result = $service->register($dados);

        self::assertNull($result['user_id']);
        self::assertContains('Informe um e-mail válido (até 50 caracteres).', $result['errors']);
        self::assertContains('A confirmação de senha não corresponde.', $result['errors']);
    }

    public function testCadastraContatoParaUsuarioExistente(): void
    {
        $userService = new UserRegistrationService(new UserRepository($this->entityManager));
        $userResult = $userService->register($this->dadosUsuario());
        self::assertNotNull($userResult['user_id']);

        $service = new ContactRegistrationService(
            new ContactRepository($this->entityManager),
            new UserRepository($this->entityManager)
        );
        $result = $service->register([
            'name' => '  Bruno Silva  ',
            'nickname' => '  Bru  ',
            'company' => '  Acme  ',
            'phone' => '(71) 98765-4321',
            'address' => '  Rua Central  ',
            'birth_date' => '1990-05-10',
        ], $userResult['user_id']);

        self::assertSame([], $result['errors']);
        self::assertNotNull($result['contact_id']);

        $contact = $this->entityManager->find(Contact::class, $result['contact_id']);
        self::assertInstanceOf(Contact::class, $contact);
        self::assertSame('Bruno Silva', $contact->getName());
        self::assertSame('Bru', $contact->getNickname());
        self::assertSame('71987654321', $contact->getPhone());
        self::assertSame($userResult['user_id'], $contact->getUser()->getId());
    }

    public function testRejeitaContatoComTelefoneInvalido(): void
    {
        $userService = new UserRegistrationService(new UserRepository($this->entityManager));
        $userResult = $userService->register($this->dadosUsuario());
        self::assertNotNull($userResult['user_id']);

        $service = new ContactRegistrationService(
            new ContactRepository($this->entityManager),
            new UserRepository($this->entityManager)
        );
        $result = $service->register([
            'name' => 'Bruno Silva',
            'nickname' => '',
            'company' => '',
            'phone' => '123',
            'address' => '',
            'birth_date' => '',
        ], $userResult['user_id']);

        self::assertNull($result['contact_id']);
        self::assertContains('Informe um telefone com DDD e 10 ou 11 dígitos.', $result['errors']);
    }

    public function testRejeitaNomeVazioEDataDeNascimentoInvalida(): void
    {
        $userId = $this->criarUsuario();
        $service = $this->contactService();

        $result = $service->register([
            'name' => '  ',
            'birth_date' => '2025-02-30',
        ], $userId);

        self::assertNull($result['contact_id']);
        self::assertContains('Informe o nome do contato (até 100 caracteres).', $result['errors']);
        self::assertContains('Informe uma data de nascimento válida.', $result['errors']);
    }

    public function testListaContatosAtivosOrdenadosDoUsuario(): void
    {
        $userId = $this->criarUsuario();
        $service = $this->contactService();
        $service->register(['name' => 'Zeca', 'birth_date' => '1980-01-02'], $userId);
        $service->register(['name' => 'Ana', 'birth_date' => '1990-03-04'], $userId);

        $user = $this->entityManager->find(User::class, $userId);
        self::assertInstanceOf(User::class, $user);
        $contacts = (new ContactRepository($this->entityManager))->findActiveByUser($user);

        self::assertCount(2, $contacts);
        self::assertSame(['Ana', 'Zeca'], array_map(static fn (Contact $contact): string => $contact->getName(), $contacts));
        self::assertSame('1990-03-04', $contacts[0]->getBirthDate()?->format('Y-m-d'));
    }

    public function testAtualizaContatoERefleteOsNovosDados(): void
    {
        $userId = $this->criarUsuario();
        $service = $this->contactService();
        $created = $service->register(['name' => 'Bruno', 'address' => 'Rua A', 'birth_date' => '1990-01-01'], $userId);
        self::assertNotNull($created['contact_id']);

        $result = $service->update($created['contact_id'], [
            'name' => 'Bruno Silva',
            'address' => 'Rua B, 20',
            'birth_date' => '1991-02-03',
        ], $userId);

        self::assertSame([], $result['errors']);
        $this->entityManager->clear();
        $updated = $service->findForUser($created['contact_id'], $userId);
        self::assertInstanceOf(Contact::class, $updated);
        self::assertSame('Bruno Silva', $updated->getName());
        self::assertSame('Rua B, 20', $updated->getAddress());
        self::assertSame('1991-02-03', $updated->getBirthDate()?->format('Y-m-d'));
    }

    public function testRejeitaEdicaoComNomeVazioOuDataInvalidaSemAlterarContato(): void
    {
        $userId = $this->criarUsuario();
        $service = $this->contactService();
        $created = $service->register([
            'name' => 'Contato original',
            'birth_date' => '1990-05-09',
        ], $userId);
        self::assertNotNull($created['contact_id']);

        $missingName = $service->update($created['contact_id'], [
            'name' => ' ',
            'birth_date' => '1990-05-09',
        ], $userId);
        $invalidDate = $service->update($created['contact_id'], [
            'name' => 'Contato alterado',
            'birth_date' => '2025-02-30',
        ], $userId);

        self::assertContains('Informe o nome do contato (até 100 caracteres).', $missingName['errors']);
        self::assertContains('Informe uma data de nascimento válida.', $invalidDate['errors']);
        $contact = $service->findForUser($created['contact_id'], $userId);
        self::assertInstanceOf(Contact::class, $contact);
        self::assertSame('Contato original', $contact->getName());
        self::assertSame('1990-05-09', $contact->getBirthDate()?->format('Y-m-d'));
    }

    public function testExcluiContatoDaListagemAtiva(): void
    {
        $userId = $this->criarUsuario();
        $service = $this->contactService();
        $created = $service->register(['name' => 'Para excluir'], $userId);
        self::assertNotNull($created['contact_id']);

        self::assertTrue($service->softDelete($created['contact_id'], $userId));
        self::assertNull($service->findForUser($created['contact_id'], $userId));
        self::assertSame(0, $service->countActiveContactsForUser($userId));
        $user = $this->entityManager->find(User::class, $userId);
        self::assertInstanceOf(User::class, $user);
        self::assertSame([], (new ContactRepository($this->entityManager))->findActiveByUser($user));
    }

    public function testListaRenderizaDataDeNascimentoAcoesEConfirmacaoDeExclusao(): void
    {
        $userId = $this->criarUsuario();
        $result = $this->contactService()->register([
            'name' => 'Bruno Silva',
            'nickname' => 'Bru',
            'phone' => '71987654321',
            'birth_date' => '1990-05-09',
        ], $userId);
        self::assertNotNull($result['contact_id']);
        $contact = $this->entityManager->find(Contact::class, $result['contact_id']);
        self::assertInstanceOf(Contact::class, $contact);

        $html = $this->renderHomeView([$contact]);

        self::assertStringContainsString('Data de nascimento', $html);
        self::assertStringContainsString('09/05/1990', $html);
        self::assertStringContainsString('contato-editar.php?id=' . $result['contact_id'], $html);
        self::assertStringContainsString('Excluir Bruno Silva', $html);
        self::assertStringContainsString('Tem certeza de que deseja excluir', $html);
        self::assertStringContainsString('action="/contato-excluir.php"', $html);
    }

    public function testFormularioDeContatoExponeCamposEValidacaoDeNome(): void
    {
        $values = [
            'name' => '',
            'nickname' => '',
            'company' => '',
            'phone' => '',
            'birth_date' => '',
            'address' => '',
        ];
        $errors = ['Informe o nome do contato (até 100 caracteres).'];
        $html = $this->renderContactForm($values, $errors, false);

        self::assertStringContainsString('name="name"', $html);
        self::assertStringContainsString('name="address"', $html);
        self::assertStringContainsString('name="birth_date"', $html);
        self::assertStringContainsString('required', $html);
        self::assertStringContainsString('Informe o nome do contato', $html);
        self::assertStringContainsString('action="/contato-novo.php"', $html);

        $editHtml = $this->renderContactForm($values, [], true);
        self::assertStringContainsString('action="/contato-editar.php?id=42"', $editHtml);
        self::assertStringContainsString('Salvar alterações', $editHtml);
    }

    private function criarUsuario(): int
    {
        $result = (new UserRegistrationService(new UserRepository($this->entityManager)))
            ->register($this->dadosUsuario());
        self::assertNotNull($result['user_id']);

        return $result['user_id'];
    }

    private function contactService(): ContactRegistrationService
    {
        return new ContactRegistrationService(
            new ContactRepository($this->entityManager),
            new UserRepository($this->entityManager)
        );
    }

    /** @param list<Contact> $contacts */
    private function renderHomeView(array $contacts): string
    {
        $contactDeleteCsrfToken = 'csrf-test-token';
        $pageTitle = 'Contatos';
        $emptyMessage = 'Nenhum contato cadastrado.';
        $showNewContactButton = true;
        $successMessage = null;
        $contactCount = count($contacts);
        $duplicateCount = 0;
        $userName = 'Ana Souza';
        $userInitial = 'A';
        $activeNavigation = 'contacts';
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $formatPhone = static fn (?string $phone): string => $phone ?? '—';

        ob_start();
        require dirname(__DIR__) . '/app/Views/home.php';

        return (string) ob_get_clean();
    }

    /** @param array<string, string> $values @param list<string> $errors */
    private function renderContactForm(array $values, array $errors, bool $isEditing): string
    {
        $csrfToken = 'csrf-test-token';
        $contactId = $isEditing ? 42 : null;
        $duplicateCount = 0;
        $contactCount = 0;
        $userName = 'Ana Souza';
        $userInitial = 'A';
        $activeNavigation = 'contacts';
        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        ob_start();
        require dirname(__DIR__) . '/app/Views/contact-create.php';

        return (string) ob_get_clean();
    }

    /** @return array{name: string, cpf: string, email: string, birth_date: string, gender: string, phone: string, password: string, password_confirmation: string} */
    private function dadosUsuario(): array
    {
        return [
            'name' => 'Ana Souza',
            'cpf' => '123.456.789-00',
            'email' => ' ANA@EXAMPLE.COM ',
            'birth_date' => '1992-04-15',
            'gender' => Sexo::Feminino->value,
            'phone' => '(71) 98765-4321',
            'password' => 'senha-segura',
            'password_confirmation' => 'senha-segura',
        ];
    }
}
