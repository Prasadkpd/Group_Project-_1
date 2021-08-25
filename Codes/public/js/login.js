const login_form = document.getElementById('login-form');
const username = document.getElementById('username');
const password = document.getElementById('password');

const login_btn = document.querySelector("#login-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

login_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
}); 

form.addEventListener("submit",(e) => {
  e.preventDefault();

  checkInputs();
});

function checkInputs() {
  // get the values from the inputs
  const usernameValue =username.value.trim();
  const passwordValue =password.value.trim();

  if (usernameValue === ''){
    // show error
    setErrorFor(username, 'Username cannot be empty');
  } else{
    setSuccessFor(username);

  }
}
function setErrorFor(input, message){
  const input_field = input.parentElement; //Input-field
  const small = input_field.querySelector('small');

  // add error message inside small
  small.innerText = message;

  // add error class
  input_field.className = 'input-field error';
}