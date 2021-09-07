const green = '#2ecc71';
const red = '#e74c3c';

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

const file1 =document.getElementById('file1');
const file2 =document.getElementById('file2');
const file3 =document.getElementById('file3');
const file4 =document.getElementById('file4');
const file5 =document.getElementById('file5');

//Manager Details
const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const mobile = document.getElementById('mobile');
const username = document.getElementById('username');
const password = document.getElementById('password');

// Form
const form = document.getElementById('formSpArenaApplication');


// Handle form
form.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        validateSpArenaName() &&
        validateContact() &&
            validateCategory() &&
            // validateOtherCategory() &&
            validateLocation() &&
            // valiadteOtherLocation() &&
        validateMapLink() &&
        validateDescription() &&
        validateOtherFacilities() &&
            validatePayment() &&

        validateFirstName() &&
        validateLastName() &&
        validateMobile() &&
        validateUsername() &&
        validatePassword()

    ) {
        form.submit();
    }
});
//Capitalize first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function validateForm() {
    validateSpArenaName();
    validateContact();
    validateCategory();
    // valiadteOtherCategory();
    validateLocation();
     // valiadteOtherLocation();
    validateMapLink();
    validateDescription();
    validateOtherFacilities();

    validatePayment();
    validateFirstName();
    validateLastName();
    validateMobile();
    validateUsername();
    validatePassword();
}


// Validation

//General Validation
function setInvalid(field, message) {
    field.style.borderColor = red;
    field.nextElementSibling.innerHTML = (field, message);
}

function setValid(field) {
    field.style.borderColor = green;
    field.nextElementSibling.innerHTML = '';
}

function isEmpty(value) {
    if (value === '') return true;
    return false;
}

function checkIfEmpty(field) {
    if (isEmpty(field.value.trim())) {
        // set field invalid
        setInvalid(field, `${capitalizeFirstLetter(field.name)} should be filled!`);
        return true;
    } else {
        // set field valid
        setValid(field);
        return false;
    }
}

function checkIfOnlyLetters(field) {
    if (/^[a-zA-Z ]+$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} should have only letters!`);
        return false;
    }
}

function checkIfOnlyNumbers(field) {
    if (/^[0-9]+$/.test(field.value)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} should have only numbers!`);
        return false;
    }
}
function checkCharacters(field){
    if ((/^[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/.test(field.value))){
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} should not have any special characters!`);
        return false;
    }
}

function meetLength(field, minLength, maxLength) {
    if (field.value.length >= minLength && field.value.length <= maxLength) {
        setValid(field);
        return true;
    } else if (field.value.length < minLength) {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} 
            must be at least ${minLength} characters long`
        );
        return false;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} 
            must be shorter than ${maxLength} characters`
        );
        return false;
    }
}

function matchWithRegEx(regEx, field) {
    if (field.value.match(regEx)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} entered in invalid!`);
        return false;
    }
}

function matchWithRegExPassword(regEx, field) {
    if (field.value.match(regEx)) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, ` ${capitalizeFirstLetter(field.name)} 
        must consists of atleast 1 capital letter, 
        1 simple letter, 1 character & 1 number!`);
        return false;
    }
}

function selectValidate(field) {
    var selectedCategory = field.options[field.selectedIndex].value;
    if (selectedCategory == "0"){
        selectInvalid(field, `Please select a ${capitalizeFirstLetter(field.name)}`);
        return false;
    }
    else{
        selectValid(field);
        return true;
    }
}
function selectOtherValidate(field) {
    var otherCategory = field.options[field.selectedIndex].value;
    if (otherCategory == "1"){

        selectInvalid(field, `Please select a ${capitalizeFirstLetter(field.name)}`);
        return false;
    }
    else{
        selectValid(field);
        return true;
    }
}

function validateImgFiles(){
    if (fileExists(file1)) return;
    return true;
}

function fileExists(field){
    if (field.files.length == 0 ) {
        setInvalid (field, `Please upload ${capitalizeFirstLetter(field.name)}!`);
        return false;
    }
    else{
        setValid(field);
        return true;
    }
}

function selectValid(field){
    field.style.borderColor = green;
    field.nextElementSibling.innerHTML = '';
}
function selectInvalid(field, message){
    field.style.borderColor = red;
    field.nextElementSibling.innerHTML = (field, message);
}

//Sports Arena Validation
function validateSpArenaName() {
    if (checkIfEmpty(spArenaName)) return;
    return true;
}
function validateContact() {
    if (checkIfEmpty(contact)) return;
    if (!checkIfOnlyNumbers(contact)) return;
    if (!meetLength(contact, 10, 10)) return;
    return true;
}
function validateCategory() {
    if (selectValidate(category)) return;
    return true;
}
// function validateOtherCategory() {
//     if (selectOtherValidate(category))
//     // other_category)) 
//     return;
//     return true;
// }



function validateLocation() {
    if (selectValidate(spLocation)) return;
    return true;
}

function validateMapLink() {
    if (checkIfEmpty(map_link)) return;
    regEx = /^https?\:\/\/(www\.|maps\.)?google(\.[a-z]+){1,2}\/maps\/?\?([^&]+&)*(ll=-?[0-9]{1,2}\.[0-9]+,-?[0-9]{1,2}\.[0-9]+|q=[^&]+)+($|&)/;
    if (matchWithRegEx(regEx, map_link)) return;
    return true;
}

function validateDescription() {
    if (checkIfEmpty(description)) return;
    if (!meetLength(description, 1, 1000)) return;
    return true;
}

function validateOtherFacilities() {
    if (checkIfEmpty(other_facilities)) return;
    return true;
}

function validatePayment() {
    if (selectValidate(payment)) return;
    return true;
}


//Manager details validation
function validateFirstName() {
    if (checkIfEmpty(firstName)) return;
    if (!checkIfOnlyLetters(firstName)) return;
    return true;
}

function validateLastName() {
    if (checkIfEmpty(lastName)) return;
    if (!checkIfOnlyLetters(lastName)) return;
    return true;
}

function validateMobile() {
    if (checkIfEmpty(mobile)) return;
    if (!meetLength(mobile, 10, 10)) return;
    if (!checkIfOnlyNumbers(mobile)) return;
    return true;
}

function validateUsername() {
    if (checkIfEmpty(username)) return;
    if (!meetLength(username, 10, 15)) return;
    if (checkCharacters(username)) return;
    return true;
}


function validatePassword(){
    if (checkIfEmpty(password)) return;
    if (!meetLength(password, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (matchWithRegExPassword(regEx, password)) return;
    return true;
}




