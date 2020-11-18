const registrationForm = document.getElementById('registration_form');
const firstPassword = document.getElementById('registration_form_plainPassword_first');
const secondPassword = document.getElementById('registration_form_plainPassword_second');

registrationForm.addEventListener('submit', (e) => {
    if (firstPassword.value !== secondPassword.value) {
        secondPassword.setCustomValidity('Les champs de mot de passe doivent correspondre.');
        secondPassword.reportValidity();
        e.preventDefault();
    } else {
        secondPassword.setCustomValidity('');
        secondPassword.reportValidity();
    }
}, false);
