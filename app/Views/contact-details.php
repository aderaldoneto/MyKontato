<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17254b">
    <title><?= $contact === null ? 'Contato não encontrado' : $escape($contact->getName()) ?> | MyKontato</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="app-body">
    <div class="dashboard-layout">
        <?php require __DIR__ . '/partials/menu.php'; ?>

        <main class="dashboard-main">
            <header class="topbar">
                <nav class="breadcrumb" aria-label="Caminho de navegação">
                    <a href="<?= $escape($listUrl) ?>"><?= $escape($listTitle) ?></a><span aria-hidden="true">›</span><strong>Detalhes do contato</strong>
                </nav>
            </header>

            <div class="dashboard-content contact-details-page">
                <section class="page-heading">
                    <div>
                        <h1><?= $contact === null ? 'Contato não encontrado' : $escape($contact->getName()) ?></h1>
                        <?php if ($contact !== null && $contact->getNickname() !== null): ?>
                            <p><?= $escape($contact->getNickname()) ?></p>
                        <?php endif; ?>
                    </div>
                    <a class="secondary-button" href="<?= $escape($listUrl) ?>">Voltar</a>
                </section>

                <?php if ($contact !== null): ?>
                    <section class="contact-details-card" aria-label="Detalhes do contato">
                        <dl class="contact-details-list">
                            <div><dt>Nome</dt><dd><?= $escape($contact->getName()) ?></dd></div>
                            <div><dt>Apelido</dt><dd><?= $escape($contact->getNickname() ?? '—') ?></dd></div>
                            <div><dt>Empresa</dt><dd><?= $escape($contact->getCompany() ?? '—') ?></dd></div>
                            <div><dt>Telefone</dt><dd><?= $escape($formatPhone($contact->getPhone())) ?></dd></div>
                            <div><dt>Data de nascimento</dt><dd><?= $contact->getBirthDate()?->format('d/m/Y') ?? '—' ?></dd></div>
                            <div class="contact-detail-address"><dt>Endereço</dt><dd><?= $contact->getAddress() !== null ? nl2br($escape($contact->getAddress())) : '—' ?></dd></div>
                        </dl>
                    </section>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
