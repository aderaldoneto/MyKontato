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
