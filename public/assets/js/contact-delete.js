const deleteDialog = document.querySelector('.contact-delete-dialog');

if (deleteDialog instanceof HTMLDialogElement) {
    const deleteForm = deleteDialog.querySelector('form');
    const contactIdInput = deleteForm?.querySelector('input[name="id"]');
    const contactName = deleteDialog.querySelector('[data-delete-contact-name]');

    document.querySelectorAll('.delete-contact-link').forEach((button) => {
        button.addEventListener('click', () => {
            if (!(contactIdInput instanceof HTMLInputElement) || !contactName) {
                return;
            }

            contactIdInput.value = button.dataset.contactId ?? '';
            contactName.textContent = button.dataset.contactName ?? '';
            deleteDialog.showModal();
        });
    });

    deleteDialog.querySelector('[data-delete-cancel]')?.addEventListener('click', () => {
        deleteDialog.close();
    });

    deleteForm?.addEventListener('submit', () => {
        deleteDialog.close();
    });
}
