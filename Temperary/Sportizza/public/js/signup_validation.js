const green = '#26de81';
const red = '#e74c3c';

//Show password button
togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = showPassword.getAttribute('type') === 'password' ? 'text' : 'password';
    showPassword.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});

//Change colour after submitting image files
function change_color(files, e) {
    if (files.length > 0) {
        console.log(e.currentTarget);
        e.currentTarget.parentNode.style.backgroundColor = "#fab1a0";
    }
}

//Capitalize first letter
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//Validations
//General Validation

//Valid & Invalid property display dynamic functions
function setInvalid(field, message) {
    field.style.borderColor = red;
    field.nextElementSibling.innerHTML = (field, message);
}

function setValid(field) {
    field.style.borderColor = green;
    field.nextElementSibling.innerHTML = '';
}

function selectValid(field) {
    field.style.borderColor = green;
    field.nextElementSibling.innerHTML = '';
}

function selectInvalid(field, message) {
    field.style.borderColor = red;
    field.nextElementSibling.innerHTML = (field, message);
}

function setInvalidNext(field, message) {
    field.style.borderColor = red;
    field.nextElementSibling.nextElementSibling.innerHTML = (field, message);
}

function setValidNext(field) {
    field.style.borderColor = green;
    field.nextElementSibling.nextElementSibling.innerHTML = '';
}

//Checking the submission of null vales
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

function checkIfEmptyNext(field) {
    if (isEmpty(field.value.trim())) {
        // set field invalid
        setInvalidNext(field, `${capitalizeFirstLetter(field.name)} should be filled!`);
        return true;
    } else {
        // set field valid
        setValidNext(field);
        return false;
    }
}

//Check for leters, numbers & characters
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

function checkCharacters(field) {
    if ((/^[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/.test(field.value))) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} should not have any special characters!`);
        return false;
    }
}

//Length validations
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

function meetLengthNext(field, minLength, maxLength) {
    if (field.value.length >= minLength && field.value.length <= maxLength) {
        setValidNext(field);
        return true;
    } else if (field.value.length < minLength) {
        setInvalidNext(field, `${capitalizeFirstLetter(field.name)} 
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

//Checking with Regular Expressions
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
        setValidNext(field);
        return true;
    } else {
        setInvalidNext(field, ` ${capitalizeFirstLetter(field.name)} 
        must consists of atleast 1 capital letter, 
        1 simple letter, 1 character & 1 number!`);
        return false;
    }
}

function selectValidate(field) {
    var selectedCategory = field.options[field.selectedIndex].value;
    if (selectedCategory == "0") {
        selectInvalid(field, `Please select a ${capitalizeFirstLetter(field.name)}`);
        return false;
    }
    else {
        selectValid(field);
        return true;
    }
}

function selectOtherCategoryValidate(field) {
    if (category.options[category.selectedIndex].value == "1") {
        if (checkIfEmpty(other_category)) {
            selectInvalid(field, `Please state the ${capitalizeFirstLetter(field.name)}`);
            return false;
        }
        else {
            selectValid(field);
            return true;
        }
    }
}

function selectOtherLocationValidate(field) {
    if (spLocation.options[spLocation.selectedIndex].value == "1") {
        if (checkIfEmpty(other_location)) {
            selectInvalid(field, `Please state the ${capitalizeFirstLetter(field.name)}`);
            return false;
        }
        else {
            selectValid(field);
            return true;
        }
    }
}

function validateImgFiles() {
    // if (fileExists(file1)) return;
    // return true;
}

// function fileExists(field) {
//     if (field.files.length == 0) {
//         setInvalid(field, `Please upload ${capitalizeFirstLetter(field.name)}!`);
//         return false;
//     }
//     else {
//         setValid(field);
//         return true;
//     }
// }



//Sports Arena Validations
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

function validateOtherCategory() {
    if (selectOtherCategoryValidate(other_category)) return;
    return true;
}

function validateLocation() {
    if (selectValidate(spLocation)) return;
    return true;
}

function validateOtherLocation() {
    if (selectOtherLocationValidate(other_location)) return;
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

function validatePassword() {
    if (checkIfEmptyNext(password)) return;
    if (!meetLengthNext(password, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (matchWithRegExPassword(regEx, password)) return;
    return true;
}




