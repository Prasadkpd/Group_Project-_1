


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
function open_popup_share(id,bdate,stime,etime,saname,category,gmap) {
  var form = document.getElementById("popup_share");
  // document.getElementById("copyDetails").innerHTML += '{% set temp = x %}';
  document.querySelector("#bdetail1").value = id;
  document.querySelector("#bdetail2").value = bdate;
  document.querySelector("#bdetail3").value = stime;
  document.querySelector("#bdetail4").value = etime;
  document.querySelector("#bdetail5").value = saname;
  document.querySelector("#bdetail6").value = category;
  document.querySelector("#bdetail7").value = gmap;

  form.style.display = "block";
  console.log(id);
  console.log(bdate);
  console.log(stime);
  console.log(etime);
  console.log(saname);
  console.log(category);
  console.log(gmap);
}
function close_popup_share() {
  var form = document.getElementById("popup_share");

  form.style.display = "none";
}
function copyToClipboard() {
  /* Get the text fields */
  var copyText1 = document.getElementById("bdetail1");
  var copyText2 = document.getElementById("bdetail2");
  var copyText3 = document.getElementById("bdetail3");
  var copyText4 = document.getElementById("bdetail4");
  var copyText5 = document.getElementById("bdetail5");
  var copyText6 = document.getElementById("bdetail6");
  var copyText7 = document.getElementById("bdetail7");

  /* Select the text fields */
  copyText1.select();
  copyText1.setSelectionRange(0, 99999); /* For mobile devices */
  
  copyText2.select();
  copyText2.setSelectionRange(0, 99999); 

  copyText3.select();
  copyText3.setSelectionRange(0, 99999); 

  copyText4.select();
  copyText4.setSelectionRange(0, 99999); 

  copyText5.select();
  copyText5.setSelectionRange(0, 99999); 

  copyText6.select();
  copyText6.setSelectionRange(0, 99999); 

  copyText7.select();
  copyText7.setSelectionRange(0, 99999); 

  /* Copy the text inside the text field */
  navigator.clipboard.writeText("Booking ID: " + copyText1.value + "\nBooking Date: " + copyText2.value + "\nTime Duration: " + copyText3.value + "-" + copyText4.value
      + "\nSports Arena Name: " + copyText5.value + "\nSport played: " + copyText6.value + "\nGoogle map link: " + copyText7.value);
//  navigator.clipboard.writeText(copyText2.value);

  /* Alert the copied text */
  alert("Press OK to copy the booking details...");
}


//popup cancel message
function open_popup_cancel_message(bookingId) {
  
  var form = document.getElementById("popup_cancel");
  document.getElementById("cancelForm").action += bookingId;
  form.style.display = "block";
}
function close_popup_cancel_message() {
  var form = document.getElementById("popup_cancel");

  form.style.display = "none";
}


//popup cannot cancel message
function open_popup_cannot_cancel_message() {
  
  var form = document.getElementById("popup_cannot_cancel");
  form.style.display = "block";
}
function close_popup_cannot_cancel_message() {
  var form = document.getElementById("popup_cannot_cancel");

  form.style.display = "none";
}




//popup delete message
function open_popup_delete_message(bookingId) {
  var form = document.getElementById("popup_delete");
  document.getElementById("deleteForm").action += bookingId;
  form.style.display = "block";
}
function close_popup_delete_message() {
  var form = document.getElementById("popup_delete");

  form.style.display = "none";
}


//popup rate message
function open_popup_rate_message(booking_id,sa_name,arena_id) {
  var form = document.getElementById("popup_rate");

  form.style.display = "block";

  document.getElementById("arena_name").value=sa_name;
  document.getElementById("booking_id").value=booking_id;
  document.getElementById("arena_id").value=arena_id;

}
function close_popup_rate_message() {
  var form = document.getElementById("popup_rate");

  form.style.display = "none";
}

//popup delete message for favorite list
function open_popup_delete_message_favorite_list(arena_id,fav_list_id) {

  document.getElementById("form_delete_arena").action+=arena_id
  document.getElementById("fav_list_id_input").value=fav_list_id
  var form = document.getElementById("popup_delete_favorite_list");

  form.style.display = "block";
}
function close_popup_delete_message_favorite_list() {
  var form = document.getElementById("popup_delete_favorite_list");

  form.style.display = "none";
}



//popup notification section
function open_popup_notification(subject,description) {
  var form = document.getElementById("popup_notification");
  form.querySelector("#popup_notification").innerHTML="<h1>"+subject+"</h1>"+"<p>"+description+"</p>";
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
  table = document.getElementById("notifications_table");
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
            table = document.getElementById("mybooking-table");
            tr = table.getElementsByTagName("tr");
            var pass = document.getElementById("date-picker").value;
            var date = new Date(pass);
            var year = String(date.getFullYear());
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var todayDate = String(date.getDate()).padStart(2, '0');
            var datePattern = year + '-' + month + '-' + todayDate;
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
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
 function NotificationDatepicker() {
            table = document.getElementById("notifications_table");
            tr = table.getElementsByTagName("tr");
            var pass = document.getElementById("notification-date-picker").value;
            var date = new Date(pass);
            var year = String(date.getFullYear());
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var todayDate = String(date.getDate()).padStart(2, '0');
            var datePattern = year + '-' + month + '-' + todayDate;
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


