
const email = document.getElementById('email');
const address = document.getElementById('address');
const city = document.getElementById('city');
const formCheckout = document.getElementById('formCheckout');
const benificiaryName = document.getElementById('benificiaryName');
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
    

        //popup sign out message
        function open_popup_signout_message(){
          var form=document.getElementById("popup_signout");
          
          form.style.display = "block";
      }
      function close_popup_signout_message(){
        var form=document.getElementById("popup_signout");
        
        form.style.display = "none";
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
    if (checkIfEmpty(benificiaryName)) return;
    if (!checkCharacters(benificiaryName)) return;
    return true;
  }
function validateAccountNumber(){
    if (checkIfEmpty(accountNumber)) return;
    if (!checkIfAccountNumber(accountNumber)) return;
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