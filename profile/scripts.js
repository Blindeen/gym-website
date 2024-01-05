import {sendRequest} from '../utils.js';

const profileForm = document.querySelector('#profile-form');
const formElements = profileForm.elements;
const formElementsArray = Array.from(formElements).slice(0, formElements.length - 1);
const formMessage = profileForm.querySelector('#form-message');

const response = await sendRequest('fetch-user-data.php');
const responseBody = await response.json();
if (response.ok) {
    const {userData} = responseBody;
    userData.forEach((value, idx) => {
        formElementsArray[idx].value = value
    })
} else {
    const {message} = responseBody;
    formMessage.setAttribute('class', 'form-error');
    formMessage.innerText = message;
}

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formElementsArray.forEach(el => formData.set(el.name, el.value));
    const response = await sendRequest(profileForm.action, formData);

    formElementsArray.forEach(el => el.removeAttribute('class'));
    if (response.ok) {
        location.reload();
    } else {
        const responseBody = await response.json();
        const {message, fields} = responseBody;

        formMessage.setAttribute('class', 'form-error');
        formMessage.innerText = message;
        fields.forEach(el => profileForm.querySelector('#' + el).setAttribute('class', 'input-error'));
    }
};

profileForm.addEventListener('submit', onSubmit);
