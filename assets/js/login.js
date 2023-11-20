const form = document.querySelector('form');
const emailInput = document.querySelector('#email');
const submitButton = document.querySelector('button[type="submit"]');
const error = document.querySelector('.error');

emailInput.addEventListener('focus', () => error && (error.style.display = 'none'));
submitButton.addEventListener('click', () => {
    const filteredEntries = Object.entries(form.elements).filter(([idx, val]) => val.tagName === 'INPUT')
    filteredEntries.forEach(([idx, element]) => {
        if (!element.value) {
            element.style.borderColor = '#FF0000';
        }
    });
});