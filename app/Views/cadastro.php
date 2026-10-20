<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1458d4">
    <title>Criar conta | MyKontato</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <main class="login-page registration-page">
        <section class="brand-panel" aria-label="MyKontato">
            <a class="brand" href="/login.php" aria-label="MyKontato — entrar">
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
                <span class="brand-kicker">Sua agenda particular</span>
                <h1>Seus contatos, sempre por perto.</h1>
                <p>Crie sua conta e mantenha as informações importantes organizadas em um só lugar.</p>
            </div>
            <p class="brand-footer">Simples de organizar. Fácil de encontrar.</p>
        </section>

        <section class="form-panel registration-panel" aria-labelledby="registration-title">
            <div class="register-card">
                <p class="form-welcome">Vamos começar</p>
                <h2 id="registration-title">Crie sua conta</h2>
                <p class="form-description">Preencha seus dados para se cadastrar no MyKontato.</p>

                <?php if ($errors !== []): ?>
                    <div class="form-errors" role="alert">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $escape($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form class="register-form" action="/cadastro.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $escape($csrfToken) ?>">
                    <div class="field">
                        <label for="register-name">Nome completo</label>
                        <input id="register-name" name="name" type="text" maxlength="100" value="<?= $escape($values['name']) ?>" placeholder="Digite seu nome completo" autocomplete="name" required>
                    </div>
                    <div class="field">
                        <label for="register-cpf">CPF</label>
                        <input id="register-cpf" name="cpf" type="text" value="<?= $escape($values['cpf']) ?>" placeholder="000.000.000-00" inputmode="numeric" autocomplete="off" required>
                    </div>
                    <div class="field">
                        <label for="register-birth-date">Data de nascimento</label>
                        <input id="register-birth-date" name="birth_date" type="date" value="<?= $escape($values['birth_date']) ?>" autocomplete="bday" required>
                    </div>
                    <div class="field">
                        <label for="register-gender">Sexo</label>
                        <select id="register-gender" name="gender" required>
                            <option value="" disabled <?= $values['gender'] === '' ? 'selected' : '' ?>>Selecione</option>
                            <?php foreach (\App\Enums\Sexo::cases() as $sexo): ?>
                                <option value="<?= $escape($sexo->value) ?>" <?= $values['gender'] === $sexo->value ? 'selected' : '' ?>>
                                    <?= $escape(ucfirst(str_replace('_', ' ', $sexo->value))) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field field-full">
                        <label for="register-phone">Celular</label>
                        <input id="register-phone" name="phone" type="tel" value="<?= $escape($values['phone']) ?>" placeholder="(00) 00000-0000" autocomplete="tel" inputmode="tel" required>
                    </div>
                    <div class="field field-full">
                        <label for="register-email">E-mail</label>
                        <input id="register-email" name="email" type="email" maxlength="50" value="<?= $escape($values['email']) ?>" placeholder="voce@exemplo.com" autocomplete="email" required>
                    </div>
                    <div class="field field-full">
                        <label for="register-password">Senha</label>
                        <input id="register-password" name="password" type="password" placeholder="Crie uma senha" autocomplete="new-password" required>
                    </div>
                    <div class="field field-full">
                        <label for="register-password-confirmation">Confirmar senha</label>
                        <input id="register-password-confirmation" name="password_confirmation" type="password" placeholder="Digite sua senha novamente" autocomplete="new-password" required>
                    </div>
                    <button class="register-submit" type="submit">Cadastrar <span aria-hidden="true">→</span></button>
                </form>
                <p class="signup-prompt">Já tem uma conta? <a class="text-link" href="/login.php">Entrar</a></p>
            </div>
            <p class="form-footer">© 2026 AN Tecnologia</p>
        </section>
    </main>
</body>
</html>
