export const editModal = document.querySelector('#modal-background');
export const modalCloseButton = editModal.querySelector('.close-button');

export const constants = {
    displayNone: 'none',
    displayFlex: 'flex',
    selectTag: 'SELECT',
    escapeKey: 'Escape',
};

export const openMondal = () => {
    editModal.style.display = constants.displayFlex;
}

export const closeModal = () => {
    editModal.style.display = constants.displayNone;
}

export const isModalOpen = () => {
    return editModal.style.display === constants.displayFlex;
}
