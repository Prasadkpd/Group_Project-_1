$(document).ready(function () {
    $("#dateInput").change(function () {
        let arenaId = $("#arenaId").val();
        let dateVal = $("#dateInput").val();
        
        //Remove dashes in dateVal
        dateVal = dateVal.split('-').join('_');
        
        // Combine date and arena ID
        let argument = `${arenaId}__${dateVal}`;
        
        $.ajax({
            type: "POST",

            url: "http://localhost/customer/searchtimeslotdate/" + argument,

            dataType: "html",

            success: function (response) {
                $("#eventsList").html(response);

                // Just to check whether ajax is successfully working
                console.log(Math.floor(Math.random() * 100));
            }
        })
    })
})