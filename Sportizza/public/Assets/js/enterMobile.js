const mobile = document.getElementById("mobile");

const formMobileReset = document.getElementById('mobileForm');

formMobileReset.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        validateMobile()
    ) {
        formMobileReset.submit();
    }
});

function validateMobileResetForm() {
    validateMobile()
}

function validateMobile() {
    if (checkIfEmpty(mobile)) return;
    if (!meetLength(mobile, 10, 10)) return;
    if (!checkIfOnlyNumbers(mobile)) return;
    if (!checkSLNumber(mobile)) return;
    return true;
}