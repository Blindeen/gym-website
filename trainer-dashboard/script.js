const editButtons = document.querySelectorAll('.edit-button');
const modalForm = editModal.querySelector('#modal-form');

const configForm = (editButton) => {
    const page = new URLSearchParams(window.location.search).get('page') ?? '1';

    editModal.style.display = constants.displayFlex;
    modalForm.action = constants.actionFilePath + editButton.getAttribute(constants.dataAttribute) + '&page=' + page;
};

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

editButtons.forEach((editButton) => editButton.addEventListener('click', () => {
    configForm(editButton);
    const {formFields, dataRow} = retrieveData(editButton);
    setFormValues(formFields, dataRow);
}));
