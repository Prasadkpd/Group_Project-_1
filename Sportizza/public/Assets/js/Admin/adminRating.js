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
