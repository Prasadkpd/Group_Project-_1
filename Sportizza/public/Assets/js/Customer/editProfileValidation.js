

const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const primaryContact = document.getElementById('primary_contact');
const username = document.getElementById('username');
const password = document.getElementsByName('password');
// const password = document.getElementById('password');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#password');

const formspArenaApplication = document.getElementById('formSpArenaApplication');

// Validations
// Handle form
formEditUserProfile.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (     
        // //Manager account validations
        validateFirstName() &&
        validateLastName() &&
        // validatePrimaryContact() &&
        validateUsername()
        // validatePassword()
    ) {
        formEditUserProfile.submit();
    }
});

// //Function to check all the validations before getting submitted
function validateEditUserForm() {
    //Manager account validations
    validateFirstName();
    validateLastName();
    // validatePrimaryContact();
    validateUsername();
    // validatePassword();
}



//Manager details validation
function validateFirstName() {
    if (checkIfEmpty(firstName)) return;
    if (!checkIfOnlyLetters(firstName)) return;
    regEx = /^\S+$/;
    if (!matchWithRegExSpace(regEx,firstName)) return;
    return true;
}

function validateLastName() {
    if (checkIfEmpty(lastName)) return;
    if (!checkIfOnlyLetters(lastName)) return;
    if (!matchWithRegExSpace(regEx,lastName)) return;
    return true;
}

function validatePrimaryContact() {
    if (checkIfEmptyNext(primaryContact)) return;
    if (!meetLengthNext(primaryContact, 9, 9)) return;
    if (!checkIfOnlyNumbersNext(primaryContact)) return;
    if (!checkSLNumberNext(primaryContact)) return;
    return true;
}

function validateUsername() {
    if (checkIfEmpty(username)) return;
    if (!meetLength(username, 10, 15)) return;
    if (!checkCharacters(username)) return;
    if (!matchWithRegExSpace(regEx,username)) return;
    return true;
}

function validatePassword() {
    if (checkIfEmptyNext(password)) return;
    if (!meetLengthNext(password, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (matchWithRegExPassword(regEx, password)) return;
    return true;
}




