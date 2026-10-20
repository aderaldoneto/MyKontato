<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#17254b">
    <title>Novo contato | MyKontato</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body class="app-body">
    <div class="dashboard-layout">
        <?php require __DIR__ . '/partials/menu.php'; ?>

        <main class="dashboard-main">
            <header class="topbar">
                <nav class="breadcrumb" aria-label="Caminho de navegação">
                    <a href="/home.php">Contatos</a><span aria-hidden="true">›</span><strong>Novo contato</strong>
                </nav>
            </header>

            <div class="dashboard-content contact-create-page">
                <section class="page-heading">
                    <div>
                        <h1>Novo contato</h1>
                    </div>
                    <a class="secondary-button" href="/home.php">Voltar</a>
                </section>

                <section class="contact-form-card" aria-labelledby="contact-form-title">
                    <?php if ($errors !== []): ?>
                        <div class="form-errors" role="alert">
                            <button class="alert-close" type="button" aria-label="Fechar aviso">×</button>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= $escape($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form class="contact-form" action="/contato-novo.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
                        <div class="field field-full">
                            <label for="contact-name">Nome completo<span aria-hidden="true">*</span></label>
                            <input id="contact-name" name="name" type="text" maxlength="100" value="<?= $escape($values['name']) ?>" autocomplete="name" placeholder="Nome completo" required>
                        </div>

                        <div class="field">
                            <label for="contact-nickname">Apelido</label>
                            <input id="contact-nickname" name="nickname" type="text" maxlength="50" value="<?= $escape($values['nickname']) ?>" placeholder="Apelido">
                        </div>

                        <div class="field">
                            <label for="contact-company">Empresa</label>
                            <input id="contact-company" name="company" type="text" maxlength="100" value="<?= $escape($values['company']) ?>" autocomplete="organization" placeholder="Nome da empresa">
                        </div>

                        <div class="field">
                            <label for="contact-phone">Telefone</label>
                            <input id="contact-phone" name="phone" type="tel" maxlength="16" value="<?= $escape($values['phone']) ?>" inputmode="numeric" autocomplete="tel" placeholder="(99) 9 9999-9999">
                        </div>

                        <div class="field">
                            <label for="contact-birth-date">Data de nascimento</label>
                            <input id="contact-birth-date" name="birth_date" type="date" value="<?= $escape($values['birth_date']) ?>" autocomplete="bday">
                        </div>

                        <div class="field field-full">
                            <label for="contact-address">Endereço</label>
                            <textarea id="contact-address" name="address" rows="3" autocomplete="street-address" placeholder="Rua, número, bairro, cidade"><?= $escape($values['address']) ?></textarea>
                        </div>

                        <div class="contact-form-actions">
                            <a class="secondary-button" href="/home.php">Cancelar</a>
                            <button class="primary-button" type="submit">Salvar contato</button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>
<script src="/assets/js/alerts.js" defer></script>
<script src="/assets/js/form.js" defer></script>
</body>
</html>
