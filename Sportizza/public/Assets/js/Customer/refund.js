
const email = document.getElementById('email');
const address = document.getElementById('address');
const city = document.getElementById('city');
const formCheckout = document.getElementById('formCheckout');
const benificialyName = document.getElementById('benificialyName');
const accountNumber = document.getElementById('accountNumber');
const bankName = document.getElementById('bankName');
const branchName = document.getElementById('branchName');


// Validations
// Handle form
formCheckout.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
       
        validateEmail() &&
        validateAddress() &&
        validateCity()&&
        validateBenificaryName() &&
        validateAccountNumber()&&
        validateBankName()&&
        validateBranchName()
    ) {
        formCheckout.submit();
    }
});


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
    

    
    
     
    





  // js for edit profile picture
const imgDiv = document.querySelector(".editProPic");
const img =document.querySelector("#form-profile-picture");
const file=document.querySelector("#file");
const uploadBtn=document.querySelector("#uploadBtn");

//if user hover image div
imgDiv.addEventListener("mouseenter",function(){
  uploadBtn.style.display="block"
})

//if user out from img div
imgDiv.addEventListener("mouseleave",function(){
  uploadBtn.style.display="none"
})

//work form image showing function
file.addEventListener("change",function(){
  //this refers to file upload
  const choosedFile = this.files[0];
  if(choosedFile){
    const reader= new FileReader();
     //file reader function
     reader.addEventListener("load",function(){
       img.setAttribute("src",reader.result);
     });

     reader.readAsDataURL(choosedFile);

  }
})

//hide items from cart
function hideItem(x)
{
    // $(".")
    $("#cartItem"+x).hide();
    console.log(x);
}




// //Function to check all the validations before getting submitted
function validateCheckoutForm() {
   
    validateEmail();
    validateAddress();
    validateCity();
    validateBenificaryName()
    validateAccountNumber()
    validateBankName()
    validateBranchName()
}

function validateEmail(){
  if (checkIfEmpty(email)) return;
  regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if (!matchWithRegExEmail(regEx, email)) return;
  return true;
}
function validateAddress(){
  if (checkIfEmpty(address)) return;
  if (!checkCharacters(address)) return;
  return true;
}
function validateCity(){
  if (checkIfEmpty(city)) return;
  if (!checkCharacters(city)) return;
  return true;
}

function validateBenificaryName(){
    if (checkIfEmpty(benificialyName)) return;
    if (!checkCharacters(benificialyName)) return;
    return true;
  }
function validateAccountNumber(){
    if (checkIfEmpty(accountNumber)) return;
    if (!checkCharacters(accountNumber)) return;
    return true;
  }
function validateBankName(){
    if (checkIfEmpty(bankName)) return;
    if (!checkCharacters(bankName)) return;
    return true;
  }
function validateBranchName(){
    if (checkIfEmpty(branchName)) return;
    if (!checkCharacters(branchName)) return;
    return true;
  }