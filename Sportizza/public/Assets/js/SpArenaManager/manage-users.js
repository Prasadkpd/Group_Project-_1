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

for (i = 0; i < booking_tab_content.length; i++) { booking_tab_content[i].style.display="none" ; }
    booking_tab=document.getElementsByClassName("booking_tab"); for (i=0; i < booking_tab.length; i++) {
    booking_tab[i].className=booking_tab[i].className.replace(" active", "" ); }
    document.getElementById(tabName).style.display="block" ; evt.currentTarget.className +=" active" ; }

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
function open_popup_delete_message(user_id) {
    document.getElementById("delete_user_form").action += user_id;
    let form = document.getElementById("popup_delete");
    form.style.display = "block";
}
function close_popup_delete_message() {
    var form = document.getElementById("popup_delete");
    form.style.display = "none";
}

//Add Facility Form
const firstName = document.getElementById('addfirstName');
const lastName =document.getElementById('addlastName');
const mobile =document.getElementById('addmobile');
const password = document.getElementById('addpassword');
const username = document.getElementById('addusername');
const staff =document.getElementById('staffType');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#addpassword');
//Add Facility Form
const editfacilityname = document.getElementById('editfacilityname');
//Show password button




const formAddUser = document.getElementById('formAdduser');

formAddUser.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Add timeslot validations
        validateFirstName() &&
        validateLastName() &&
        validateMobile() &&
        validateUsername() &&
        validatePassword() &&
        validateStaffTypeSelect()
    ) {
        formAddUser.submit();
    }
});

function validateAddUserForm() {
    //Add facility validations
    validateFirstName();
    validateLastName();
    validateMobile();
    validateUsername();
    validatePassword();
    validateStaffTypeSelect();
}

function validateFirstName() {
    if (checkIfEmpty(firstName)) return;
    if (!checkIfOnlyLetters(firstName)) return;
    regEx = /^\S+$/;
    if (!matchWithRegExSpace(regEx, firstName)) return;
    return true;
}

function validateLastName() {
    if (checkIfEmpty(lastName)) return;
    if (!checkIfOnlyLetters(lastName)) return;
    if (!matchWithRegExSpace(regEx, lastName)) return;
    return true;
}

function validateMobile() {
    if (checkIfEmpty(mobile)) return;
    if (!meetLength(mobile, 10, 10)) return;
    if (!checkIfOnlyNumbers(mobile)) return;
    if (!checkSLNumber(mobile)) return;
    return true;
}

function validateUsername() {
    if (checkIfEmpty(username)) return;
    if (!meetLength(username, 10, 15)) return;
    if (!checkCharacters(username)) return;
    return true;
}

function validatePassword() {
    if (checkIfEmptyNext(password)) return;
    if (!meetLengthNext(password, 8, 255)) return;
    regEx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    if (!matchWithRegExPassword(regEx, password)) return;
    return true;
}
function validateStaffTypeSelect(){
    if (!selectValidate(staff)) return;
    return true;
}

function searchViewTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("view-staff");
  input = document.getElementById("search-view-staff");
  filter = input.value.toUpperCase();
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
function searchRemoveTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("remove-staff");
  input = document.getElementById("search-remove-staff");
  filter = input.value.toUpperCase();
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}