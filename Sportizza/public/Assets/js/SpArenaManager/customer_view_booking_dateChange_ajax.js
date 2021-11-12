$(document).ready(function () {

    $("body").on("click", "button.removeItem", function () {
        // let form = document.getElementById("addtocartform");
        let id = $(this).val();
        let payment_cart = $(this).parent().prev();
        
        let paymentMethod = payment_cart.find('.checkbox').val();
        let bookingDate = $('.bookingDatehidden').val();
        
        console.log(id);
        console.log(bookingDate);
        console.log(paymentMethod);

        bookingDate = bookingDate.split("-").join("_");

        let argument = `${id}__${bookingDate}__${paymentMethod}`;

        // console.log(argument);

        $.ajax({
            type: "POST",

            url: "http://localhost//hidebooking/" + argument,
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
      let arenaId = $("#arenaId").val();
      let dateVal = $("#dateInput").val();
      $(".bookingDatehidden").val(dateVal);
      //Remove dashes in dateVal
      dateVal = dateVal.split("-").join("_");

      // Combine date and arena ID
      let argument = `${arenaId}__${dateVal}`;

      $.ajax({
        type: "POST",

        url: "http://localhost/customer/searchtimeslotdate/" + argument,

        dataType: "html",

        success: function (response) {
          $("#eventsList").html(response);
        },
      });
    });
});

