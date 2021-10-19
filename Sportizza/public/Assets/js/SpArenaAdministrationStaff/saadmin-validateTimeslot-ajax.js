$(document).ready(function () {
  $("#facilityName").change(function () {
    let fac = $("#facilityName").val();
    let iTime = $("#startTime").val();
    let sTime = iTime.replace(":", "");
    let duration = $("#timeSlotDuration").val();
    let price = $("#slotPrice").val();
    let combined = sTime.concat(duration, fac, price);

    console.log(sTime, duration, fac, price);
    console.log(combined);

    $.ajax({
      type: "POST",

      url:
        "http://localhost/spadministrationstaff/validatetimeslots/" +
        sTime +
        duration +
        fac +
        price,
      dataType: "text",

      success: function (response) {
        if (response) {
          console.log(response);
          $("#imgMsg6").text(
            "Timeslot cannot be added to the selected facility"
          );
          $("#facilityName").css("border-color", "#e74c3c");
          document.querySelector("#timeSlotbutton").disabled = true;
        }
      },
    });
  });
});
