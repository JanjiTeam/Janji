const registrationFormButton = document.querySelector('#registration_form button[type="submit"]');

registrationFormButton.addEventListener('click', () => {
    const firstPassword = document.getElementById('registration_form_plainPassword_first');
    const secondPassword = document.getElementById('registration_form_plainPassword_second');

    if (firstPassword.value !== secondPassword.value) {
        secondPassword.setCustomValidity('Les champs de mot de passe doivent correspondre.');
        secondPassword.reportValidity();
    } else {
        secondPassword.setCustomValidity('');
        secondPassword.reportValidity();
    }
}, false);
