const phoneInputs = document.querySelectorAll('#contact-phone, #register-phone');
const cpfInput = document.querySelector('#register-cpf');
const emailInputs = document.querySelectorAll('input[type="email"]');
const birthDateInputs = document.querySelectorAll('#contact-birth-date, #register-birth-date');

phoneInputs.forEach((phoneInput) => {
    if (!(phoneInput instanceof HTMLInputElement)) {
        return;
    }

    phoneInput.addEventListener('input', () => {
        const digits = phoneInput.value.replace(/\D/g, '').slice(0, 11);
        const areaCode = digits.slice(0, 2);
        const number = digits.slice(2);

        if (digits.length <= 2) {
            phoneInput.value = digits ? `(${digits}` : '';
            return;
        }

        let formatted = `(${areaCode}) ${number.slice(0, 1)}`;
        if (number.length > 1) {
            formatted += ` ${number.slice(1, 5)}`;
        }
        if (number.length > 5) {
            formatted += `-${number.slice(5, 9)}`;
        }

        phoneInput.value = formatted;
    });
    phoneInput.dispatchEvent(new Event('input'));
});

if (cpfInput instanceof HTMLInputElement) {
    cpfInput.addEventListener('input', () => {
        const digits = cpfInput.value.replace(/\D/g, '').slice(0, 11);
        let formatted = digits.slice(0, 3);

        if (digits.length > 3) {
            formatted += `.${digits.slice(3, 6)}`;
        }
        if (digits.length > 6) {
            formatted += `.${digits.slice(6, 9)}`;
        }
        if (digits.length > 9) {
            formatted += `-${digits.slice(9, 11)}`;
        }

        cpfInput.value = formatted;
    });
    cpfInput.dispatchEvent(new Event('input'));
}

emailInputs.forEach((emailInput) => {
    if (!(emailInput instanceof HTMLInputElement)) {
        return;
    }

    const validateEmail = () => {
        emailInput.setCustomValidity(
            emailInput.value !== '' && emailInput.validity.typeMismatch
                ? 'Informe um e-mail válido.'
                : ''
        );
    };

    emailInput.addEventListener('input', validateEmail);
    validateEmail();
});

birthDateInputs.forEach((birthDateInput) => {
    if (!(birthDateInput instanceof HTMLInputElement)) {
        return;
    }

    const today = new Date();
    const localDate = [today.getFullYear(), String(today.getMonth() + 1).padStart(2, '0'), String(today.getDate()).padStart(2, '0')].join('-');
    birthDateInput.max = localDate;
});
