const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const username = document.getElementById('username');

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
        validateUsername()
    ) {
        formEditUserProfile.submit();
    }
});

// //Function to check all the validations before getting submitted
function validateEditUserForm() {
    //Manager account validations
    validateFirstName();
    validateLastName();
    validateUsername();
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

function validateUsername() {
    if (checkIfEmpty(username)) return;
    if (!meetLength(username, 10, 15)) return;
    if (!checkCharacters(username)) return;
    if (!matchWithRegExSpace(regEx,username)) return;
    return true;
}








//Edit Profile picture
const imgDiv = document.querySelector(".editProPic");
const img = document.querySelector("#form-profile-picture");
const file = document.querySelector("#file");
const uploadBtn = document.querySelector("#uploadBtn");

//if user hover image div
imgDiv.addEventListener("mouseenter", function () {
    uploadBtn.style.display = "block"
})

//if user out from img div
imgDiv.addEventListener("mouseleave", function () {
    uploadBtn.style.display = "none"
})

//work form image showing function
file.addEventListener("change", function () {
    //this refers to file upload
    const choosedFile = this.files[0];
    if (choosedFile) {
        const reader = new FileReader();
        //file reader function
        reader.addEventListener("load", function () {
            img.setAttribute("src", reader.result);
        });
        reader.readAsDataURL(choosedFile);

    }
})

//Edit mobile number popup
//popup editPrimaryPhoneNumber section
function open_editPrimaryPhoneNumber() {
    var form = document.getElementById("popup_editPrimaryNumber");
    var profile = document.getElementById("myForm");
    profile.style.display = "none"
    form.style.display = "block";
}
function close_editPrimaryPhoneNumber() {
    var form = document.getElementById("popup_editPrimaryNumber");
    var profile = document.getElementById("myForm");
    profile.style.display = "block"
    form.style.display = "none";
}


//popup edit password section
function open_editPassword() {
    var form = document.getElementById("popup_editPassword");
    var profile = document.getElementById("myForm");
    profile.style.display = "none"
    form.style.display = "block";
}
function close_editPassword() {
    var form = document.getElementById("popup_editPassword");
    var profile = document.getElementById("myForm");
    profile.style.display = "block"
    form.style.display = "none";
}

document.getElementById("ChangePassword").addEventListener("click", function (event) {
    event.preventDefault();
    open_editPassword();
});

document.getElementById("No_EditPassword").addEventListener("click", function (event) {
    event.preventDefault();
    close_editPassword();
});

const oldPassword = document.getElementById('oldPassword');
const togglePassword1 = document.querySelector('#togglePassword1');
const showPassword1 = document.querySelector('#oldPassword');
const newPassword = document.getElementById('newPassword');
const togglePassword2 = document.querySelector('#togglePassword2');
const showPassword2 = document.querySelector('#newPassword');

const formPassword = document.getElementById('formPassword');

//Show password button
togglePassword1.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = showPassword1.getAttribute('type') === 'password' ? 'text' : 'password';
    showPassword1.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});

//Show password button
togglePassword2.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = showPassword2.getAttribute('type') === 'password' ? 'text' : 'password';
    showPassword2.setAttribute('type', type);
    // toggle the eye / eye slash icon
    this.classList.toggle('bi-eye');
});

formPassword.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Customer account validations
        validateOldPassword() &&
        validateNewPassword()
    ) {
        formPassword.submit();
    }
});

function validatePasswordForm() {
    //Customer account validations
    validateOldPassword();
    validateNewPassword();
}

function validateOldPassword() {
    if (checkIfEmptyNext(oldPassword)) return;
    if (!meetLengthNextLogin(oldPassword, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (!matchWithRegExPasswordLogin(regEx, oldPassword)) return;
    return true;
}

function validateNewPassword() {
    if (checkIfEmptyNext(newPassword)) return;
    if (!meetLengthNextLogin(newPassword, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (!matchWithRegExPasswordLogin(regEx, newPassword)) return;
    return true;
}

