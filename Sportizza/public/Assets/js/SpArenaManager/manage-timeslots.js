let side_menu_open_btn = document.querySelector("#side-menu-open-btn");
let sidebar = document.querySelector(".sidebar");
let side_menu_close_btn = document.querySelector("#side-menu-close-btn");


side_menu_open_btn.onclick = function () {
    sidebar.classList.add("active");
}
side_menu_close_btn.onclick = function () {
    sidebar.classList.remove("active");
}

//notification
function showNotifycation() {
    document.querySelector(".pop-up").classList.toggle("show");
    document.querySelector(".notification-container").classList.toggle("hide");
}

function openTab(evt, tabName) {
    var i, booking_tab_content, booking_tab;

    booking_tab_content = document.getElementsByClassName("booking_tab_content");

    for (i = 0; i < booking_tab_content.length; i++) { booking_tab_content[i].style.display = "none"; }
    booking_tab = document.getElementsByClassName("booking_tab"); for (i = 0; i < booking_tab.length; i++) {
        booking_tab[i].className = booking_tab[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block"; evt.currentTarget.className += " active";
}

// popup section

function openpopupform() {
    var form = document.getElementById("myForm");
    form.style.display = "block";
}
function closepopupform() {
    var form = document.getElementById("myForm");
    form.style.display = "none";
}


//popup sign out message
function open_popup_signout_message() {
    var form = document.getElementById("popup_signout");
    form.style.display = "block";
}
function close_popup_signout_message() {
    var form = document.getElementById("popup_signout");
    form.style.display = "none";
}

//popup delete message
function open_popup_delete_message() {
    var form = document.getElementById("popup_delete");
    form.style.display = "block";
}
function close_popup_delete_message() {
    var form = document.getElementById("popup_delete");
    form.style.display = "none";
}
const startTime = document.getElementById('startTime');
const endTime = document.getElementById('endTime');
const slotPrice = document.getElementById('slotPrice');
const facilityName = document.getElementById('facilityName');

const formAddTimeslot = document.getElementById('formAddTimeslot');

formAddTimeslot.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Add timeslot validations
        validateStartTime() &&
        validateEndTime() &&
        validatePrice() &&
        validateFacilitySelect()
    ) {
        formAddTimeslot.submit();
    }
});

function validateAddTimeslotForm() {
    //Add timeslot validations
    validateStartTime();
    validateEndTime();
    validatePrice();
    validateFacilitySelect();
}

function validateStartTime() {
    if (checkIfEmpty(startTime)) return;
    return true;
}
function validateEndTime() {
    if (checkIfEmpty(endTime)) return;
    return true;
}

function validatePrice() {
    if (checkIfEmpty(slotPrice)) return;
    if (!checkIfOnlyNumbers(slotPrice)) return;
    return true;
}

function validateFacilitySelect() {
    if (!selectValidate(facilityName)) return;
    return true;
}

