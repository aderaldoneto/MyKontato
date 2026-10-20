document.querySelectorAll('.form-errors, .form-success').forEach((alert) => {
    const dismiss = () => {
        alert.classList.add('is-dismissed');
        window.setTimeout(() => alert.remove(), 200);
    };

    alert.querySelector('.alert-close')?.addEventListener('click', dismiss);
    window.setTimeout(dismiss, 3000);
});
