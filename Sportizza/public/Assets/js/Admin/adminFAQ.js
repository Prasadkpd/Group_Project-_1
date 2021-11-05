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

//popup delete message
function open_popup_delete_message(id) {
    var form = document.getElementById("popup_delete");
    var anchor = document.getElementById("deleteFAQbutton");
    anchor.href = "http://localhost/admin/deletefaq/" + id;
    console.log(anchor.href);
    form.style.display = "block";
}
function close_popup_delete_message() {
    var form = document.getElementById("popup_delete");
    form.style.display = "none";
}

function searchTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-faq");
  filter = input.value.toUpperCase();
  table = document.getElementById("faq-table");
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
function searchdeleteFaqTable() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-faq-delete");
  filter = input.value.toUpperCase();
  table = document.getElementById("faq-delete-table");
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

//Function to check all the validations before getting submitted
const createFAQForm = document.getElementById('createFAQForm');

createFAQForm.addEventListener('submit', function (event) {
  // Prevent default behaviour
  event.preventDefault();

  if (
      //FAQ validations
      validateType() &&
      validateQuestion() &&
      validateSolution()
  ) {
      createFAQForm.submit();
  }
});

function validateCreateFAQForm() {
  validateType();
  validateQuestion();
  validateSolution();
}

const updateFAQForm = document.getElementById('updateFAQForm');

updateFAQForm.addEventListener('submit', function (event) {
  event.preventDefault();

  if (
      validateUType() &&
      validateUQuestion() &&
      validateUSolution()
  ) {
      updateFAQForm.submit();
  }
});


function validateUpdateFAQForm() {
  validateUType();
  validateUQuestion();
  validateUSolution();
}

function validateType() {
  if (!selectValidate(type)) return;
  return true;
}

function validateQuestion(){
  if (checkIfEmpty(question)) return;
  if (!checkCharacters(question)) return;
  return true;
}

function validateSolution(){
  if (checkIfEmpty(solution)) return;
  if (!checkCharacters(solution)) return;
  return true;
}

function validateUType() {
  if (!selectValidate(utype)) return;
  return true;
}


function validateUQuestion(){
  // Retreiving FAQ ID and appending it to update FAQ form action
  var id = document.getElementById("uquestion").value;
  link = "http://localhost/admin/updatefaq/"+id;
  document.getElementById("updateFAQForm").action = link
  console.log(document.getElementById("updateFAQForm").action);

  if (!selectValidate(uquestion)) return;
  return true;
}

function validateUSolution(){
  if (checkIfEmpty(usolution)) return;
  if (!checkCharacters(usolution)) return;
  return true;
}