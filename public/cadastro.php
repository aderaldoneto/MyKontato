<?php

declare(strict_types=1);

use App\Enums\Sexo;

require_once dirname(__DIR__) . '/app/Enums/Sexo.php';
?>
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
                <span class="brand-kicker">Sua agenda começa aqui</span>
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

                <form class="register-form" action="#" method="post">
                    <div class="field">
                        <label for="register-name">Nome completo</label>
                        <input id="register-name" name="name" type="text" placeholder="Digite seu nome completo" autocomplete="name" required>
                    </div>
                    <div class="field">
                        <label for="register-cpf">CPF</label>
                        <input id="register-cpf" name="cpf" type="text" placeholder="000.000.000-00" inputmode="numeric" autocomplete="off" required>
                    </div>
                    <div class="field">
                        <label for="register-birth-date">Data de nascimento</label>
                        <input id="register-birth-date" name="birth_date" type="date" autocomplete="bday" required>
                    </div>
                    <div class="field">
                        <label for="register-gender">Sexo</label>
                        <select id="register-gender" name="gender" required>
                            <option value="" selected disabled>Selecione</option>
                            <?php foreach (Sexo::cases() as $sexo): ?>
                                <option value="<?= htmlspecialchars($sexo->value, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars(ucfirst($sexo->value), ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field field-full">
                        <label for="register-phone">Telefone</label>
                        <input id="register-phone" name="phone" type="tel" placeholder="(00) 00000-0000" autocomplete="tel" inputmode="tel" required>
                    </div>
                    <div class="field field-full">
                        <label for="register-email">E-mail</label>
                        <input id="register-email" name="email" type="email" placeholder="voce@exemplo.com" autocomplete="email" required>
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
