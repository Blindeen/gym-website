const addActivityForm = document.querySelector('#add-activity-form');
const addActivityFormElements = addActivityForm.elements;
const addActivityFormElementsArray = Array.from(addActivityFormElements).slice(0, addActivityFormElements.length - 1);
const addActivityFormMessage = document.querySelector('#form-wrapper').querySelector('#form-message');

const modalForm = editModal.querySelector('#modal-form');
const editButtons = document.querySelectorAll('.edit-button');
const editActivityFormElements = modalForm.elements;
const editActivityFormElementsArray = Array.from(editActivityFormElements).slice(0, editActivityFormElements.length - 1);
const editActivityFormMessage = document.querySelector('#modal-form-wrapper').querySelector('#form-message');

const sendRequest = async (url, formData) => {
    const response = await fetch(url, {
        method: 'POST',
        body: formData
    });

    return await response.json();
}

const onSubmitAddActivity = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    addActivityFormElementsArray.forEach(el => formData.set(el.name, el.value));
    const responseBody = await sendRequest(addActivityForm.action, formData);
    const {statusCode, message, fields} = responseBody;
    addActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
    if (statusCode === 201) {
        window.location.reload();
    } else {
        addActivityFormMessage.setAttribute('class', 'form-error');
        addActivityFormMessage.innerText = message;
        fields.forEach(field => {
            addActivityForm.querySelector('#' + field).setAttribute('class', 'input-error');
        });
    }
}

addActivityForm.addEventListener('submit', onSubmitAddActivity);

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

editButtons.forEach(editButton => editButton.addEventListener('click', () => {
    const {formFields, dataRow} = retrieveData(editButton);
    setFormValues(formFields, dataRow);
    const activityID = editButton.getAttribute('data-id');

    const onSubmit = async (e) => {
        e.preventDefault();

        const formData = new FormData();
        editActivityFormElementsArray.forEach(el => formData.set(el.name, el.value));
        const url = modalForm.action + '?id=' + activityID;
        const responseBody = await sendRequest(url, formData);
        const {statusCode, message, fields} = responseBody;
        editActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
        if (statusCode === 201) {
            window.location.reload();
        } else {
            editActivityFormMessage.setAttribute('class', 'form-error');
            editActivityFormMessage.innerText = message;
            fields.forEach(field => {
                modalForm.querySelector('#' + field).setAttribute('class', 'input-error');
            });
        }
    }

    modalForm.addEventListener('submit', onSubmit);
    editModal.style.display = constants.displayFlex;
}));

const clearModalForm = () => {
    editActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
    editActivityFormMessage.innerText = '';
}

modalCloseButton.addEventListener('click', () => clearModalForm());

editModal.addEventListener('click', (e) => {
    if (e.target !== editModal) return;
    clearModalForm();
});

addEventListener('keyup', (e) => {
    if (e.key === constants.escapeKey) {
        if (editActivityFormMessage.innerText !== '') {
            clearModalForm();
        }
    }
});
