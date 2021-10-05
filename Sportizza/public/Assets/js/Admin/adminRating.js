let side_menu_open_btn = document.querySelector("#side-menu-open-btn");
let sidebar = document.querySelector(".sidebar");
let side_menu_close_btn = document.querySelector("#side-menu-close-btn");
// let homecontent = document.querySelector(".home-content");

side_menu_open_btn.onclick = function () {
  sidebar.classList.add("active");
};
side_menu_close_btn.onclick = function () {
  sidebar.classList.remove("active");
};

//popup delete message
function open_popup_delete_message() {
  var form = document.getElementById("popup_delete");
  form.style.display = "block";
}
function close_popup_delete_message() {
  var form = document.getElementById("popup_delete");
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

// popup form section

function openpopupform() {
  var form = document.getElementById("myForm");

  form.style.display = "block";
}

function closepopupform() {
  var form = document.getElementById("myForm");

  form.style.display = "none";
}

function Datepicker() {
  table = document.getElementById("rating-table");
  tr = table.getElementsByTagName("tr");
  var pass = document.getElementById("date-picker").value;
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

function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-rating");
  filter = input.value.toUpperCase();
  table = document.getElementById("rating-table");
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