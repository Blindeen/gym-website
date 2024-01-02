import {sendRequest} from "../utils.js";

const loginForm = document.querySelector('#login-form');
const email = loginForm.querySelector('#email');
const password = loginForm.querySelector('#password');
const formElements = [email, password];
const formMessage = document.querySelector('#form-message');

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formElements.forEach(el => formData.set(el.name, el.value));
    const response = await sendRequest(loginForm.action, formData);
    const responseBody = await response.json();
    const {message} = responseBody;

    formMessage.innerText = message;
    formElements.forEach(el => el.removeAttribute('class'));
    if (response.ok) {
        formMessage.setAttribute('class', 'form-success');
        formElements.forEach(el => el.value = '');
        setTimeout(() => location.replace(location.origin), 1000);
    } else {
        formMessage.setAttribute('class', 'form-error');
        switch (message) {
            case 'Invalid email':
            case 'User not found': {
                email.setAttribute('class', 'input-error');
            } break;
            case 'Incorrect password':
            case 'Password cannot be empty': {
                password.setAttribute('class', 'input-error');
            }
        }
    }
};

loginForm.addEventListener('submit', onSubmit);
