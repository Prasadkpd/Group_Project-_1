$(document).ready(function () {
    $("#analyticsDateFilter").change(function () {
        let dateValue = $("#analyticsDateFilter").val();

        $.ajax({
            type: "POST",

            url: "http://localhost/sparenamanager/reshapepiechart/" + dateValue,
            
            dataType: "text",
            
            success: function (response) {
                var valuearray = response.split("_");
                var paymentarray = valuearray[0].split(",");
                console.log(paymentarray);
                var countarray = valuearray[1].split(",");
                console.log(countarray);

                // CHART 2 UPDATE
                myChart2.config.data.labels = paymentarray;
                myChart2.config.data.datasets[0].data = countarray;
                myChart2.update();
                
                
            }
        })
    })
})