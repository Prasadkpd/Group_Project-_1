$(document).ready(function () {
    $("#facilityName").change(function () {
        let fac = $("#facilityName").val();
        let iTime = $("#startTime").val();
        let sTime = iTime.replace(":","");
        let duration = $("#timeSlotDuration").val();
        $.ajax({
            type: "POST",

            url: "http://localhost/sparenamanager/managervalidatetimeslots/"+sTime+duration+fac,
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