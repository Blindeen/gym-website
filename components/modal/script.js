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

const removeModalParameter = () => {
    const url = new URL(window.location.href);
    const params = url.searchParams;
    params.delete('modal')
    history.pushState({}, '', url);
};

modalCloseButton.addEventListener('click', () => {
    removeModalParameter();
    editModal.style.display = constants.displayNone;
});

editModal.addEventListener('click', (e) => {
    if (e.target !== editModal) return;
    removeModalParameter();
    editModal.style.display = constants.displayNone;
});

addEventListener('keyup', (e) => {
    if (editModal.style.display !== constants.displayNone) {
        if (e.key === constants.escapeKey) {
            removeModalParameter();
            editModal.style.display = constants.displayNone;
        }
    }
});
