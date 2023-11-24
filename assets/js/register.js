const roleSelect = document.querySelector('#role');
const paymentMethodSelect = document.querySelector('.field:has(#payment-method)');
const passTypeSelect = document.querySelector('.field:has(#pass)');

const roles = {
    trainer: '0',
    client: '1',
};

roleSelect.addEventListener('change', () => {
    if (roleSelect.value === roles.client) {
        paymentMethodSelect.style.display = 'block';
        passTypeSelect.style.display = 'block';
    } else {
        paymentMethodSelect.style.display = 'none';
        passTypeSelect.style.display = 'none';
    }
});
