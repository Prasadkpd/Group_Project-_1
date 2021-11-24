// // return current date
window.onload = function () {
  
  // const date = new Date().toISOString().split('T')[0];
  // date.setDate(date.getDate() + 1);
  
  var today = new Date().toISOString().split('T')[0];
  document.getElementsByName("dateInput")[0].setAttribute('min', today);

  document.getElementById('dateInput').valueAsDate = new Date();
  document.querySelectorAll('.bookingDatehidden').valueAsDate = new Date();

}

















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




var booking_date_form = document.getElementById('bookingdateform');
// var date_Input = document.getElementById('dateInput');

// booking_date_form.addEventListener('submit', function (event) {
//   var x = dateI
// }  



// // return current date
// function getDate () {
// var now = new Date();

// var day = ("0" + now.getDate()).slice(-2);
// var month = ("0" + (now.getMonth() + 1)).slice(-2);

// var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
// };



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
function open_popup_cancel_message() {
  var form = document.getElementById("popup_cancel");

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
function open_popup_notification() {
  var form = document.getElementById("popup_notification");

  form.style.display = "block";
}
function close_popup_notification() {
  var form = document.getElementById("popup_notification");

  form.style.display = "none";
}

let thumbnails = document.getElementsByClassName("thumbnail");
let slider = document.getElementById("slider");

let buttonRight = document.getElementById("slide-right");
let buttonLeft = document.getElementById("slide-left");

buttonLeft.addEventListener("click", function () {
  slider.scrollLeft -= 280;
});

buttonRight.addEventListener("click", function () {
  slider.scrollLeft += 280;
});

const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
// alert(maxScrollLeft);
// alert("Left Scroll:" + slider.scrollLeft);

//AUTO PLAY THE SLIDER
function autoPlay() {
  if (slider.scrollLeft > maxScrollLeft - 1) {
    slider.scrollLeft -= maxScrollLeft;
  } else {
    slider.scrollLeft += 1;
  }
}
let play = setInterval(autoPlay, 50);

// PAUSE THE SLIDE ON HOVER
for (var i = 0; i < thumbnails.length; i++) {
  thumbnails[i].addEventListener("mouseover", function () {
    clearInterval(play);
  });

  thumbnails[i].addEventListener("mouseout", function () {
    return (play = setInterval(autoPlay, 50));
  });
}

// js for edit profile picture
const imgDiv = document.querySelector(".editProPic");
const img = document.querySelector("#form-profile-picture");
const file = document.querySelector("#file");
const uploadBtn = document.querySelector("#uploadBtn");

//if user hover image div
imgDiv.addEventListener("mouseenter", function () {
  uploadBtn.style.display = "block";
});

//if user out from img div
imgDiv.addEventListener("mouseleave", function () {
  uploadBtn.style.display = "none";
});

//work form image showing function
file.addEventListener("change", function () {
  //this refers to file upload
  const choosedFile = this.files[0];
  if (choosedFile) {
    const reader = new FileReader();
    //file reader function
    reader.addEventListener("load", function () {
      img.setAttribute("src", reader.result);
    });

    reader.readAsDataURL(choosedFile);
  }
});

//Assign date to the hidden input
const bottom_date=document.getElementById("bookingDateB");
const top_date=document.getElementById("dateInput");

if(top_date){
  alert("Hello");
}

top_date.addEventListener("change",function(){
  let a=top_date.value;
  bottom_date.value=a;
  alert("Hello");
  console.log(a);
});


//js for hide and show arena
// let more_details = document.querySelector(".more-details");
// let view_button = document.querySelector(".more-link");
// let booking_content = document.querySelector(".bookings");
// temp = "1";
// view_button.onclick = function () {
//   if (temp == "1") {
//     temp = "0";
//     view_button.textContent = "Go To Bookings";
//     more_details.classList.add("active");
//     booking_content.classList.add("hide");
//   } else {
//     temp = "1";
//     view_button.textContent = "More Details";
//     more_details.classList.remove("active");
//     booking_content.classList.remove("hide");
//   }
// };

// slide_close.onclick = function () {
//     slide_open.classList.add("active");
//     slide_close.style.display = "none";
//    booking_content.classList.add("hide");
// }

// slide_close.onclick = function () {
//     slide_open.classList.add("active");
//     slide_close.style.display = "none";
//     booking_content.classList.remove("hide");
// }

//popup notification section
function open_moredetails() {
  var moredetails = document.getElementById("more-details");
  var booking = document.getElementById("booking");
  var gotobookingbtn = document.getElementById("goto_booking_btn");
  var moredetailsbtn = document.getElementById("more_details_btn");
  
  booking.style.display="none";
  moredetails.style.display= "block";
  gotobookingbtn.style.display="block";
  moredetailsbtn.style.display="none";
  
}
function open_goto_booking() {
 var moredetails = document.getElementById("more-details");
  var booking = document.getElementById("booking");
  var gotobookingbtn = document.getElementById("goto_booking_btn");
  var moredetailsbtn = document.getElementById("more_details_btn");
  
  booking.style.display="flex";
  moredetails.style.display= "none";
  gotobookingbtn.style.display="none";
  moredetailsbtn.style.display="block";  
}

// function hideItem(id){
//   // document.getElementById("#"+id).hide();
//   var x = document.getElementById("#"+id);
//   x.style.display = "none";
//   console.log(id);
// }

// function hidefunction(x)
// {
//     // $(".")
//     $("#"+x).hide();
//     console.log(x);
// }

// Payment method == 'both'

function paymentclick(){
  var payment_method = event.target.value;
  // var payment_method = document.getElementsByClassName("checkbox").value;
  // alert(payment_method);
  if (payment_method=="card"){
    event.target.value = "cash";
  }else{
    event.target.value = "card";
  }
  console.log(event.target.value);
}






