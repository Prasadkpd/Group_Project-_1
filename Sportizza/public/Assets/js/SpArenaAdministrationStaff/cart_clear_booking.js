$(document).ready(function () {
    $(".itemDelete").click(function () {
        let booking_id = $(this).find(".storeValue").val();
        let price = $(this).siblings(".itemPrice").find(".storePrice").val();
        
        console.log(booking_id);
        console.log(price);
        

        $.ajax({
            type: "POST",

            url: "http://localhost/spadministrationstaff/clearbooking/" + booking_id,
            
            dataType: "text",
            
            success: function (response) {
                    // console.log(response); 
                    if(response){
                        $("#cartItem" + booking_id).hide();
                        console.log("Successfully cleared booking no " + booking_id);
                        let total = $("#storeTotal").val();
                        let newSum = String(total - price);
                        $("#storeTotal").val(newSum);
                        let displayTotal = "LKR " + newSum;
                        $("#showTotal").html(displayTotal);
                    }  
            }
        })
    })
})