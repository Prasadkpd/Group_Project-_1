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

//popup cancel booking message
function open_popup_cancel_booking(booking_id) {
  document.getElementById("BookingReasonForm").action += booking_id;
  var form = document.getElementById("popup_delete");
  form.style.display = "block";
}
function close_popup_cancel_booking() {
  var form = document.getElementById("popup_delete");
  form.style.display = "none";
}

//popup payment message
function open_popup_payment_message(booking_id) {
  document.getElementById("getPaymentbtn").href += booking_id;
  var form = document.getElementById("popup_payment");
  form.style.display = "block";
}
function close_popup_payment_message() {
  var form = document.getElementById("popup_payment");
  form.style.display = "none";
}

function searchViewTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("view-booking");
  input = document.getElementById("search-view-booking");
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


//window.onload =function ViewDatepicker() 
function ViewDatepicker() {
  table = document.getElementById("view-booking");
  var pass = document.getElementById("view-date-picker").value;
  tr = table.getElementsByTagName("tr");
  var date = new Date(pass);
  var year = String(date.getFullYear());
  var month = String(date.getMonth() + 1).padStart(2, "0");
  var todayDate = String(date.getDate()).padStart(2, "0");
  console.log(datePattern);
  var datePattern = year + "-" + month + "-" + todayDate;
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[4];
    if (td) {
      txtValue = td.innerText;
      if (txtValue == datePattern) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchViewTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("view-booking");
  input = document.getElementById("search-view-booking");
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


function searchDeleteTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("delete-booking");
  input = document.getElementById("search-delete-booking");
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

function DeleteDatepicker() {
  table = document.getElementById("delete-booking");
  var pass = document.getElementById("delete-date-picker").value;
  tr = table.getElementsByTagName("tr");
  var date = new Date(pass);
  var year = String(date.getFullYear());
  var month = String(date.getMonth() + 1).padStart(2, "0");
  var todayDate = String(date.getDate()).padStart(2, "0");
  console.log(datePattern);
  var datePattern = year + "-" + month + "-" + todayDate;
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.innerText;
      if (txtValue == datePattern) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchPaymentTable() {
  var input, filter, table, tr, td, i, txtValue;
  table = document.getElementById("payment-booking");
  input = document.getElementById("search-payment-booking");
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

function PaymentDatepicker() {
  table = document.getElementById("payment-booking");
  var pass = document.getElementById("payment-date-picker").value;
  tr = table.getElementsByTagName("tr");
  var date = new Date(pass);
  var year = String(date.getFullYear());
  var month = String(date.getMonth() + 1).padStart(2, "0");
  var todayDate = String(date.getDate()).padStart(2, "0");
  console.log(datePattern);
  var datePattern = year + "-" + month + "-" + todayDate;
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3];
    if (td) {
      txtValue = td.innerText;
      if (txtValue == datePattern) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

const BookingReasonForm = document.getElementById("BookingReasonForm");
const cancelBookingMsg = document.getElementById("cancelReason");

BookingReasonForm.addEventListener("submit", function (event) {
  // Prevent default behaviour
  event.preventDefault();
  if (validateCancelReason()) {
    BookingReasonForm.submit();
  }
});

function validateBookingCancelReason() {
  validateCancelReason();
}

function validateCancelReason() {
  if (checkIfEmpty(cancelBookingMsg)) return;
  return true;
}

function checkIfEmptytextArea(field) {
  console.log(field.value.trim());
  if (isEmpty(field.value.trim())) {
    // set field invalid
    setInvalid(
      field,
      `${capitalizeFirstLetter(
        replaceUnderscore(field.name)
      )} should be filled!`
    );
    return true;
  } else {
    // set field valid
    setValid(field);
    return false;
  }
}

// // return current date
window.onload = function () {
  
  // const date = new Date().toISOString().split('T')[0];
  // date.setDate(date.getDate() + 1);
  
  var today = new Date().toISOString().split('T')[0];
  document.getElementsByName("dateInput")[0].setAttribute('min', today);

  document.getElementById('dateInput').valueAsDate = new Date();
  document.querySelectorAll('.bookingDatehidden').valueAsDate = new Date();

}
