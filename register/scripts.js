const form = document.querySelector('form');
const submitButton = document.querySelector('button[type="submit"]');

const tagNames = ['INPUT', 'SELECT'];
const redColor = '#FF0000';

submitButton.addEventListener('click', () => {
    const entries = Object.entries(form.elements);
    const filteredEntries = entries.filter(([_, val]) => tagNames.includes(val.tagName));
    filteredEntries.forEach(([_, element]) => {
        if (!element.value) {
            element.style.borderColor = redColor;
        }
    });
});
