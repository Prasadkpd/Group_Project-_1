//popup notification section
function open_popup_notification(subject, description) {
  var form = document.getElementById("popup_notification");
  console.log("efekjfnejfnefj");
  form.querySelector("#popup_notification").innerHTML =
    "<h1>" + subject + "</h1>" + "<p>" + description + "</p>";
  form.style.display = "block";
}
function close_popup_notification() {
  var form = document.getElementById("popup_notification");

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

window.onload = function () {
  var today = new Date().toISOString().split("T")[0];
  document.getElementsByName("date-picker")[0].setAttribute("max", today);
};

function Datepicker() {
  table = document.getElementById("notification");
  tr = table.getElementsByTagName("tr");
  var pass = document.getElementById("date-picker").value;
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

function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-notification");
  filter = input.value.toUpperCase();
  table = document.getElementById("notification");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
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
