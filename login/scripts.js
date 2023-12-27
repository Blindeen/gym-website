const form = document.querySelector('form');
const emailInput = document.querySelector('#email');
const submitButton = document.querySelector('button[type="submit"]');
const error = document.querySelector('.error');

const inputTagName = 'INPUT';
const redColor = '#FF0000';

emailInput.addEventListener('focus', () => error && (error.style.display = 'none'));
submitButton.addEventListener('click', () => {
    const entries = Object.entries(form.elements);
    const filteredEntries = entries.filter(([_, val]) => val.tagName === inputTagName);
    filteredEntries.forEach(([_, element]) => {
        if (!element.value) {
            element.style.borderColor = redColor;
        }
    });
});
