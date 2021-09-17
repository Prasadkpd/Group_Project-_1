// Input fields

// User details
const username = document.getElementById('username');
const password = document.getElementById('password');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#password');

// Form
const formUserLogin = document.getElementById('formUserLogin');

formUserLogin.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Customer account validations
        validateLoginUsername() &&
        validateLoginPassword()
    ) {
        formUserLogin.submit();
    }
});

function validateLoginForm() {
    //Customer account validations
    validateLoginUsername();
    validateLoginPassword();
}

//General Validations

function checkCharactersLogin(field) {
    if ((/^[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]*$/.test(field.value))) {
        setValid(field);
        return true;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} entered is invalid!`);
        return false;
    }
}

function meetLengthLogin(field, minLength, maxLength) {
    if (field.value.length >= minLength && field.value.length <= maxLength) {
        setValid(field);
        return true;
    } else if (field.value.length < minLength) {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} 
            entered is invalid!`
        );
        return false;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} 
        entered is invalid!`
        );
        return false;
    }
}

function meetLengthNextLogin(field, minLength, maxLength) {
    if (field.value.length >= minLength && field.value.length <= maxLength) {
        setValidNext(field);
        return true;
    } else if (field.value.length < minLength) {
        setInvalidNext(field, `${capitalizeFirstLetter(field.name)} 
        entered is invalid!`
        );
        return false;
    } else {
        setInvalid(field, `${capitalizeFirstLetter(field.name)} 
        entered is invalid!`
        );
        return false;
    }
}

function matchWithRegExPasswordLogin(regEx, field) {
    if (field.value.match(regEx)) {
        setValidNext(field);
        return true;
    } else {
        setInvalidNext(field, ` ${capitalizeFirstLetter(field.name)} 
        entered is invalid!`);
        return false;
    }
}

//Specific validations

function validateLoginUsername() {
    if (checkIfEmpty(username)) return;
    if (!meetLengthLogin(username, 10, 15)) return;
    if (checkCharactersLogin(username)) return;
    return true;
}

function validateLoginPassword() {
    if (checkIfEmptyNext(password)) return;
    // if (!meetLengthNextLogin(password, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (matchWithRegExPasswordLogin(regEx, password)) return;
    return true;
}
