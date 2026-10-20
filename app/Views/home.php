<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17254b">
    <title>MyKontato | <?= $escape($pageTitle) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="app-body">
    <div class="dashboard-layout">
        <?php require __DIR__ . '/partials/menu.php'; ?>

        <main class="dashboard-main">
            <header class="topbar">
                <nav class="breadcrumb" aria-label="Caminho de navegação">
                    <span>MyKontato</span><span aria-hidden="true">›</span><strong><?= $escape($pageTitle) ?></strong>
                </nav>
            </header>

            <div class="dashboard-content">
                <?php if ($successMessage !== null): ?>
                    <div class="form-success" role="status">
                        <?= $escape($successMessage) ?>
                        <button class="alert-close" type="button" aria-label="Fechar aviso">×</button>
                    </div>
                <?php endif; ?>
                <?php if ($showNewContactButton): ?>
                    <section class="welcome-row">
                        <div></div>
                        <a class="primary-button" href="/contato-novo.php"><span aria-hidden="true">＋</span> Novo contato</a>
                    </section>
                <?php endif; ?>

                <section class="contact-section" aria-labelledby="contact-title">
                    <div class="contact-list-heading">
                        <h2 id="contact-title"></h2>
                        <?php if (in_array($activeNavigation, ['contacts', 'duplicates'], true)): ?>
                            <form class="contact-search" method="get" action="<?= $activeNavigation === 'duplicates' ? '/contatos-duplicados.php' : '/home.php' ?>" role="search">
                                <input id="contact-search-input" type="search" name="q" value="<?= $escape($searchQuery) ?>" placeholder="Nome, apelido ou telefone">
                                <button type="submit" aria-label="Buscar contatos" title="Buscar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="contact-table-wrap">
                        <table class="contact-table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Nome</th>
                                    <th scope="col">Data de nascimento</th>
                                    <th scope="col">Apelido</th>
                                    <th scope="col">Telefone</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($contacts === []): ?>
                                    <tr><td class="contact-empty" colspan="6"><?= $escape($emptyMessage) ?></td></tr>
                                <?php else: ?>
                                    <?php foreach ($contacts as $contact): ?>
                                        <tr>
                                            <td><span class="contact-avatar" aria-hidden="true"><?= $escape(mb_strtoupper(mb_substr($contact['name'], 0, 1))) ?></span></td>
                                            <td><a class="contact-name-link" href="/contato-detalhes.php?id=<?= $contact['id'] ?><?= $activeNavigation === 'duplicates' ? '&amp;from=duplicates' : '' ?>"><?= $escape($contact['name']) ?></a></td>
                                            <td><?= $escape($contact['birth_date'] !== null ? (new DateTimeImmutable($contact['birth_date']))->format('d/m/Y') : '—') ?></td>
                                            <td><?= $escape($contact['nickname'] ?? '—') ?></td>
                                            <td><?= $escape($formatPhone($contact['phone'])) ?></td>
                                            <td>
                                                <a href="/contato-editar.php?id=<?= $contact['id'] ?>" title="Editar contato">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil preview-icon"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                                                </a>
                                                <button type="button" class="delete-contact-link" data-contact-id="<?= $contact['id'] ?>" data-contact-name="<?= $escape($contact['name']) ?>" aria-label="Excluir <?= $escape($contact['name']) ?>" title="Excluir contato">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash preview-icon"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                </button>
                                            </td>
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
    <dialog class="contact-delete-dialog" aria-labelledby="contact-delete-title">
        <form action="/contato-excluir.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $escape($contactDeleteCsrfToken) ?>">
            <input type="hidden" name="id" value="">
            <h2 id="contact-delete-title">Excluir contato</h2>
            <p>Tem certeza de que deseja excluir <strong data-delete-contact-name></strong>?</p>
            <div class="contact-delete-actions">
                <button class="secondary-button" type="button" data-delete-cancel>Cancelar</button>
                <button class="delete-confirm-button" type="submit">Confirmar</button>
            </div>
        </form>
    </dialog>
<script src="/assets/js/alerts.js" defer></script>
<script src="/assets/js/contact-delete.js" defer></script>
<script src="/assets/js/contact-search.js" defer></script>
</body>
</html>
