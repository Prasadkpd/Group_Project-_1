$(document).ready(function () {
    $(".itemDelete").click(function () {
        let booking_id = $(this).find(".storeValue").val();
        let price = $(this).siblings(".itemPrice").val();
        console.log(booking_id);
        console.log(price);

        $.ajax({
            type: "POST",

            url: "http://localhost/spadministrationstaff/clearbooking/" + dateValue,
            
            dataType: "text",
            
            success: function (response) {
                    // console.log(response);   
                    $("#cartItem" + booking_id).hide();
                    console.log("Successfully cleared booking no " + booking_id);
                    let total = $("#storeTotal").val();
                    let newSum = String(total - price);
                    let displayTotal = "LKR " + newSum;
                    $("#showTotal").html(displayTotal);
            }
        })
    })
})