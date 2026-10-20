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
        <aside class="sidebar">
            <a class="brand sidebar-brand" href="/home.php" aria-label="MyKontato — início">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <rect x="4" y="4" width="16" height="16" rx="4" />
                        <circle cx="9" cy="10" r="2" />
                        <path d="M6.5 17a2.5 2.5 0 0 1 5 0m2-7h4m-4 4h4" />
                    </svg>
                </span>
                <span>My<span class="brand-light">Kontato</span></span>
            </a>

            <nav class="sidebar-nav" aria-label="Navegação principal">
                <a class="sidebar-link active" href="/home.php" aria-current="page">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-contact preview-icon"><path d="M16 2v2"/><path d="M7 21v-2a2 2 0 012-2h6a2 2 0 012 2v2"/><path d="M8 2v2"/><circle cx="12" cy="10" r="3"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    Contatos
                </a>
                <a class="sidebar-link" href="#duplicados">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sticky-notes preview-icon"><path d="M10 8a2.4 2.4 0 0 1 1.706.706l3.588 3.588A2.4 2.4 0 0 1 16 14v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2z"/><path d="M10 8v5a1 1 0 0 0 1 1h5"/><path d="M8 4a2 2 0 0 1 2-2h6a2.4 2.4 0 0 1 1.706.706l3.588 3.588A2.4 2.4 0 0 1 22 8v6a2 2 0 0 1-2 2"/><path d="M16 2v5a1 1 0 0 0 1 1h5"/></svg>
                    Duplicados
                </a>
            </nav>

            <div class="sidebar-bottom">
                <a class="sidebar-link logout-button" href="/logout.php">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M10 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h5m5-4 4-4-4-4m4 4H9"/></svg>
                    Sair
                </a>
                <div class="profile">
                    <span class="profile-avatar" aria-hidden="true">MK</span>
                    <span><strong>Minha conta</strong><small>MyKontato</small></span>
                </div>
            </div>
        </aside>

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
                    <a class="primary-button" href="#contatos"><span aria-hidden="true">＋</span> Novo contato</a>
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
