import {sendRequest} from "../utils.js";

const enrollmentForm = document.querySelector('#enrollment-form');
const activitySelect = enrollmentForm.querySelector('#activity');
const formMessage = document.querySelector('#form-message');

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.set(activitySelect.name, activitySelect.value);
    const response = await sendRequest(enrollmentForm.action, formData);
    activitySelect.removeAttribute('class');
    if (response.ok) {
        window.location.reload();
    } else {
        const responseBody = await response.json();
        const {message, fields} = responseBody;
        formMessage.setAttribute('class', 'form-error');
        formMessage.innerText = message;
        fields.forEach(field => {
            enrollmentForm.querySelector('#' + field).setAttribute('class', 'input-error');
        });
    }
}

enrollmentForm.addEventListener('submit', onSubmit);
