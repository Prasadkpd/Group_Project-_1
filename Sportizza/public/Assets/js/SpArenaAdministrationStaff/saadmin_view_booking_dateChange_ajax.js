$(document).ready(function () {

    $("body").on("click", "button.removeItem", function () {
        // let form = document.getElementById("addtocartform");
        let id = $(this).val();
        let payment_cart = $(this).parent().prev();
        
        let bookingDate = $('#dateInput').val();
        
        console.log(id);
        console.log(bookingDate);

        bookingDate = bookingDate.split("-").join("_");

        let argument = `${id}__${bookingDate}`;

        // console.log(argument);

        $.ajax({
            type: "POST",

            url: "http://localhost/Spadministrationstaff/hidebooking/" + argument,
            // data: temp,
            dataType: "text",
            // data: {
            //     courseId: temp
            // },
            success: function (response) {
            if (response) {
                $("#" + id).hide();
                console.log("The hidden timeslot id is : "+id);
            }
            // console.log(response);
        }
    })
    })

    $("body").on("change", "input#dateInput", function () {
      // let arenaId = $("#arenaId").val();
      let dateVal = $("#dateInput").val();
      $(".bookingDatehidden").val(dateVal);
      //Remove dashes in dateVal
      dateVal = dateVal.split("-").join("_");

      // Combine date and arena ID
      // let argument = `${arenaId}__${dateVal}`;

      $.ajax({
        type: "POST",

        
        url: "http://localhost/Spadministrationstaff/searchtimeslotdate/" + dateVal,

        dataType: "html",

        success: function (response) {
          $("#eventsList").html(response);
          // console.log(response);
        },
      });
    });
});

