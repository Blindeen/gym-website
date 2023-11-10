const passwordInput = document.querySelector('#password');
const confirmPasswordInput = document.querySelector('#confirm-password');
const confirmPasswordError = document.querySelector('#confirm-password-error');
const roleSelect = document.querySelector('#role');
const paymentMethodSelect = document.querySelector('#payment-method')

const roles = {
    trainer: '0',
    client: '1',
}

roleSelect.addEventListener('change', () => {
    if (roleSelect.value === roles.client) {
        paymentMethodSelect.style.display = 'block';
        console.log(roleSelect.value);
    } else {
        paymentMethodSelect.style.display = 'none';
    }
})
