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

function openTab(evt, tabName) {
  var i, booking_tab_content, booking_tab;

  booking_tab_content = document.getElementsByClassName("booking_tab_content");

  for (i = 0; i < booking_tab_content.length; i++) {
    booking_tab_content[i].style.display = "none";
  }

  booking_tab = document.getElementsByClassName("booking_tab");
  for (i = 0; i < booking_tab.length; i++) {
    booking_tab[i].className = booking_tab[i].className.replace(" active", "");
  }

  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

function sportsArenaPopup() {
  document.getElementsByClassName("form-container").style.display = "block";
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
//popup sign out message
function open_popup_signout_message() {
  var form = document.getElementById("popup_signout");

  form.style.display = "block";
}
function close_popup_signout_message() {
  var form = document.getElementById("popup_signout");

  form.style.display = "none";
}

//popup remove customers message
function open_popup_rmve_customers_message(id) {
  var form = document.getElementById("popup_rmve_customers");

  var anchor = document.getElementById("deletecustomerbutton");
  anchor.href = "http://localhost/admin/deletecustomers/" + id;
  console.log(anchor.href);

  form.style.display = "block";
}
function close_popup_rmve_customers_message() {
  var form = document.getElementById("popup_rmve_customers");

  form.style.display = "none";
}

//popup add sports arenas message
function open_popup_view_arenas_message(name,location,category,payment,otherfac,maplink,desc) {
  var form = document.getElementById("popup_view_arenas");

  // form.querySelector("#displayarenadetails").innerHTML = "<h1>"+name+"</h1>"+"<p>"+desc+"</p>"+"<p>"+location+"</p>"+"<p>"+category+"</p>"+"<p>"+payment+"</p>"+"<p>"+otherfac+"</p>"+"<p>"+maplink+"</p>";
  form.querySelector("#displayarenadetails").innerHTML = "<h1>"+name+"</h1>";
  form.querySelector("#displayarenadetails").innerHTML += "<h3>"+desc+"</h3>"+"<span>Location: </span>"+"<p>"+location+"</p>"+"</p>"+"<span>Category: </span>"+"<p>"+category+"</p>"+"<span>Other facilities: </span>"+"<p>"+otherfac+"</p>"+"<span>Payment method: </span>";
  
  if (payment=="cash"){
    form.querySelector("#displayarenadetails").innerHTML += "<p>Cash</p>";
  }else if (payment=="card"){
    form.querySelector("#displayarenadetails").innerHTML += "<p>Card</p>";
  }else{
    form.querySelector("#displayarenadetails").innerHTML += "<p>Card and cash</p>";
  }

  form.querySelector("#displayarenadetails").innerHTML += "<span>Google Map Link: </span>"+"<p>"+maplink+"</p>";

  console.log(maplink);

  form.style.display = "block";
}
function close_popup_view_arenas_message() {
  var form = document.getElementById("popup_view_arenas");

  form.style.display = "none";
}

//popup add sports arenas message
function open_popup_add_arenas_message(id) {
  var form = document.getElementById("popup_add_arenas");

  var anchor = document.getElementById("addarenasbutton");
  anchor.href = "http://localhost/admin/addarenas/" + id;
  console.log(anchor.href);

  form.style.display = "block";
}
function close_popup_add_arenas_message() {
  var form = document.getElementById("popup_add_arenas");

  form.style.display = "none";
}

//popup remove sports arenas message
function open_popup_view_complaints_message() {
  var form = document.getElementById("popup_view_complaints");

  form.style.display = "block";
}
function close_popup_view_complaints_message() {
  var form = document.getElementById("popup_view_complaints");

  form.style.display = "none";
}

//popup remove sports arenas message
function open_popup_rmv_arenas_message(id) {
  var form = document.getElementById("popup_rmve_arenas");

  var anchor = document.getElementById("deletearenasbutton");
  anchor.href = "http://localhost/admin/deletearenas/" + id;
  console.log(anchor.href);

  form.style.display = "block";
}
function close_popup_rmv_arenas_message() {
  var form = document.getElementById("popup_rmve_arenas");

  form.style.display = "none";
}

// //popup add sports arenas message
// function open_popup_blck_arenas_message() {
//   var form = document.getElementById("popup_blck_arenas");

//   form.style.display = "block";
// }
// function close_popup_blck_arenas_message() {
//   var form = document.getElementById("popup_blck_arenas");

//   form.style.display = "none";
// }

//popup blacklist sports arenas message
function open_popup_blck_arenas_message(id) {
  var form = document.getElementById("popup_blck_arenas");

  var anchor = document.getElementById("blacklistarenasbutton");
  anchor.href = "http://localhost/admin/blacklistarenas/" + id;
  console.log(anchor.href);

  form.style.display = "block";
}
function close_popup_blck_arenas_message() {
  var form = document.getElementById("popup_blck_arenas");

  form.style.display = "none";
}

function searchCustomersTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-customers");
  filter = input.value.toUpperCase();
  table = document.getElementById("customer-table");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3];
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

function searchInactiveArenasTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-inactive-arenas");
  filter = input.value.toUpperCase();
  table = document.getElementById("inactive-arenas-table");
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

function searchActiveArenasTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-active-arenas");
  filter = input.value.toUpperCase();
  table = document.getElementById("active-arenas-table");
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