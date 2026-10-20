document.querySelectorAll('.contact-search input[name="q"]').forEach((input) => {
    let timeout;

    input.addEventListener('input', () => {
        window.clearTimeout(timeout);
        const query = input.value.trim();

        if (query.length !== 0 && query.length < 3) {
            return;
        }

        timeout = window.setTimeout(() => input.form?.requestSubmit(), 300);
    });
});
