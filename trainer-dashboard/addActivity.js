import {sendRequest} from "../utils.js";

const addActivityForm = document.querySelector('#add-activity-form');
const addActivityFormElements = addActivityForm.elements;
const addActivityFormElementsArray = Array.from(addActivityFormElements).slice(0, addActivityFormElements.length - 1);
const addActivityFormMessage = document.querySelector('#form-wrapper').querySelector('#form-message');

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    addActivityFormElementsArray.forEach(el => formData.set(el.name, el.value));
    const response = await sendRequest(addActivityForm.action, formData);
    const responseBody = await response.json();
    const {message} = responseBody;
    addActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
    if (response.ok) {
        window.location.reload();
    } else {
        const {fields} = responseBody;
        addActivityFormMessage.setAttribute('class', 'form-error');
        addActivityFormMessage.innerText = message;
        fields.forEach(field => {
            addActivityForm.querySelector('#' + field).setAttribute('class', 'input-error');
        });
    }
};

export default onSubmit;
