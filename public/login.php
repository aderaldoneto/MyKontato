<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1458d4">
    <title>MyKontato | Acesso</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <main class="login-page">
        <section class="brand-panel" aria-label="MyKontato">
            <a class="brand" href="/login.php" aria-label="MyKontato — início">
                <span aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="8" height="4" x="8" y="2" rx="1"/>
                        <path d="M8 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.5M16 4h2a2 2 0 0 1 1.73 1M8 18h1"/>
                        <path d="m14 14 4-4a2 2 0 0 1 3 3l-4 4-4 1z"/>
                    </svg>
                </span>
                <span>My<span class="brand-light">Kontato</span></span>
            </a>
            <div class="brand-message">
                <span class="brand-kicker">Tudo em contato</span>
                <h1>As pessoas importantes, na palma de sua mão.</h1>
                <p>Organize seus contatos em um só lugar e encontre quem você precisa com facilidade.</p>
            </div>
            <p class="brand-footer">Simples de organizar. Fácil de encontrar.</p>
        </section>

        <section class="form-panel" aria-label="Acesso à conta">
            <div class="login-card" id="login">
                <p class="form-welcome">Bem-vindo de volta</p>
                <h2>Acesse sua conta</h2>
                <p class="form-description">Entre para continuar organizando seus contatos.</p>

                <form class="login-form" action="#login" method="post">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" placeholder="E-mail" autocomplete="email" required>

                    <label for="password">Senha</label>
                    <input id="password" name="password" type="password" placeholder="Digite sua senha" autocomplete="current-password" required>

                    <label class="remember-option" for="remember">
                        <input id="remember" name="remember" type="checkbox">
                        <span class="checkbox-ui" aria-hidden="true"></span>
                        <span>Manter conectado</span>
                    </label>

                    <button type="submit">Entrar <span aria-hidden="true">→</span></button>
                </form>

                <p class="signup-prompt">Não tem uma conta? <a class="text-link" href="/cadastro.php">Cadastre-se</a></p>
            </div>
            <p class="form-footer">© 2026 AN Tecnologia</p>
        </section>
    </main>
</body>
</html>
