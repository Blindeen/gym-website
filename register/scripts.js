import {sendRequest, getSessionStorage} from "../utils.js";

const registerForm = document.querySelector('#register-form');
const registerFormElements = registerForm.elements;
const registerFormElementsArray = Array.from(registerFormElements).slice(0, registerFormElements.length - 1);
const formMessage = registerForm.querySelector('#form-message');

const enroll = async (activity) => {
    const formData = new FormData();
    formData.set('activity', activity.id);
    await sendRequest('../client-dashboard/enroll.php', formData);
}

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    registerFormElementsArray.forEach(el => formData.set(el.name, el.value));
    const response = await sendRequest(registerForm.action, formData);
    const responseBody = await response.json();
    const {message} = responseBody;

    formMessage.innerText = message;
    registerFormElementsArray.forEach(el => el.removeAttribute('class'));
    if (response.ok) {
        formMessage.setAttribute('class', 'form-success');
        registerFormElementsArray.forEach(el => el.value = '');

        const favoriteActivities = JSON.parse(getSessionStorage('favoriteActivities') ?? '[]');
        favoriteActivities.forEach(act => enroll(act));

        setTimeout(() => window.location.replace(location.origin), 2000);
    } else {
        const {fields} = responseBody;
        formMessage.setAttribute('class', 'form-error');
        fields.forEach(el => registerForm.querySelector('#' + el).setAttribute('class', 'input-error'));
    }
}

registerForm.addEventListener('submit', onSubmit);
