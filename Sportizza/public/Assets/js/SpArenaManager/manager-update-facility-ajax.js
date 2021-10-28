//Ajax for adding facility
$(document).ready(function () {
   $("#editFacilityName").bind("keyup focusout", function () {
       $("#btn_update_facility").prop("disabled",false);
       $("#facility_update_error").text("");
       $("#editFacilityName").css("border-color", "#26de81");

       let facility_Name = $("#editFacilityName").val();
       facility_Name = facility_Name.split(" ").join("_");
       $.ajax({
           type: "POST",
           url:"http://localhost/sparenamanager/validatefacilityname/"+facility_Name,
           dataType: "text",

           success: function (response) {
               if(response){
                   console.log("Hello world");
                   $("#facility_update_error").text("Facility is already exist with this name");
                   $("#editFacilityName").css("border-color", "#e74c3c");
                   $("#btn_update_facility").prop("disabled", true);
               }
               else {
                   console.log("Hi");
                   $("#btn_update_facility").prop("disabled",false);
                   $("#facility_update_error").text("");
                   $("#editFacilityName").css("border-color", "#26de81");
               }
           }
       })
   })
});
