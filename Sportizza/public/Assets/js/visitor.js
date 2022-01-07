const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const email = document.getElementById('email');
const subject = document.getElementById('subject');
const message = document.getElementById('message');
const formVisitorContact = document.getElementById('formVisitorContact');

//Nav
function uncheck() {
    document.getElementById("check").checked=false;
}

//Visitor Slideshow function
let slideIndex = 0;
showSlides();

function showSlides() {
    let slides = document.getElementsByClassName("visitor-slides");
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if(slideIndex > slides.length - 1)
    {
        slideIndex = 0;
    }

    slides[slideIndex].style.display = "block";
    setTimeout(showSlides,3000);
}
// Sign Up Pop up
//popup sign out message
function open_popup_signup_message() {
    var form = document.getElementById("popup_signup");

    form.style.display = "block";
}
function close_popup_signup_message() {
    var form = document.getElementById("popup_signup");

    form.style.display = "none";
}

//Faq Tab Open

function openFaqTab(evt, tabName) {
    var i, booking_tab_content, booking_tab;

    faq_content = document.getElementsByClassName("faq-content");

    for (i = 0; i < faq_content.length; i++) {
        faq_content[i].style.display = "none";
    }

    faq_tab = document.getElementsByClassName("faq-btn");
    for (i = 0; i < faq_tab.length; i++) {
        faq_tab[i].className = faq_tab[i].className.replace(" active", "");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

//Faq Accordian

let acc = document.getElementsByClassName("faq-accordian");
let i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var faq_accordian_content = this.nextElementSibling;
            if (faq_accordian_content.style.maxHeight) {
                faq_accordian_content.style.maxHeight = null;
            } else {
                faq_accordian_content.style.maxHeight = faq_accordian_content.scrollHeight + "px";
            }
        });
    }

// Contact form Validation
// Input fields

// Customer details

// Form

formVisitorContact.addEventListener('submit', function (event) {
    // Prevent default behaviour
    event.preventDefault();
    if (
        validateFirstName() &&
        validateLastName() &&
        validateEmail() &&
        validateSubject() &&
        validateMessage()
    ) {
        formVisitorContact.submit()
    }
});

function validateVisitorContactForm() {
    validateFirstName();
    validateLastName();
    validateEmail();
    validateSubject();
    validateMessage();
}

$(document).ready(function () {
    loadData("");

    function loadData(search) {
        let locationValue = $("#location option:selected").text().replace('-', '_');
        let categoryValue = $("#category option:selected").text().replace('-', '_');
        let searchValue = search.trim().replace(' ', '_');

        if(locationValue == "select location"){
            locationValue = "0";
        }

        if(categoryValue == "select category"){
            categoryValue = "0";
        }

        let argument = `${searchValue}__${categoryValue}__${locationValue}`;

        $.ajax({
            type: "POST",

            url: "http://localhost/Home/searcharenasajax/"+argument,
            dataType: "html",
            
            success: function (response) {
                console.log(response);
                $(".search-results").html(response);
            }
        
        })
    }
    
    $("#name").keyup(function () {
        let search = $(this).val();

        if (search !== "") {
            // $(".result-details").hide();
            loadData(search);
        }
    });

    $('#location').change(function () {
        loadData("");
    });

    $('#category').change(function () {
        loadData("");
    });
})