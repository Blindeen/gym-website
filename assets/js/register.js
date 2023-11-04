const passwordInput = document.querySelector('#password');
const confirmPasswordInput = document.querySelector("#confirm-password");
const confirmPasswordError = document.querySelector("#confirm-password-error");

let timeoutID;

confirmPasswordInput.addEventListener("input", () => {
    clearTimeout(timeoutID);
    timeoutID = setTimeout(() => {
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordError.innerText = 'Passwords aren\'t identical';
            confirmPasswordError.style.display = 'block';
        } else {
            confirmPasswordError.innerText = '';
            confirmPasswordError.style.display = 'none';
        }
    }, 300);
});
