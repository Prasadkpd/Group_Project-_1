// Input fields

// Sports Arena details
const spArenaName = document.getElementById('spArenaName');
const contact = document.getElementById('contact');
const category = document.getElementById('category');
const other_category = document.getElementById('other_category');
const spLocation = document.getElementById('location');
const other_location = document.getElementById('other_location');
const map_link = document.getElementById('map-link');
const description = document.getElementById('description');
const other_facilities = document.getElementById('other-facilities');
const payment = document.getElementById('payment');

//Image files
const file1 = document.getElementById('file1');
const file2 = document.getElementById('file2');
const file3 = document.getElementById('file3');
const file4 = document.getElementById('file4');
const file5 = document.getElementById('file5');

//Manager Details
const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const mobile = document.getElementById('mobile');
const username = document.getElementById('username');
const password = document.getElementById('password');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#password');

// Form
const formspArenaApplication = document.getElementById('formSpArenaApplication');

// Validations
// Handle form
formspArenaApplication.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();

    if (
        //Sports Arena validations
        validateSpArenaName() &&
        validateContact() &&
        validateCategory() &&
        validateOtherCategory() &&
        validateLocation() &&
        validateOtherLocation() &&
        validateMapLink() &&
        validateDescription() &&
        validateOtherFacilities()  &&
        validatePayment() &&

        //Manager account validations
        validateFirstName() &&
        validateLastName() &&
        validateMobile() &&
        validateUsername() &&
        validatePassword()
    ) {
        formspArenaApplication.submit();
    }
});

// //Function to check all the validations before getting submitted
function validateForm() {
    //Sports Arena validations
    validateSpArenaName();
    validateContact();
    validateCategory();
    validateOtherCategory();
    validateLocation();
    validateOtherLocation();
    validateMapLink();
    validateDescription();
    validateOtherFacilities();

    //Manager account validations
    validatePayment();
    validateFirstName();
    validateLastName();
    validateMobile();
    validateUsername();
    validatePassword();
}


//Sports Arena Validations
// function validateSpArenaName() {
//     if (checkIfEmpty(spArenaName)) return;
//     return true;
// }

// function validateContact() {
//     if (checkIfEmpty(contact)) return;
//     if (!checkIfOnlyNumbers(contact)) return;
//     if (!meetLength(contact, 10, 10)) return;
//     return true;
// }

// function validateCategory() {
//     if (!selectValidate(category)) return;
//     return true;
// }

// //this
// function validateOtherCategory() {
//     if (!selectOtherCategoryValidate(other_category)) return;
//     return true;
// }

// function validateLocation() {
//     if (!selectValidate(spLocation)) return;
//     return true;
// }

// function validateOtherLocation() {
//     if (!selectOtherLocationValidate(other_location)) return;
//     return true;
// }

// function validateMapLink() {
//     if (checkIfEmpty(map_link)) return;
//     regEx = /^http\:\/\/|https\:\/\/|www\.google$/;
//     if (!matchWithRegEx(regEx, map_link)) return;
//     return true;
// }

// function validateDescription() {
//     if (checkIfEmpty(description)) return;
//     if (!meetLength(description, 1, 1000)) return;
//     return true;
// }

// function validateOtherFacilities() {
//     if (checkIfEmpty(other_facilities)) return;
//     return true;
// }

// function validatePayment() {
//     if (!selectValidate(payment)) return;
//     return true;
// }
// //Manager details validation
// function validateFirstName() {
//     if (checkIfEmpty(firstName)) return;
//     if (!checkIfOnlyLetters(firstName)) return;
//     regEx = /^\S+$/;
//     if (!matchWithRegExSpace(regEx, firstName)) return;
//     return true;
// }

// function validateLastName() {
//     if (checkIfEmpty(lastName)) return;
//     if (!checkIfOnlyLetters(lastName)) return;
//     if (!matchWithRegExSpace(regEx, lastName)) return;
//     return true;
// }

// function validateMobile() {
//     if (checkIfEmpty(mobile)) return;
//     if (!meetLength(mobile, 10, 10)) return;
//     if (!checkIfOnlyNumbers(mobile)) return;
//     if (!checkSLNumber(mobile)) return;
//     return true;
// }

// function validateUsername() {
//     if (checkIfEmpty(username)) return;
//     if (!meetLength(username, 10, 15)) return;
//     if (!checkCharacters(username)) return;
//     return true;
// }

// function validatePassword() {
//     if (checkIfEmptyNext(password)) return;
//     if (!meetLengthNext(password, 8, 255)) return;
//     regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
//     if (!matchWithRegExPassword(regEx, password)) return;
//     return true;
// }