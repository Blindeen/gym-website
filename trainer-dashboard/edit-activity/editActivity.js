import {sendRequest} from "../../utils.js";
import {editModal, constants, openMondal} from "../../components/modal/script.js";

const modalForm = editModal.querySelector('#modal-form');
const editActivityFormElements = modalForm.elements;
const editActivityFormElementsArray = Array.from(editActivityFormElements).slice(0, editActivityFormElements.length - 1);
const editActivityFormMessage = document.querySelector('#modal-form-wrapper').querySelector('#form-message');

const retrieveData = (editButton) => {
    const actionCell = editButton.parentElement;
    const tableRow = Array.from(actionCell.parentElement.children);
    const dataRow = tableRow.slice(0, tableRow.indexOf(actionCell))
    const allFormElements = Array.from(modalForm.elements);
    let formFields = allFormElements.slice(0, allFormElements.length - 1);
    formFields = [formFields[0], formFields[3], formFields[1], formFields[2], formFields[4]];

    return {formFields, dataRow};
};

const setFormValues = (formFields, dataRow) => {
    formFields.forEach((el, idx) => {
        const data = dataRow[idx];

        if (el.tagName === constants.selectTag) {
            const option = Array.from(el.children).find(
                element => element.innerText === data.innerText
            );
            el.value = option.value;
        } else {
            el.value = data.innerText;
        }
    })
};

const onSubmit = (editButton) => editButton.addEventListener('click', () => {
    const {formFields, dataRow} = retrieveData(editButton);
    setFormValues(formFields, dataRow);
    const activityID = editButton.getAttribute('data-id');

    const onSubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        editActivityFormElementsArray.forEach(el => formData.set(el.name, el.value));
        const url = modalForm.action + '?id=' + activityID;
        const response = await sendRequest(url, formData);
        const responseBody = await response.json();
        const {message} = responseBody;
        editActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
        if (response.ok) {
            window.location.reload();
        } else {
            const {fields} = responseBody;
            editActivityFormMessage.setAttribute('class', 'form-error');
            editActivityFormMessage.innerText = message;
            fields.forEach(field => {
                modalForm.querySelector('#' + field).setAttribute('class', 'input-error');
            });
        }
    }

    modalForm.addEventListener('submit', onSubmit);
    openMondal();
});

export default onSubmit;
