function notification(){
  window.location="./customer-notification.html";
}









var el = document.querySelector('.more');
var btn = el.querySelector('.more-btn');
var menu = el.querySelector('.more-menu');
var visible = false;

function showMenu(e) {
  e.preventDefault();
  if (!visible) {
      visible = true;
      el.classList.add('show-more-menu');
      menu.setAttribute('aria-hidden', false);
      document.addEventListener('mousedown', hideMenu, false);
  }
 
}

function hideMenu(e) {
  if (btn.contains(e.target)) {
      return;
  }
  if (visible) {
      visible = false;
      el.classList.remove('show-more-menu');
      menu.setAttribute('aria-hidden', true);
      document.removeEventListener('mousedown', hideMenu);
  }
}

btn.addEventListener('click', showMenu, false);




// js for my bookings side dropdown menu

/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}





