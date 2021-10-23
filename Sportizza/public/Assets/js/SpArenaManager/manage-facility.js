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

  // popup section for update
function openpopupform_for_update(facility_id, facility_name) {
  
  // getFacilityName(facility_name);
  
document.getElementById("editfacilityname").value=facility_name;
document.getElementById("formUpdateFacility").action += facility_id;
    var form = document.getElementById("myForm_for_update");
    form.style.display = "block";
}
function closepopupform_for_update() {
    var form = document.getElementById("myForm_for_update");
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

function open_popup_delete_message(facility_id) {
  document.getElementById("deleteFacilitybtn").href += facility_id;
  var form = document.getElementById("popup_delete");
  console.log(facility_id);
  form.style.display = "block";
}
function close_popup_delete_message() {
    var form = document.getElementById("popup_delete");
    form.style.display = "none";
}


//Add Facility Form
const facilityName = document.getElementById('facilityName');
// const password = document.querySelector('#password');
const password = document.getElementById('password');
const togglePassword = document.querySelector('#togglePassword');
const showPassword = document.querySelector('#password');
//Add Facility Form
const editfacilityname = document.getElementById('editfacilityname');
//Show password button




const formAddFacility = document.getElementById('formAddFacility');

formAddFacility.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Add timeslot validations
        validateFacilityName() &&
        validateUserPassword()
    ) {
        formAddFacility.submit();
    }
});

function validateAddFacilityForm() {
    //Add facility validations
    validateFacilityName();
        validatePassword();
}

function validateFacilityName() {
    if (checkIfEmpty(facilityName)) return;
    if (!meetLength(facilityName, 1, 50)) return;
    return true;
}
function validateUserPassword() {
    if (checkIfEmptyNext(password)) return;
    return true;
}

const formUpdateFacility = document.getElementById('formUpdateFacility');

formUpdateFacility.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        //Add timeslot validations
        validateUpdateFacilityName()
    ) {
        formUpdateFacility.submit();
    }
});

function validateUpdateFacilityForm() {
    //Update facility validations
    validateUpdateFacilityName();     
}
function validateUpdateFacilityName() {
    if (checkIfEmpty(editfacilityname)) return;
    if (!meetLength(editfacilityname, 1, 50)) return;
    // document.getElementById("facilityNameMsg").innerHTML = "";
    // document.querySelector('#btn-add-facility').disabled = false;
    return true;
}

function searchViewTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("view-facility");
  input = document.getElementById("search-view-facility");
  filter = input.value.toUpperCase();
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
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
function searchDeleteTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("delete-facility");
  input = document.getElementById("search-delete-facility");
  filter = input.value.toUpperCase();
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
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
function searchUpdateTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("update-facility");
  input = document.getElementById("search-update-facility");
  filter = input.value.toUpperCase();
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
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





