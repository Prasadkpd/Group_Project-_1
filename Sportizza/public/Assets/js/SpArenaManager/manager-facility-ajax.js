//Ajax for adding facility
$(document).ready(function () {

    //When the cursor is on
      $("#facilityName").keyup(function () {
    
        //Assigning the facility's name
          let fac = $("#facilityName").val();
    
          $.ajax({
              type: "POST",
    
              url: "http://localhost/sparenamanager/managervalidatetimeslots/"+sTime+duration+fac+price,
              dataType: "text",
              
              success: function (response) {
                  if (response){
                  $("#imgMsg6").text("Timeslot cannot be added to the selected facility");
                  $("#facilityName").css("border-color", "#e74c3c");
                  document.querySelector('#timeSlotbutton').disabled = true;
              }
          }
          })
      })
    })