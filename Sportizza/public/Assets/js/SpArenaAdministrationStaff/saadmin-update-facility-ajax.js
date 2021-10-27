
//Ajax for updating facility
$(document).ready(function () {

    


    //When the cursor is on
    // if($("#editfacilityname").val()==getFacilityName()) {
    //     $("#update-facility-btn").prop("disabled", false);
    //     $("#facilityupdateMsg").text("");
    //     $("#editfacilityname").css("border-color", "#26de81");
    // }
    // else{
      $("#editfacilityname").bind("keyup focusout", function () {
        $("#update-facility-btn").prop("disabled", false);
        $("#facilityupdateMsg").text("");
        $("#editfacilityname").css("border-color", "#26de81");
   
        //Get facility id - START

        let temp = $('#formUpdateFacility').attr('action');
        temp = temp.split("/");
        let facilityId = temp[temp.length - 1];
        //Get facility id - END


        //Assigning the facility's name
          let fac = $("#editfacilityname").val();
          
          //Replacing spaces with underscore
          fac = fac.trim().split(' ').join('_');
          let argument = `${fac}__${facilityId}`;

          $.ajax({
              type: "POST",
              url: "http://localhost/spadministrationstaff/validateAndUpdatefacilityname/"+argument,
              dataType: "text",
              
              success: function (response) {
                //If there's a facility with the given name
                  if (response){
                  $("#facilityupdateMsg").text("Facility already exists with this name");
                  $("#editfacilityname").css("border-color", "#e74c3c");
                  $("#update-facility-btn").prop("disabled", true);
                  
              }
          }
          })
      })
    }
    )
