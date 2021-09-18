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
        validateOtherFacilities() &&
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

//Function to check all the validations before getting submitted
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