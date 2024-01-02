import {sendRequest} from "../utils.js";

const addActivityForm = document.querySelector('#add-activity-form');
const addActivityFormElements = addActivityForm.elements;
const addActivityFormElementsArray = Array.from(addActivityFormElements).slice(0, addActivityFormElements.length - 1);
const addActivityFormMessage = document.querySelector('#form-wrapper').querySelector('#form-message');

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    addActivityFormElementsArray.forEach(el => formData.set(el.name, el.value));
    const responseBody = await sendRequest(addActivityForm.action, formData);
    const {statusCode, message, fields} = responseBody;
    addActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
    if (statusCode >= 200 && statusCode <= 299) {
        window.location.reload();
    } else {
        addActivityFormMessage.setAttribute('class', 'form-error');
        addActivityFormMessage.innerText = message;
        fields.forEach(field => {
            addActivityForm.querySelector('#' + field).setAttribute('class', 'input-error');
        });
    }
};

export default onSubmit;
