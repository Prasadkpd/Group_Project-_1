$(document).ready(function () {
    $("#popup_cmp_button").click(function () {
        let temp = $("#popup_cmp_button").val();
        // temp = parseInt(temp);
        console.log(temp);

        $.ajax({
            type: "POST",

            url: "http://localhost/admin/getcomplaints/" + temp,
            // data: temp,
            dataType: "html",
            // data: {
            //     courseId: temp
            // },
            success: function (response) {
                $("#displaycomplaints").html(response);
            }
        })
    })
})