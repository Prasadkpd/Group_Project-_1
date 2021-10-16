$(document).ready(function () {
    $("#facilityName").change(function () {
        let fac = $("#facilityName").val();
        let iTime = $("#startTime").val();
        let sTime = iTime.replace(":","");
        let duration = $("#timeSlotDuration").val();
        let price = $("#slotPrice").val();
        // let sFac = fac.toString();
        // let sSTime = sTime.toString();
        // let sDuration = duration.toString();
        // let sPrice = price.toString();
        let combined = sTime.concat(duration,fac,price);

        console.log(sTime,duration,fac,price);
        console.log(combined);

        $.ajax({
            type: "POST",

            url: "http://localhost/sparenamanager/managervalidatetimeslots/"+sTime+duration+fac+price,
            // url: "http://localhost/sparenamanager/managervalidatetimeslots/"+combined,
            // url: "http://localhost/sparenamanager/managervalidatetimeslots/"+sSTime+sDuration+sPrice+sFac,
            // data: temp,
            dataType: "text",
            // data: {
            //     courseId: temp
            // },
            success: function (response) {
                $("#imgMsg6").text(response);
            }
        })
    })
})