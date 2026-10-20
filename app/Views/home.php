<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17254b">
    <title>MyKontato | Visão geral</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="app-body">
    <div class="dashboard-layout">
        <?php require __DIR__ . '/partials/menu.php'; ?>

        <main class="dashboard-main">
            <header class="topbar">
                <nav class="breadcrumb" aria-label="Caminho de navegação">
                    <span>MyKontato</span><span aria-hidden="true">›</span><strong>Contatos</strong>
                </nav>
            </header>

            <div class="dashboard-content">
                <?php if ($successMessage !== null): ?>
                    <div class="form-success" role="status">
                        <?= $escape($successMessage) ?>
                        <button class="alert-close" type="button" aria-label="Fechar aviso">×</button>
                    </div>
                <?php endif; ?>
                <section class="welcome-row">
                    <div>
                    </div>
                    <a class="primary-button" href="/contato-novo.php"><span aria-hidden="true">＋</span> Novo contato</a>
                </section>

                <section class="summary-grid" aria-label="Resumo dos contatos">
                    <article class="summary-card">
                        <div class="summary-card-top"><span class="summary-icon contacts-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.5"/><path d="M3 20v-1.5A4.5 4.5 0 0 1 7.5 14h3a4.5 4.5 0 0 1 4.5 4.5V20H3Zm13-12a3.5 3.5 0 0 1 0 6.8m2 1.2a4 4 0 0 1 3 3.9v.1h-3"/></svg>
                        </span></div>
                        <span class="summary-label">Todos os contatos</span>
                        <strong class="summary-value"><?= $contactCount ?></strong>
                    </article>
                </section>

                <section class="contact-section" aria-labelledby="contact-title">
                    <div class="contact-list-heading">
                        <h2 id="contact-title">Contatos</h2>
                    </div>
                    <div class="contact-table-wrap">
                        <table class="contact-table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Apelido</th>
                                    <th scope="col">Telefone</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($contacts === []): ?>
                                    <tr><td class="contact-empty" colspan="3">Nenhum contato cadastrado.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td>IMG DE LETRA</td>
                                            <td><a class="contact-name-link" href="/contato-detalhes.php?id=<?= $contact->getId() ?>"><?= $escape($contact->getName()) ?></a></td>
                                            <td><?= $escape($contact->getNickname() ?? '—') ?></td>
                                            <td><?= $escape($formatPhone($contact->getPhone())) ?></td>
                                            <td><a href="/contato-editar.php?id=<?= $contact->getId() ?>">Lápis de editar</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>
<script src="/assets/js/alerts.js" defer></script>
</body>
</html>
