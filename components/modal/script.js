const editModal = document.querySelector('#modal-background');
const modalCloseButton = editModal.querySelector('.close-button');

const constants = {
    displayNone: 'none',
    displayFlex: 'flex',
    dataAttribute: 'data-id',
    actionFilePath: 'edit-activity.php?id=',
    selectTag: 'SELECT',
    escapeKey: 'Escape',
};

modalCloseButton.addEventListener('click', () => {
    editModal.style.display = constants.displayNone;
});

editModal.addEventListener('click', (e) => {
    if (e.target !== editModal) return;
    editModal.style.display = constants.displayNone;
});

addEventListener('keyup', (e) => {
    if (editModal.style.display !== constants.displayNone) {
        if (e.key === constants.escapeKey) {
            editModal.style.display = constants.displayNone;
        }
    }
});
