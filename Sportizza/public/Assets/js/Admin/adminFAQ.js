$(document).ready(function () {
    $("#utype").change(function () {
        let temp = $("#utype").val();
        // temp = parseInt(temp);
        console.log(typeof temp);

        $.ajax({
            type: "POST",

            url: "http://localhost/admin/getquestions/" + temp,
            // data: temp,
            dataType: "html",
            // data: {
            //     courseId: temp
            // },
            success: function (response) {
                $("#uquestion").html(response);
            }
        })
    })
})