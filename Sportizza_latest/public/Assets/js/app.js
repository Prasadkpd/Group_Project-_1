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


//Faq Tab Open

function OpenFaqTab(event,faq_tab_name) {
    let i, faq_container, faq_btn;

    faq_container = document.getElementsByClassName("faq-content");
    for (let i = 0; i < faq_container.length; i++) {
        faq_container[i].style.display = "none";
    }
    
    faq_btn = document.getElementsByClassName("faq-btn");
    for (let i = 0; i < faq_btn.length; i++) {
        faq_btn[i].className = faq_btn[i].className.replace(" active", "");
    }

    document.getElementsById(faq_tab_name).style.display="block";
    event.currentTarget.className += " active";

}