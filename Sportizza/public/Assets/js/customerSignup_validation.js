// Input fields

// Customer details
const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const mobile = document.getElementById('mobile');
const username = document.getElementById('username');
const password = document.getElementById('password');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#password');

// Form
const formCustomerSignup = document.getElementById('formCustomerSignup');

formCustomerSignup.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Customer account validations
        validateFirstName() &&
        validateLastName() &&
        validateMobile() &&
        validateUsername() &&
        validatePassword()
    ) {
        formCustomerSignup.submit();
    }
});

function validateCustomerSignupForm() {
    //Customer account validations
    validateFirstName();
    validateLastName();
    validateMobile();
    validateUsername();
    validatePassword();
}