
const newPassword = document.getElementById('newPassword');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#password');
//Add Form
const formUserPasswordReset = document.getElementById('formUserPasswordReset');

formUserPasswordReset.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        validateNewPassword()
    ) {
        formUserPasswordReset.submit();
    }
});

function validatePasswordResetForm() {
    validateNewPassword();
}

function validateNewPassword() {
    if (checkIfEmptyNext(newPassword)) return;
    if (!meetLengthNext(newPassword, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (!matchWithRegExPassword(regEx, newPassword)) return;
    return true;

}
