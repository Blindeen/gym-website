const emailInput = document.querySelector('#email');
const passwordInput = document.querySelector('#password');
const error = document.querySelector('.error');

emailInput.addEventListener('focus', () => error && (error.style.display = 'none'));
