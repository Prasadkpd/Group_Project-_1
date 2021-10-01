$(document).ready(function () {
    $("#uquestion").change(function () {
        let temp = $("#uquestion").val();
        // temp = parseInt(temp);
        console.log(typeof temp);

        $.ajax({
            type: "POST",

            url: "http://localhost/admin/getsolutions/" + temp,
            // data: temp,
            dataType: "html",
            // data: {
            //     courseId: temp
            // },
            success: function (response) {
                $("#usolution").html(response);
            }
        })
    })
})