
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
    
    
    
    
        // popup form section
    
        function openpopupform(){
            var form=document.getElementById("myForm");
            
            form.style.display = "block";
    
        }
    
        function closepopupform(){
            var form=document.getElementById("myForm");
            
            form.style.display = "none";
    
        }
        //popup sign out message
        function open_popup_signout_message(){
          var form=document.getElementById("popup_signout");
          
          form.style.display = "block";
      }
      function close_popup_signout_message(){
        var form=document.getElementById("popup_signout");
        
        form.style.display = "none";
    }
    
    
    
    
        //popup share section
        function open_popup_share(){
          var form=document.getElementById("popup_share");
            
          form.style.display = "block";
        }
        function close_popup_share(){
          var form=document.getElementById("popup_share");
            
          form.style.display = "none";
        }
    
        
        //popup cancel message
        function open_popup_cancel_message(){
          var form=document.getElementById("popup_cancel");
          
          form.style.display = "block";
      }
      function close_popup_cancel_message(){
        var form=document.getElementById("popup_cancel");
        
        form.style.display = "none";
    }
    
    
        //popup delete message
        function open_popup_delete_message(){
          var form=document.getElementById("popup_delete");
          
          form.style.display = "block";
      }
      function close_popup_delete_message(){
        var form=document.getElementById("popup_delete");
        
        form.style.display = "none";
    }
    
    
        //popup rate message
        function open_popup_rate_message(){
          var form=document.getElementById("popup_rate");
          
          form.style.display = "block";
      }
      function close_popup_rate_message(){
        var form=document.getElementById("popup_rate");
        
        form.style.display = "none";
    }
    
        //popup delete message for favorite list
        function open_popup_delete_message_favorite_list(){
          var form=document.getElementById("popup_delete_favorite_list");
          
          form.style.display = "block";
      }
      function close_popup_delete_message_favorite_list(){
        var form=document.getElementById("popup_delete_favorite_list");
        
        form.style.display = "none";
    }
    
    
    
          //popup notification section
          function open_popup_notification(){
            var form=document.getElementById("popup_notification");
              
            form.style.display = "block";
          }
          function close_popup_notification(){
            var form=document.getElementById("popup_notification");
              
            form.style.display = "none";
          } 
        
    
    // set onclick button as a view booking button in the page loading process
          window.onload=function(){
            document.getElementById("view_booking_button").click();
        };













        let thumbnails = document.getElementsByClassName('thumbnail');
let slider = document.getElementById('slider');

let buttonRight = document.getElementById('slide-right');
let buttonLeft = document.getElementById('slide-left');

buttonLeft.addEventListener('click', function(){
    slider.scrollLeft -= 280;
})

buttonRight.addEventListener('click', function(){
    slider.scrollLeft += 280;
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
