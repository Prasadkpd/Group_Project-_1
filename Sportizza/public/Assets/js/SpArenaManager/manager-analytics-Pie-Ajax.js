$(document).ready(function () {
    $("#analyticsDateFilter").change(function () {
        let dateValue = $("#analyticsDateFilter").val();

        $.ajax({
            type: "POST",

            url: "http://localhost/sparenamanager/reshapechart/" + dateValue,
            
            dataType: "text",
            
            success: function (response) {
                var controlarray = response.split("$");

                var value1array = controlarray[0].split("_");
                var paymentarray = value1array[0].split(",");
                console.log(paymentarray);
                var count1array = value1array[1].split(",");
                console.log(count1array);
                
                var value2array = controlarray[1].split("_");
                var slotarray = value2array[0].split(",");
                console.log(slotarray);
                var count2array = value2array[1].split(",");
                console.log(count2array);
                
                var value3array = controlarray[2].split("_");
                var facilityarray = value3array[0].split(",");
                console.log(facilityarray);
                var count3array = value3array[1].split(",");
                console.log(count3array);

                // CHART 2 UPDATE
                myChart2.config.data.labels = paymentarray;
                myChart2.config.data.datasets[0].data = count1array;
                myChart2.update();
                
                // CHART 3 UPDATE
                myChart3.config.data.labels = slotarray;
                myChart3.config.data.datasets[0].data = count2array;
                myChart3.update();
                
                // CHART 4 UPDATE
                myChart4.config.data.labels = facilityarray;
                myChart4.config.data.datasets[0].data = count3array;
                myChart4.update();
                
                
            }
        })
    })
})