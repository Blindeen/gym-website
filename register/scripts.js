const registerForm = document.querySelector('#register-form');
const registerFormElements = registerForm.elements;
const registerFormElementsArray = Array.from(registerFormElements).slice(0, registerFormElements.length - 1);
const formMessage = registerForm.querySelector('#form-message');

const sendRequest = async (url, formData) => {
    const response = await fetch(url, {
        method: 'POST',
        body: formData
    });

    return await response.json();
}

const onSubmit = async (e) => {
    e.preventDefault();

    const formData = new FormData();
    registerFormElementsArray.forEach(el => formData.set(el.name, el.value));
    const responseBody = await sendRequest(registerForm.action, formData);
    const {statusCode, message} = responseBody;

    formMessage.innerText = message;
    registerFormElementsArray.forEach(el => el.removeAttribute('class'));
    if (statusCode >= 200 && statusCode <= 299) {
        formMessage.setAttribute('class', 'form-success');
        registerFormElementsArray.forEach(el => el.value = '');
        setTimeout(() => window.location.replace('http://localhost:81/'), 1000);
    } else {
        const {fields} = responseBody;
        formMessage.setAttribute('class', 'form-error');
        fields.forEach(el => registerForm.querySelector('#' + el).setAttribute('class', 'input-error'));
    }
}

registerForm.addEventListener('submit', onSubmit);
