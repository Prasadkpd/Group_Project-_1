$(document).ready(function(){
    $("#removeItem").click(function(event){
        event.preventDefault();
        let temp = $("#removeItem").val();
        // $(".available-bookings .row").addClass("hide");
        // $(".available-bookings .hideDetails[value="+temp+"]").addClass("hide");
        $("#"+temp).hide();
        // temp = parseInt(temp);
        console.log(temp);

        // $.ajax({
        //     type: "POST",

        //     url: "http://localhost/customer/hidebooking/" + temp,
        //     // data: temp,
        //     // dataType: "html",
        //     // data: {
        //     //     courseId: temp
        //     // },
        //     // success: function (response) {
        //     //     $("#uquestion").html(response);
        //     // }
        // })
    })
})