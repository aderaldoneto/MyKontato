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
                        <strong class="summary-value">555</strong>
                    </article>
                </section>

                <section class="contact-section" aria-labelledby="contact-title">
                    <p>Lista</p>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
