$(document).ready(function () {
    $("#analyticsDateFilter").change(function () {
        let dateValue = $("#analyticsDateFilter").val();

        $.ajax({
            type: "POST",

            url: "http://localhost/admin/reshapetablechart/" + dateValue,
            
            dataType: "html",
            
            success: function (response) {
                $("#tableCharts").html(response);
            }
        })
    })
})