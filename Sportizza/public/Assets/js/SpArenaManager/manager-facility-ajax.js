//Ajax for adding facility
$(document).ready(function () {
  //When the cursor is on
  $("#facilityName").bind("keyup focusout", function () {
    $("#btn-add-facility").prop("disabled", false);
    $("#facilityNameMsg").text("");
    $("#facilityName").css("border-color", "#26de81");

    //Assigning the facility's name
    let fac = $("#facilityName").val();

    //Replacing spaces with underscore
    fac = fac.split(" ").join("_");
    let argument = `${fac}`;

    $.ajax({
      type: "POST",
      url:
        "http://localhost/sparenamanager/validatefacilityname/" +
        argument,
      dataType: "text",

      success: function (response) {
        //If there's a facility with the given name
        if (response) {
          $("#facilityNameMsg").text("Facility already exists with this name");
          $("#facilityName").css("border-color", "#e74c3c");

          $("#btn-add-facility").prop("disabled", true);
        }
      },
    });
  });
});

