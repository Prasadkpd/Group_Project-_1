//Ajax for adding facility
$(document).ready(function () {

    //When the cursor is on
      $("#editfacilityname").bind("keyup focusout", function () {
        $("#update-facility-btn").prop("disabled", false);
        $("#facilityUpdateMsg").text("");
        $("#editfacilityname").css("border-color", "#26de81");
   
        //Assigning the facility's name
          let fac = $("#editfacilityname").val();
          
          //Replacing spaces with underscore
          fac = fac.split(' ').join('_');
          let argument = `${fac}`;
      
          $.ajax({
              type: "POST",
              url: "http://localhost/spadministrationstaff/validatefacilityname/"+argument,
              dataType: "text",
              
              success: function (response) {
                
                //If there's a facility with the given name
                  if (response){
                  $("#facilityUpdateMsg").text("Facility already exists with this name");
                  $("#editfacilityname").css("border-color", "#e74c3c");
                  $("#update-facility-btn").prop("disabled", true);
                  
              }
          }
          })
      })
    })