const editButtons = document.querySelectorAll('.edit-button');
const modalForm = editModal.querySelector('#modal-form');

const setLocalStorage = (key, value) => {
    localStorage.setItem(key, JSON.stringify(value));
};

const prepareCurrentFormData = (formFields) => {
    const currentFormData = Object.entries({
        name: '',
        startHour: '',
        endHour: '',
        weekday: '',
        room: '',
    });

    currentFormData.forEach(
        (_, idx, array) => array[idx][1] = formFields[idx].value
    );

    return Object.fromEntries(currentFormData);
};

const modifyUrl = (queryString) => {
    const {name, value} = queryString;
    if (value) {
        const url = new URL(location.href);
        const params = url.searchParams;
        params.append(name, value);
        history.pushState({}, '', url);
    }
};

const configForm = (editButton) => {
    const urlSearchParams = new URLSearchParams(location.search);
    const page = urlSearchParams.get('page') ?? '1';
    const ID = editButton.getAttribute(constants.dataAttribute)
    !urlSearchParams.get('modal') && modifyUrl({name: 'modal', value: ID})

    editModal.style.display = constants.displayFlex;
    modalForm.action = constants.actionFilePath + ID + '&page=' + page;
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

addEventListener('DOMContentLoaded', () => {
    const modalID = new URLSearchParams(location.search).get('modal');
    if (modalID) {
        const editButton = Array.from(editButtons).find(
            value => value.getAttribute(constants.dataAttribute) === modalID
        );

        editButton && editButton.click();
    }
});

editButtons.forEach(editButton => editButton.addEventListener('click', () => {
    configForm(editButton);
    const {formFields, dataRow} = retrieveData(editButton);
    setFormValues(formFields, dataRow);

    const currentFormData = prepareCurrentFormData(formFields);

    setLocalStorage('currentFormValues', currentFormData);
}));
