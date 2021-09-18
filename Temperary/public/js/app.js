const filter_btns = document.querySelectorAll(".filter-btn");
const skills_bars = document.querySelectorAll(".skill-progress");
const records_wrap = document.querySelector(".records");
const records_numbers = document.querySelectorAll(".number");
const hamburger_menu = document.querySelector(".hamburger-menu");
const navbar = document.querySelector("header nav");
const links = document.querySelectorAll(".links a");


window.onload = function() {
  document.getElementById("defaultButton").click();
};

//Hamburger Menu
function closeMenu() {
  navbar.classList.remove("open");
  document.body.classList.remove("stop-scrolling");
}

hamburger_menu.addEventListener("click", () => {
  if (!navbar.classList.contains("open")) {
    navbar.classList.add("open");
    document.body.classList.add("stop-scrolling");
  } else {
    closeMenu();
  }
});

links.forEach((link) => link.addEventListener("click", () => closeMenu()));



//Slideshow function
var slideIndex = 0;
showSlides();
showSlides2();
function showSlides() {
  var i;
  var slides = document.getElementsByClassName("home-slides fade");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none"; 
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1} 
  slides[slideIndex-1].style.display = "block"; 
  setTimeout(showSlides, 3000); // Change image every 3 seconds
}

//Slideshow function2
function showSlides2() {
  var i;
  var slides = document.getElementsByClassName("sports-arena-slides fade");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none"; 
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1} 
  slides[slideIndex-1].style.display = "block"; 
  setTimeout(showSlides2, 3000); // Change image every 3 seconds
}

//Accrodion function
// var acc = document.getElementsByClassName("accordion");
// var i;

// for (i = 0; i < acc.length; i++) {
//   acc[i].addEventListener("click", function() {
//     this.classList.toggle("active");
//     var panel = this.nextElementSibling;
//     if (panel.style.display === "block") {
//       panel.style.display = "none";
//     } else {
//       panel.style.display = "block";
//     }
//   });
// }

//Date picking from Calendar
let calendar = document.querySelector('.calendar')

const month_names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

isLeapYear = (year) => {
    return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 ===0)
}

getFebDays = (year) => {
    return isLeapYear(year) ? 29 : 28
}

generateCalendar = (month, year) => {

    let calendar_days = calendar.querySelector('.calendar-days')
    let calendar_header_year = calendar.querySelector('#year')

    let days_of_month = [31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

    calendar_days.innerHTML = ''

    let currDate = new Date()
    if (!month) month = currDate.getMonth()
    if (!year) year = currDate.getFullYear()

    let curr_month = `${month_names[month]}`
    month_picker.innerHTML = curr_month
    calendar_header_year.innerHTML = year

    // get first day of month
    
    let first_day = new Date(year, month, 1)

    for (let i = 0; i <= days_of_month[month] + first_day.getDay() - 1; i++) {
        let day = document.createElement('div')
        if (i >= first_day.getDay()) {
            day.classList.add('calendar-day-hover')
            day.innerHTML = i - first_day.getDay() + 1
            day.innerHTML += `<span></span>
                            <span></span>
                            <span></span>
                            <span></span>`
            if (i - first_day.getDay() + 1 === currDate.getDate() 
            && year === currDate.getFullYear() && month === currDate.getMonth()) {
                day.classList.add('curr-date')
            }
        }
        calendar_days.appendChild(day)
    }
}

let month_list = calendar.querySelector('.month-list')

month_names.forEach((e, index) => {
    let month = document.createElement('div')
    month.innerHTML = `<div data-month="${index}">${e}</div>`
    month.querySelector('div').onclick = () => {
        month_list.classList.remove('show')
        curr_month.value = index
        generateCalendar(index, curr_year.value)
    }
    month_list.appendChild(month)
})

let month_picker = calendar.querySelector('#month-picker')

month_picker.onclick = () => {
    month_list.classList.add('show')
}

let currDate = new Date()

let curr_month = {value: currDate.getMonth()}
let curr_year = {value: currDate.getFullYear()}

generateCalendar(curr_month.value, curr_year.value)

document.querySelector('#prev-year').onclick = () => {
    --curr_year.value
    generateCalendar(curr_month.value, curr_year.value)
}

document.querySelector('#next-year').onclick = () => {
    ++curr_year.value
    generateCalendar(curr_month.value, curr_year.value)
}


/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function showLocationList() {
  document.getElementById("locationDropdown").classList.toggle("show-location");
}

function showCategoryList() {
  document.getElementById("categoryDropdown").classList.toggle("show-category");
}


// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}


//Show the hidden content
function ShowAndHide() {
  var x = document.getElementById('reviews-more');
  console.log("clicked")
  if (x.style.display == 'none') {
      x.style.display = 'block';
  } else {
      x.style.display = 'none';
  }
}
//Show the hidden content
function ShowSearchResults() {
  var x = document.getElementById('search-results2');
  if (x.style.display == 'none') {
      x.style.display = 'block';
  } else {
      x.style.display = 'none';
  }
}

//Tabs for faq
function changeFaq(event, faqType) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(faqType).style.display = "block";
  event.currentTarget.className += " active";
  console.log(event)
}




//Popup box

// var btnOpen = select('.js-open');
// var btnClose = select('.js-close');
// var modal = select('.js-modal');
// var modalChildren = modal.children;

// function hideModal() {
//   dynamics.animate(modal, {
//     opacity: 0,
//     translateY: 100
//   }, {
//     type: dynamics.spring,
//     frequency: 50,
//     friction: 600,
//     duration: 1500
//   });
// }

// function showModal() {
//   // Define initial properties
//   dynamics.css(modal, {
//     opacity: 0,
//     scale: .5
//   });
  
//   // Animate to final properties
//   dynamics.animate(modal, {
//     opacity: 1,
//     scale: 1
//   }, {
//     type: dynamics.spring,
//     frequency: 300,
//     friction: 400,
//     duration: 1000
//   });
// }

// function showBtn() {
//   dynamics.css(btnOpen, {
//     opacity: 0
//   });
  
//   dynamics.animate(btnOpen, {
//     opacity: 1
//   }, {
//     type: dynamics.spring,
//     frequency: 300,
//     friction: 400,
//     duration: 800
//   });
// }

// function showModalChildren() {
//   // Animate each child individually
//   for(var i=0; i<modalChildren.length; i++) {
//     var item = modalChildren[i];
    
//     // Define initial properties
//     dynamics.css(item, {
//       opacity: 0,
//       translateY: 30
//     });

//     // Animate to final properties
//     dynamics.animate(item, {
//       opacity: 1,
//       translateY: 0
//     }, {
//       type: dynamics.spring,
//       frequency: 300,
//       friction: 400,
//       duration: 1000,
//       delay: 100 + i * 40
//     });
//   } 
// }

// function toggleClasses() {
//   toggleClass(btnOpen, 'is-active');
//   toggleClass(modal, 'is-active');
// }

// // Open nav when clicking sandwich button
// btnOpen.addEventListener('click', function(e) {
//   toggleClasses();
//   showModal();
//   showModalChildren();
// });

// // Open nav when clicking sandwich button
// btnClose.addEventListener('click', function(e) {
//   hideModal();
//   dynamics.setTimeout(toggleClasses, 500);
//   dynamics.setTimeout(showBtn, 500);
// });