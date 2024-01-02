import {sendRequest} from '../utils.js';
import {editModal, modalCloseButton, closeModal, isModalOpen, constants} from '../components/modal/script.js';

import addActivity from './addActivity.js';
import editActivity from './editActivity.js';
import deleteActivity from './deleteActivity.js';

const modalForm = editModal.querySelector('#modal-form');
const editActivityFormElements = modalForm.elements;
const editActivityFormElementsArray = Array.from(editActivityFormElements).slice(0, editActivityFormElements.length - 1);
const editActivityFormMessage = document.querySelector('#modal-form-wrapper').querySelector('#form-message');

const clearModalForm = () => {
    editActivityFormElementsArray.forEach(el => el.removeAttribute('class'));
    editActivityFormMessage.innerText = '';
}

modalCloseButton.addEventListener('click', () => {
    closeModal();
    clearModalForm();
});

editModal.addEventListener('click', (e) => {
    if (e.target !== editModal) return;
    closeModal();
    clearModalForm();
});

addEventListener('keyup', (e) => {
    if (e.key === constants.escapeKey) {
        if (isModalOpen()) {
            closeModal();
            clearModalForm();
        }
    }
});

document.querySelector('#add-activity-form').addEventListener('submit', addActivity);
document.querySelectorAll('.edit-button').forEach(editActivity);
document.querySelectorAll('.remove-button').forEach(deleteActivity);
