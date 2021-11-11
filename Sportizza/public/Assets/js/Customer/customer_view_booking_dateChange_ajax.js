$(document).ready(function () {

    $("body").on("click", "button.removeItem", function () {
        // let form = document.getElementById("addtocartform");
        let id = $(".removeItem").val();
        // let pay_method = $(".checkbox").val();
        // let booking_date = $("#dateInput").val();
        // console.log(id);
        // alert("Hello");
        let timeslot_id = document.getElementsByClassName("timeSlotClass").value();
        console.log(timeslot_id);
        let bookingDate = document.getElementById("bookingDatehidden").val();
        let paymentMethod = document.getElementsByClassName("payment_input").val();
        console.log(bookingDate);
        console.log(paymentMethod);

        $.ajax({
            type: "POST",

            url: "http://localhost/customer/hidebooking/",
            // data: temp,
            dataType: "text",
            // data: {
            //     courseId: temp
            // },
            success: function (response) {
            if (response) {
                console.log(id);
                $("#" + id).hide();
            }
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

