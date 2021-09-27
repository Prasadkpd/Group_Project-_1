let side_menu_open_btn = document.querySelector("#side-menu-open-btn");
let sidebar = document.querySelector(".sidebar");
let side_menu_close_btn = document.querySelector("#side-menu-close-btn");


side_menu_open_btn.onclick = function () {
    sidebar.classList.add("active");
}
side_menu_close_btn.onclick = function () {
    sidebar.classList.remove("active");
}


let thumbnails = document.getElementsByClassName('thumbnail');
let slider = document.getElementById('slider');

let buttonRight = document.getElementById('slide-right');
let buttonLeft = document.getElementById('slide-left');

buttonLeft.addEventListener('click', function(){
    slider.scrollLeft -= 200;
})

buttonRight.addEventListener('click', function(){
    slider.scrollLeft += 200;
})

const maxScrollLeft = slider.scrollWidth - slider.clientWidth;
// alert(maxScrollLeft);
// alert("Left Scroll:" + slider.scrollLeft);

//AUTO PLAY THE SLIDER 
function autoPlay() {
    if (slider.scrollLeft > (maxScrollLeft - 1)) {
        slider.scrollLeft -= maxScrollLeft;
    } else {
        slider.scrollLeft += 1;
    }
}
let play = setInterval(autoPlay, 50);

// PAUSE THE SLIDE ON HOVER
for (var i=0; i < thumbnails.length; i++){

thumbnails[i].addEventListener('mouseover', function() {
    clearInterval(play);
});

thumbnails[i].addEventListener('mouseout', function() {
    return play = setInterval(autoPlay, 50);
});
}

//notification
function showNotifycation() {
    document.querySelector(".pop-up").classList.toggle("show");
    document.querySelector(".notification-container").classList.toggle("hide");
}

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

//popup Edit Profile message
function open_popup_edit_arena_profile() {
    var form = document.getElementById("popup_edit_form");
    form.style.display = "block";
}
function close_popup_edit_arena_profile() {
    var form = document.getElementById("popup_edit_form");
    form.style.display = "none";
}
