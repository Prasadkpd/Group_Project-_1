<?php
    require_once "../koolreport/autoload.php";
    use \koolreport\datasources\PdoDataSource;
    use \koolreport\widgets\koolphp\Table;
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\google\AreaChart;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\widgets\google\DonutChart;

    $connection = array(
        "connectionString"=>"mysql:host=localhost;dbname=alt_sportizza",
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    );
?>

    <html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Analytics</title>
     <!-- CSS File -->
    <link rel="stylesheet" href="admin-analytics.css">
    <!-- FontAwesome Icon -->
    <script src="https://kit.fontawesome.com/3220c9480a.js" crossorigin="anonymous"></script>
</head>
<body>

    <!-- Top Navigation Bar -->
    <nav>
       <div class="logo-container">
            <a href="#">
                <i class="fas fa-running "></i>
                <span class="logo-name">Sportizza</span>
            </a>
       </div>
       <div class="search-bar-container">
            <input type="search" name="search" placeholder="Search">
            <i class="fas fa-search "></i>
       </div>
       <div  class="nav-profile">
            <button   class="openbutton" onclick="openpopupform()">
            <div class="profile-details">
                <img src="assets/profile-picture.jpg" alt="" class="profile-picture">
                <div class="username">Bhashitha Ranasinghe</div>
                
            </div>
            </button>
        </div>
       <div class="sign-in-out-btn">
           <button onclick="open_popup_signout_message()" > Sign Out </button>
       </div> 
    </nav>
    <!-- End Top Navigation Bar -->
    
    <!-- popup form for edit profile -->
    <div id="myForm" class="sectionpop ">


    <div class="formsection">

        <div>
            <button s class="closebutton"onclick="closepopupform()">❌</button>
        </div>
    <img class="form-profile-picture"  src="assets/profile-picture.jpg" alt="">
    <form action="/action_page.php" style="margin:20px">
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="firstname"
            placeholder="Enter First Name" value="sadeepa">

        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lastname"
            placeholder="Enter Last Names" value="bhashitha">


        <label for="lname">Primary Number</label>
        <input type="text" id="lname" name="lastname"
            placeholder="Enter Primary Number" value="0705614640">

        <label for="lname">Secondary Number</label>
        <input type="text" id="userid" name="lastname"
            placeholder="Enter Secondary Number" value="0705614640">



        <input  type="submit" value="Save">
    </form> 
    </div>    
    </div>

    <!-- start pop up sign out section -->
    <div id="popup_signout" class="sectionpop ">
    

    <div class="formsection">

        <div>
            <button s class="closebutton"onclick="close_popup_signout_message()">❌</button>
        </div>
    <form action="/action_page.php" style="margin:20px">
        <div style="margin:auto">
            <h1 style="margin:auto">
                Sign Out
            </h1>
            <p style="margin:auto">
                Do your really want to sign up now?
            </p>
        </div>
    </form> 


    <div style="display:flex">
    
    <button style="background-color:#e74c3c    " class="popup_button">
        YES
    </button>
    <button onclick="close_popup_signout_message()"  class="popup_button">
        NO
        </button>
    </div>
    </div>  
    </div>
    <!-- end pop up sign out section -->

    <section class="page-container">
        
        <!-- SIDE NAVIGATION BAR - START -->
        <div class="sidebar">
                <div class="sidebar-container">
                    <div class="side-menu">
                        <i class="fas fa-bars fa-2x " id="side-menu-open-btn"></i>
                        <i class="fas fa-times fa-2x" id="side-menu-close-btn"></i>
                    </div>
                    <ul class="side-menu-list">
                        <li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-chart-bar"></i>
                                <p class="side-menu-item">Analytics</p>
                            </a>
                            <p class="side-menu-tooltip">Analytics</p>
                        </li>
                        <li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-users"></i>
                                <p class="side-menu-item">Manage Users</p>
                            </a>
                            <p class="side-menu-tooltip">Manage Users</p>
                        </li>
                        <!--<li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-exclamation"></i>
                                <p class="side-menu-item">Complaints</p>
                            </a>
                            <p class="side-menu-tooltip">Complaints</p>
                        </li>-->
                        <li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-question"></i>
                                <p class="side-menu-item">FAQ</p>
                            </a>
                            <p class="side-menu-tooltip">FAQ</p>
                        </li>
                        <!--<li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-file-alt"></i>
                                <p class="side-menu-item">Reports</p>
                            </a>
                            <p class="side-menu-tooltip">Reports</p>
                        </li>-->
                        <li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-star"></i>
                                <p class="side-menu-item">Ratings</p>
                            </a>
                            <p class="side-menu-tooltip">Ratings</p>
                        </li>
                        <!--<li>
                            <a href="#" class="side-menu-li">
                                <i class="fas fa-bell"></i>
                                <p class="side-menu-item">Notifications</p>
                            </a>
                            <p class="side-menu-tooltip">Notifications</p>
                        </li>-->
                    </ul>
                </div>
            </div>
            <!-- SIDE NAVIGATION BAR - END -->
            <div class="page-content-container" >
                <div class="container">
                    
                    <!--<div class="tab">
                        <button class="booking_tab tab-btn active" onclick="openTab(event, 'view_bookings')"><i class="fas fa-search"></i><p>View FAQ</p></button>
                        <button class="booking_tab tab-btn" onclick="openTab(event, 'add_bookings')"><i class="fas fa-plus"></i><p>Create FAQ</p></button>
                        <button class="booking_tab tab-btn" onclick="openTab(event, 'cancel_booking')"><i class="fas fa-times"></i><p> Delete FAQ</p></button>
                        <button class="booking_tab tab-btn" onclick="openTab(event, 'booking_payment')"><i class="fas fa-edit"></i><p>Update FAQ</p></button>
                    </div>-->
                    <h1 class="page-header">Analytics</h1>
                    <div class="chart">
                                <?php 
                                    ColumnChart::create(array(
                                        "dataSource"=>(new PdoDataSource($connection))->query("
                                            SELECT CASE EXTRACT(MONTH FROM user.registered_time)
                                            WHEN '1' THEN 'January'
                                            WHEN '2' THEN 'February'
                                            WHEN '3' THEN 'March'
                                            WHEN '4' THEN 'April'
                                            WHEN '5' THEN 'May'
                                            WHEN '6' THEN 'June'
                                            WHEN '7' THEN 'July'
                                            WHEN '8' THEN 'August'
                                            WHEN '9' THEN 'September'
                                            WHEN '10' THEN 'October'
                                            WHEN '11' THEN 'November'
                                            WHEN '12' THEN 'December'
                                            ELSE 'Not Valid'
                                        END AS Time_Registered, COUNT(DISTINCT customer_user_id) AS No_Of_Customers
                                        FROM customer
                                        JOIN user ON customer.customer_user_id=user.user_id
                                        GROUP BY Time_Registered
                                        ORDER BY Time_Registered ASC;
                                        ")
                                    ));
                                ?>
                            </div>
                            <div class="chart">
                                <?php 
                                    AreaChart::create(array(
                                        "dataSource"=>(new PdoDataSource($connection))->query("
                                        SELECT CASE EXTRACT(MONTH FROM user.registered_time)
                                            WHEN '1' THEN 'January'
                                            WHEN '2' THEN 'February'
                                            WHEN '3' THEN 'March'
                                            WHEN '4' THEN 'April'
                                            WHEN '5' THEN 'May'
                                            WHEN '6' THEN 'June'
                                            WHEN '7' THEN 'July'
                                            WHEN '8' THEN 'August'
                                            WHEN '9' THEN 'September'
                                            WHEN '10' THEN 'October'
                                            WHEN '11' THEN 'November'
                                            WHEN '12' THEN 'December'
                                            ELSE 'Not Valid'
                                        END AS Time_Registered, COUNT(DISTINCT manager.user_id) AS No_Of_Sports_Arenas
                                        FROM manager
                                        JOIN user ON manager.user_id=user.user_id
                                        JOIN sports_arena ON manager.sports_arena_id=sports_arena.sports_arena_id
                                        GROUP BY Time_Registered
                                        ORDER BY Time_Registered ASC;
                                        ")
                                    ));
                                ?>
                            </div>
                            <div class="chart">
                                    <?php 
                                    BarChart::create(array(
                                        "dataSource"=>(new PdoDataSource($connection))->query("
                                        SELECT CAST(booking_date AS DATE) Date, COUNT(DISTINCT booking_id) AS No_Of_Bookings
                                        FROM booking
                                        GROUP BY Date;
                                        ")
                                    ));
                                ?>
                            </div>
                            <div class="chart">
                                <?php 
                                    DonutChart::create(array(
                                        "dataSource"=>(new PdoDataSource($connection))->query("
                                        SELECT payment_method, COUNT(DISTINCT booking_id) AS No_Of_Bookings
                                        FROM booking
                                        GROUP BY payment_method;
                                        ")
                                    ));
                                ?>
                            </div>
                            <div class="chart-Table">
                                <?php 
                                    Table::create(array(
                                        "dataSource"=>(new PdoDataSource($connection))->query("
                                        SELECT category, COUNT(DISTINCT sports_arena_id) AS No_Of_Sports_Arenas
                                        FROM sports_arena_profile
                                        GROUP BY category;
                                        ORDER BY category ASC;
                                        "),
                                        "cssClass"=>array(
                                            "th"=>"dataTableHeader",
                                            "td"=>"dataTableRows",
                                        ),
                                    ));
                                ?>
                            </div>
                            <div class="chart-Table">    
                                <?php 
                                    Table::create(array(
                                        "dataSource"=>(new PdoDataSource($connection))->query("
                                        SELECT location, COUNT(DISTINCT sports_arena_id) AS No_Of_Sports_Arenas
                                        FROM sports_arena_profile
                                        GROUP BY location;
                                        ORDER BY location ASC;
                                        "),
                                        "cssClass"=>array(
                                            "th"=>"dataTableHeader",
                                            "td"=>"dataTableRows",
                                        ),
                                    ));
                                ?>
                            </div>
                <!-- <div id="view_bookings" class="booking_tab_content active"  style="display: block;">
                </div> -->

                <div>
                    <input type="submit" value="Generate Report" class="faqSubmit" />
                </div>         
                
                </div>
            </div>
    </section>

    <!-- <table class="booking-table">
                        <thead>
                            <tr class="booking-thead-first-tr">
                                <th class="booking-table-first-th">
                                    <div class="booking-table-filter-container">
                                        <div class="booking-table-search-container">
                                            <input type="search" name="search" placeholder="Enter type of report..">
                                            <i class="fas fa-search"></i>
                                        </div>
                                        <div class="booking-table-date-picker">
                                            <label class="date-label">Start Date</label>
                                            <input type="date">
                                        </div>
                                        <div class="booking-table-date-picker">
                                            <label class="date-label">End Date</label>
                                            <input type="date">
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody style="background-color:black;">
                            
                            
                            

                            
                        </tbody>
                       
    </table> -->

    <script>
        let side_menu_open_btn = document.querySelector("#side-menu-open-btn");
        let sidebar = document.querySelector(".sidebar");
        let side_menu_close_btn = document.querySelector("#side-menu-close-btn");
        // let homecontent = document.querySelector(".home-content");


        side_menu_open_btn.onclick = function () {    
            sidebar.classList.add("active");
        }
        side_menu_close_btn.onclick = function () {
            sidebar.classList.remove("active");
        }

        //notification
        function showNotifycation() {
            document.querySelector(".pop-up").classList.toggle("show");
            document.querySelector(".notification-container").classList.toggle("hide");
        }

        function openTab(evt, tabName) {
        var i, booking_tab_content, booking_tab;

        booking_tab_content = document.getElementsByClassName("booking_tab_content");
        
        for (i = 0; i < booking_tab_content.length; i++) {
            booking_tab_content[i].style.display = "none";            
        }
        
        booking_tab = document.getElementsByClassName("booking_tab");
        for (i = 0; i < booking_tab.length; i++) {
            booking_tab[i].className = booking_tab[i].className.replace(" active", "");
        }
        
        document.getElementById(tabName).style.display = "block";
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

    </script>
</body>
</html>
        
        
