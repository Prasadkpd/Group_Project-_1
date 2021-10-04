      //popup notification section
      function open_popup_notification(subject,description){
        var form=document.getElementById("popup_notification");
        form.querySelector("#popup_notification").innerHTML="<h1>"+subject+"</h1>"+"<p>"+description+"</p>";
        form.style.display = "block";
      }
      function close_popup_notification(){
        var form=document.getElementById("popup_notification");
          
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

// popup section

 function openpopupform() {
    var form = document.getElementById("myForm");
    form.style.display = "block";
}
function closepopupform() {
    var form = document.getElementById("myForm");
    form.style.display = "none";
}
