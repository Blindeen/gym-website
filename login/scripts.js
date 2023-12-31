const formMessage = document.querySelector('#form-message');
const email = document.querySelector('#email');
const password = document.querySelector('#password');

const formElements = [email, password];

const sendRequest = async formData => {
    const response = await fetch('login.php', {
        method: 'POST',
        body: formData
    });

    return await response.json();
}

const onSubmit = async e => {
    e.preventDefault();

    const formData = new FormData();
    formElements.forEach(el => formData.set(el.name, el.value));
    const responseBody = await sendRequest(formData);
    const {statusCode, message} = responseBody;

    formMessage.innerText = message;
    formElements.forEach(el => el.removeAttribute('class'));
    if (statusCode === 200) {
        formMessage.setAttribute('class', 'form-success');
        formElements.forEach(el => el.value = '');
        setTimeout(() => window.location.replace('http://localhost:81/'), 1000);
    } else {
        formMessage.setAttribute('class', 'form-error');
        switch (message) {
            case 'Invalid email':
            case 'User not found': {
                email.setAttribute('class', 'input-error');
            } break;
            case 'Incorrect password':
            case 'Password cannot be empty': {
                password.setAttribute('class', 'input-error');
            }
        }
    }
};

document.querySelector('form').addEventListener('submit', onSubmit);
