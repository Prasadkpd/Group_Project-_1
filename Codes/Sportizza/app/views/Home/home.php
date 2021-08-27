<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Sportizza</title>
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <link rel="stylesheet" href="./css/visitor.css" />
  <link rel="manifest" href="/manifest.json" />

</head>

<body>
  <main>
    <header>
      <!-- Navigation Bar -->
      <nav>
        <div class="nav-container">
          <div class="logo">
            <a href="visitor.html"><img src="images/logos/logo-white-final.svg"></a>
          </div>
          <!-- <img src="././Assets/Images/Error-Images/error_404.svg"> -->

          <div class="links">
            <ul>
              <li><a href="#">HOME</a></li>
              <li><a href="#sp-arena">SPORTS ARENAS</a></li>
              <li><a href="#reviews">REVIEWS</a></li>
              <li><a href="#faq">FAQ</a></li>
              <li><a href="#about">ABOUT</a></li>
              <li><a href="#contact">CONTACT</a></li>
              <!-- Direct to login page if not logged in -->
              <li><a href="#" class="active">PROFILE</a></li>
            </ul>
          </div>
          <div class="hamburger-menu">
            <div class="bar"></div>
          </div>
        </div>
      </nav>
      <!-- End of navigation bar -->

      <!-- Home Page -->
      <div class="header-content">
        <div class="container grid-2">
          <div class="column-1">
            <h1 class="header-title">Book a Sports Arena</h1>
            <p class="text">
              Book a Sports Arena without any hassle
              with the number 1 Sports arena Booking App in Srilanka.
            </p>
            <a href="#sp-arena" class="book-now-btn">Book Now</a>
          </div>

          <div class="column-2 slideshow">
            <div class="slideshow-container">

              <div class="home-slides fade">
                <img src="images/visitor/slides/image1.svg" style="width:100%">
              </div>

              <div class="home-slides fade">
                <img src="images/visitor/slides/image2.svg" style="width:100%">
              </div>

              <div class="home-slides fade">
                <img src="images/visitor/slides/image3.svg" style="width:100%">
              </div>

              <div class="home-slides fade">
                <img src="images/visitor/slides/image4.svg" style="width:100%">
              </div>

              <div class="home-slides fade">
                <img src="images/visitor/slides/image5.svg" style="width:100%">
              </div>

              <div class="home-slides fade">
                <img src="images/visitor/slides/image6.svg" style="width:100%">
              </div>
              <div class="home-slides fade">
                <img src="images/visitor/slides/image7.svg" style="width:100%">
              </div>

            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- End of Home Page -->


    <!-- Sports Arena Section -->
    <section class="sports-arena section" id="sp-arena">

      <div class="container2">
        <div class="section-header">
          <h3 class="title" data-title="">Sports Arenas</h3>
        </div>

        <div class="sports-arena-slideshow">
          <div class="sports-arena-slideshow-container">

            <div class="sports-arena-slides fade">
              <img src="images/visitor/search/basketball.jpg" style="width:100%">
            </div>

            <div class="sports-arena-slides fade">
              <img src="images/visitor/search/cricket.jpg" style="width:100%">
            </div>

            <div class="sports-arena-slides fade">
              <img src="images/visitor/search/football.jpg" style="width:100%">
            </div>

            <div class="sports-arena-slides fade">
              <img src="images/visitor/search/swimming.jpg" style="width:100%">
            </div>

            <div class="sports-arena-slides fade">
              <img src="images/visitor/search/volleyball.jpg" style="width:100%">
            </div>

          </div>
        </div>
        <div class=sp-arena-box>
          <div class="sports-arena-grid-2">

            <div class="column-1">
              <div class="filter">
                <button onclick="showLocationList()" class="dropbtn">Location
                  <i class="fas fa-caret-down"></i></button>
                <div id="locationDropdown" class="dropdown-content">
                  <a href="#home">Nugegoda</a>
                  <a href="#about">Kelaniya</a>
                  <a href="#contact">Dehiwala</a>
                </div>
              </div>

              <div class="filter">
                <button onclick="showCategoryList()" class="dropbtn1">Category
                  <i class="fas fa-caret-down"></i></button>
                <div id="categoryDropdown" class="dropdown-content1">
                  <a href="#home">Cricket</a>
                  <a href="#about">Chess</a>
                  <a href="#contact">Basketball</a>
                </div>
              </div>

            </div>

            <div class="column-2">
              <div class="search-wrap">
                <div class="search-box">
                  <input type="text" class="input" placeholder="Search by sports arena name">
                  <a href="#search-results2" class="btn search-btn" ONCLICK="ShowSearchResults()">Search</a>
                  <!-- <button ONCLICK="ShowAndHide()" class="btn search-btn" href="#search-results-id">Search</button> -->

                  <!-- <button class="btn search-btn" ONCLICK="ShowSearchResults()">Search</button> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



      <!-- Search-results -->
      <!-- <div class="container" id="search-results-id">
        <div class="search-results">

          <h3>Search Results:
            <div class="chip" style="margin-left: 2rem;">
              Nugegoda
              <span class="closebtn" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <div class="chip" style="margin-left: 2rem;">
              Cricket Grounds
              <span class="closebtn" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
          </h3>

          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>
              <a href="#" class="btn check-availability-btn">Check Availability</a>
              
            </div>
            
          </div>

          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn">Check Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn">Check Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn">Check Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn">Check Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn">Check Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn">Check Availability</a>
            </div>
          </div>
        </div>
      </div> -->
    </section>

    <section class="search-results2 section" id="search-results2" Style="display:none">
      <div class="container">
        <div class="search-results">

          <h3>Search Results:
            <div class="chip" style="margin-left: 2rem;">
              Nugegoda
              <span class="closebtn" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <div class="chip" style="margin-left: .5rem;">
              Cricket Grounds
              <span class="closebtn" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
          </h3>

          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>
              <a href="#" class="btn check-availability-btn"> Availability</a>

            </div>

          </div>

          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn"> Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn"> Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn"> Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn"> Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn"> Availability</a>
            </div>
          </div>
          <div class="result-details">
            <h4><a href="">Mercantile Cricket Grounds</a></h4>
            <div class="sp-arena-summary-items">
              <h5>City:<h6> Colombo 07</h6>
              </h5>

              <h5>Category: <h6> Cricket Ground</h6>
              </h5>

              <a href="#" class="btn check-availability-btn"> Availability</a>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- End of Sports Arena Section -->

    <!-- Reviews Section -->
    <section class="reviews section" id="reviews">
      <div class="container">
        <div class="section-header">
          <h3 class="title" data-title="">Customer Reviews</h3>
        </div>
        <div class="review-content grid-2">
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>
        <button ONCLICK="ShowAndHide()" class="btn">Read More</button>

      </div>
    </section>
    <!-- End of Reviews section -->

    <!-- More reviews section -->
    <section class="reviews-more section" id="reviews-more" Style="display:none">
      <div class="container">
        <div class="review-content grid-2">
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="column-1 reviews">
            <div class="swiper-wrapper">
              <div class="swiper-slide review">

                <div class="review-info">
                  <img src="images/Team members/bhashitha.png" alt="">
                  <h3 class="review-name">Kavindu Wijesinghe</h3>
                  <h5 class="review-username">kavindu123</h5>
                </div>

                <p class="review-text">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                  Laudantium adipisci veniam debitis quas, sapiente
                  repellendus mollitia. Laboriosam labore voluptate quos?
                </p>

                <div class="rate">
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>
                  <i class="fas fa-star"></i>

                  <div class="sp-arena-name">
                    - Mercantile Basketball Courts, Colombo 07
                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- End of more reviews section -->

    <!-- FAQ section -->
    <section class="faq section" id="faq">

      <div class="faq-container">
        <div class="section-header">
          <h3 class="title" data-title="Frequently Asked Questions">FAQs</h3>
        </div>

        <div class="tabs">
          <div class="tab-header">
            <button class="tablinks" onclick="changeFaq(event, 'Customers')" id="defaultButton">FAQ by Customers</button>
            <button class="tablinks" onclick="changeFaq(event, 'SportsArena')">FAQ by Sports Arena</button>
          </div>

          <div class="tab-body">
            <div class="tabcontent" id="Customers">

              <section class="accordion">

                <section class="accordion-item" id="question3">
                  <a class="accordion-link" href="#question3">
                    How to Create my favourite sports arena list?
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-minus"></i>

                  </a>
                  <section class="answer">
                    <p>
                      ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat.</p>
                  </section>
                </section>


              </section>
              <section class="accordion">

                <section class="accordion-item" id="question4">
                  <a class="accordion-link" href="#question4">
                    How to Create my favourite sports arena list?
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-minus"></i>

                  </a>
                  <section class="answer">
                    <p>
                      ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat.</p>
                  </section>
                </section>
                <section class="accordion-item" id="question5">
                  <a class="accordion-link" href="#question5">
                    How to Create my favourite sports arena list?
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-minus"></i>

                  </a>
                  <section class="answer">
                    <p>
                      ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat.</p>
                  </section>
                </section>
                <section class="accordion-item" id="question6">
                  <a class="accordion-link" href="#question6">
                    How to Create my favourite sports arena list?
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-minus"></i>

                  </a>
                  <section class="answer">
                    <p>
                      ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat.</p>
                  </section>
                </section>

              </section>


            </div>
            <div class="tabcontent" id="SportsArena">
              <section class="accordion">

                <section class="accordion-item" id="question2b">
                  <a class="accordion-link" href="#question2b">
                    How to make a booking for a sports arena?
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-minus"></i>

                  </a>
                  <section class="answer">
                    <p>
                      ipsum dolor sit amet, consectetur adipisicing elit,
                      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                      Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                      nisi ut aliquip ex ea commodo consequat.</p>
                  </section>
                </section>



              </section>

            </div>
          </div>



        </div>

    </section>

    <!-- <section class="faq section" id="faq">
      <div class="container">
          <div class="section-header">
              <h3 class="title" data-title="Frequently Asked Questions">FAQs</h3>
          </div>

          <div class="tabs">
              <div class="tab-header">
                  <div class="active">
                      FAQ by Customers
                  </div>
                  <div>
                      FAQ by Sports Arena
                  </div>
              </div>

              <div class="tab-body">
                  <div class="active">

                      <section class="accordion">
                          <section class="accordion-item" id="question1">
                              <a class="accordion-link" href ="#question1">
                                  How to Create a Customer account in Sportizza?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer" ><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>

                          <section class="accordion-item" id="question2">
                              <a class="accordion-link" href ="#question2">
                                  How to make a booking for a sports arena?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer"><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>

                          <section class="accordion-item" id="question3">
                              <a class="accordion-link" href ="#question3">
                                  How to Create my favourite sports arena list?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer"><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>
                          <section class="accordion-item" id="question4">
                              <a class="accordion-link" href ="#question4">
                                  How to cancel my bookings?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer"><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>

                      </section>


                  </div>
                  <div>
                      <section class="accordion">
                          

                          <section class="accordion-item" id="question2b">
                              <a class="accordion-link" href ="#question2b">
                                  How to make a booking for a sports arena?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer"><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>

                          <section class="accordion-item" id="question3b">
                              <a class="accordion-link" href ="#question3b">
                                  How to Create my favourite sports arena list?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer"><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>
                          <section class="accordion-item" id="question4b">
                              <a class="accordion-link" href ="#question4b">
                                  How to cancel my bookings?
                                  <i class="fa fa-plus"></i>
                                  <i class="fa fa-minus"></i>

                              </a>
                              <section class="answer"><p>
                                  ipsum dolor sit amet, consectetur adipisicing elit,
                                  sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
                                  nisi ut aliquip ex ea commodo consequat.</p>
                              </section>
                          </section>

                      </section>

                  </div>
              </div>



          </div>
          <script src="js/faq.js">
          </script>
  </section> -->
    <!-- End of FAQ section -->

    <!-- About Us section -->
    <section class="about section" id="about">
      <div class="container">
        <div class="section-header">
          <h3 class="title" data-title="Who Are We">About us</h3>
        </div>
        <div class="section-body grid-2">

          <div class="column-1">
            <h3 class="title-sm">Hello,</h3>
            <p class="text">
              <br>
              We are a group of second year Undergraduates from
              University of Colombo School of Computing interested
              in contributing to the greater well being of the nation.

            </p>
            <br><br>
            <a href="#team" class="btn">Read more</a>
          </div>

          <div class="column-2 image">
            <img src="./images/visitor/about-us/about-us.svg" class="z-index" alt="" />
          </div>
        </div>
      </div>
    </section>
    <!-- End of About Us section -->

    <!-- Team section -->
    <section class="team section" id="team">
      <div class="container">
        <div class="section-header">
          <h3 class="title">Our Team</h3>
        </div>

        <div class="grid-2">
          <div class="column-1">
            <div class="member-details">
              <div class="column-3">
                <h4>Maneth Wijetunga</h4>
                <h5> Developer</h5>
                <div class="social-media">
                  <a href="#">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                </div>
              </div>
              <div class="member-image">
                <img src="images/Team members/maneth.png" alt="" />
              </div>
              <p class="text">Maneth is an Undergraduate at UCSC studying in the field
                of Computer Science. His areas of interest include Finance,
                Data Science & Computing .</p>
            </div>
          </div>
          <div class="column-2">
            <div class="member-details">
              <div class="column-3">
                <h4>Jonathan Dass</h4>
                <h5> Developer</h5>
                <div class="social-media">
                  <a href="#">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                </div>
              </div>
              <div class="member-image">
                <img src="images/Team members/jonathan.png" alt="" />
              </div>
              <p class="text">Jonathan is an Undergraduate at UCSC studying in the field of Computer Science.
                His areas of interest include Web development, Network Forensics & Cyber Security.</p>
            </div>
          </div>
        </div>
        <div class="grid-2">
          <div class="column-1">
            <div class="member-details">
              <div class="column-3">
                <h4>Bhashitha Ranasinghe</h4>
                <h5> Developer</h5>
                <div class="social-media">
                  <a href="#">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                </div>
              </div>
              <div class="member-image">
                <img src="images/Team members/bhashitha.png" alt="" />
              </div>
              <p class="text">Bhashitha is an Undergraduate at UCSC studying in the field
                of Computer Science. His areas of interest include Computer Networking, Web developing & Marketing .</p>
            </div>
          </div>
          <div class="column-2">
            <div class="member-details">
              <div class="column-3">
                <h4>Prasad Lakshan</h4>
                <h5> Developer</h5>
                <div class="social-media">
                  <a href="#">
                    <i class="fab fa-facebook-f"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-instagram"></i>
                  </a>
                  <a href="#">
                    <i class="fab fa-linkedin-in"></i>
                  </a>
                </div>
              </div>
              <div class="member-image">
                <img src="images/Team members/jonathan.png" alt="" />
              </div>
              <p class="text">Prasad is an Undergraduate at UCSC studying in the field of Computer Science.
                His areas of interest include Web Development, Management & Data Science.</p>
            </div>
          </div>
        </div>
      </div>

    </section>
    <!-- End of team section -->

    <!-- Contact SECTION-->
    <section class="contact" id="contact">
      <div class="container">
        <div class="contact-box">
          <div class="contact-info">
            <h3 class="title">Get in touch</h3>
            <p class="text">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi
              rerum odio incidunt doslorum officia dolorem eaque aprim?
            </p>
            <div class="information-wrap">
              <div class="information">
                <div class="contact-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <p class="info-text">No 35, Reid Avenue, Colombo 00700, Sri Lanka</p>
              </div>

              <div class="information">
                <div class="contact-icon">
                  <i class="fas fa-paper-plane"></i>
                </div>
                <p class="info-text">contact.sportizza@gmail.com</p>
              </div>

              <div class="information">
                <div class="contact-icon">
                  <i class="fas fa-phone-alt"></i>
                </div>
                <p class="info-text">+94 77 123 4567</p>
              </div>
            </div>
          </div>

          <div class="contact-form">
            <h3 class="title">Contact us</h3>
            <form action="https://formspree.io/f/xpzkyeyk" method="POST">
              <div class="row">
                <div class="form-group">
                  <label for="firstName"> </label>
                  <input type="text" class="contact-input" id="firstName" name="firstName" placeholder="First Name" />
                </div>
                <div class="form-group">
                  <label for="lastName"> </label>
                  <input type="text" class="contact-input" id="lastName" name="lastName" placeholder="Last Name" />
                </div>
              </div>

              <div class="row">
                <div class="form-group">
                  <label for="phone"> </label>
                  <input type="text" class="contact-input" id="phone" name="phone" placeholder="Phone" />
                </div>
                <div class="form-group">
                  <label for="email"> </label>
                  <input type="text" class="contact-input" id="email" name="email" placeholder="Email" />
                </div>

              </div>

              <div class="row">
                <textarea name="message" class="contact-input textarea" placeholder="Message"></textarea>
              </div>
              <button type="submit" class="btn">Send</button>
              <!-- <div class="wrap">
              <button class="js-open btn-open is-active" type="submit">Send</button>
              <div class="modal js-modal">
                <div class="modal-image">
                  <svg viewBox="0 0 32 32" style="fill:#48DB71"><path d="M1 14 L5 10 L13 18 L27 4 L31 8 L13 26 z"></path></svg>
                </div>
                <h1>Nice job!</h1>
                <p>To dismiss click the button below</p>
                <button class="js-close">Dismiss</button>
              </div>
            </div> -->





            </form>
          </div>
        </div>
      </div>
    </section>
    <!-- End of Contact -->

  </main>

  <footer class="footer">
    <div class="container">
      <div class="grid-4">
        <div class="grid-4-col footer-image">
          <img src="images/logos/Sportizza_Logo_Final.png" alt="" />
        </div>
        <div class="grid-4-col footer-about">
          <h3 class="title-sm">Sportizza</h3>
          <p class="text">
            We have the desire of building a healthy community.
          </p>
        </div>

        <div class="grid-4-col footer-links">
          <ul>
            <li>
              <a href="#">HOME</a>
            </li>
            <li>
              <a href="#sp-arena">SPORTS ARENAS</a>
            </li>
            <li>
              <a href="#reviews">REVIEWS</a>
            </li>
          </ul>
        </div>
        <div class="grid-4-col footer-links">
          <ul>
            <li>
              <a href="#faq">FAQ</a>
            </li>
            <li>
              <a href="#about">ABOUT</a>
            </li>
            <li>
              <a href="#contact">CONTACT</a>
            </li>
          </ul>
        </div>

      </div>

      <div class="grid-4-col footer-newstletter">


      </div>

      <div class="bottom-footer">
        <div class="copyright">
          <p class="text">
            Copyright &copy;2021 All rights reserved | Sportizza Inc.
          </p>
        </div>

        <div class="followus-wrap">
          <div class="followus">
            <h3>Follow us</h3>
            <span class="footer-line"></span>
            <div class="social-media">
              <a href="#">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>

          <div class="back-btn-wrap">
            <a href="#" class="back-btn">
              <i class="fas fa-chevron-up"></i>
            </a>
          </div>
        </div>
      </div>

    </div>
  </footer>

  <script src="js/app.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>