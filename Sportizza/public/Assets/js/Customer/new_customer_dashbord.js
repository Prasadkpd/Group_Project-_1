
// let side_menu_open_btn = document.querySelector("#side-menu-open-btn");
// let sidebar = document.querySelector(".sidebar");
// let side_menu_close_btn = document.querySelector("#side-menu-close-btn");
// // let homecontent = document.querySelector(".home-content");


// side_menu_open_btn.onclick = function () {    
//     sidebar.classList.add("active");
// }
// side_menu_close_btn.onclick = function () {
//     sidebar.classList.remove("active");
// }



//notification
// function showNotifycation() {
//     document.querySelector(".pop-up").classList.toggle("show");
//     document.querySelector(".notification-container").classList.toggle("hide");
// }

function openTab(evt, cityName) {
  var i, booking_tab_content, booking_tab;
  booking_tab_content = document.getElementsByClassName("booking_tab_content");
  for (i = 0; i < booking_tab_content.length; i++) {
    booking_tab_content[i].style.display = "none";
  }
  booking_tab = document.getElementsByClassName("booking_tab");
  for (i = 0; i < booking_tab.length; i++) {
    booking_tab[i].className = booking_tab[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}


//popup share section
function open_popup_share() {
  var form = document.getElementById("popup_share");

  form.style.display = "block";
}
function close_popup_share() {
  var form = document.getElementById("popup_share");

  form.style.display = "none";
}


//popup cancel message
function open_popup_cancel_message(x) {
  var form = document.getElementById("popup_cancel");
  document.getElementById("deleteForm").action = 'Customer/deletebooking/' + x;;
  form.style.display = "block";
}
function close_popup_cancel_message() {
  var form = document.getElementById("popup_cancel");

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


//popup rate message
function open_popup_rate_message() {
  var form = document.getElementById("popup_rate");

  form.style.display = "block";
}
function close_popup_rate_message() {
  var form = document.getElementById("popup_rate");

  form.style.display = "none";
}

//popup delete message for favorite list
function open_popup_delete_message_favorite_list() {
  var form = document.getElementById("popup_delete_favorite_list");

  form.style.display = "block";
}
function close_popup_delete_message_favorite_list() {
  var form = document.getElementById("popup_delete_favorite_list");

  form.style.display = "none";
}



//popup notification section
function open_popup_notification(description) {
  var form = document.getElementById("popup_notification");
  form.querySelector("#popup_notification").innerHTML=description;
  form.style.display = "block";
}
function close_popup_notification() {
  var form = document.getElementById("popup_notification");

  form.style.display = "none";
}


// set onclick button as a view booking button in the page loading process
window.onload = function () {
  document.getElementById("view_booking_button").click();
};

//Function to search sports arena in my bookings page
function SearchArenaBooking() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-arena-booking");
  filter = input.value.toUpperCase();
  table = document.getElementById("mybooking-table");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[4];
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

//Function to search sports arena in my favourite list
function SearchArenaFavorite() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-arena-favourite");
  filter = input.value.toUpperCase();
  table = document.getElementById("my_favorite_list");
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

//Function to search notifications
function SearchNotifications() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search-notifications");
  filter = input.value.toUpperCase();
  table = document.getElementById("notifications_list");
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







 function Datepicker() {
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            var pass = document.getElementById("myInput").value;
            var date = new Date(pass);
            var year = String(date.getFullYear());
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var todayDate = String(date.getDate()).padStart(2, '0');
            alert("Hello");
            var datePattern = year + '-' + month + '-' + todayDate;
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[2];
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


