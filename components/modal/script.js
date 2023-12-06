const editButtons = document.querySelectorAll('.edit-button');
const editModal = document.querySelector('#modal-background');
const modalForm = editModal.querySelector('#modal-form');
const modalCloseButton = editModal.querySelector('.close-button');
modalCloseButton.addEventListener('click', () => {
    editModal.style.display = 'none';
})

editModal.addEventListener('click', (e) => {
    if (e.target !== editModal) {
        return;
    }

    editModal.style.display = 'none';
})

editButtons.forEach((editButton) => editButton.addEventListener('click', () => {
    editModal.style.display = 'flex';
    modalForm.action = 'edit-activity.php?id=' + editButton.getAttribute('data-id');

    const actionCell = editButton.parentElement;
    const row = Array.from(actionCell.parentElement.children);
    const actionCellIndex = row.indexOf(actionCell);
    const dataRow = row.slice(0, actionCellIndex)
    const allFormElements = Array.from(modalForm.elements);
    let formFields = allFormElements.slice(0, allFormElements.length - 1);
    formFields = [formFields[0], formFields[3], formFields[1], formFields[2], formFields[4]];

    formFields.forEach((element, idx) => {
        const data = dataRow[idx];

        if (element.tagName === 'SELECT') {
            const selectOptions = Array.from(element.children);
            const option = selectOptions.find((element) => element.innerText === data.innerText);
            element.value = option.value;
        } else {
            element.value = data.innerText;
        }
    })
}));
