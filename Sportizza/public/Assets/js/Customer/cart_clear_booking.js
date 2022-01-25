$(document).ready(function () {
    $(".itemDelete").click(function () {
        let booking_id = $(this).find(".storeValue").val();
        let price = $(this).siblings(".itemPrice").find(".storePrice").val();
        let method = $(this).siblings(".itemPayment").find(".storePayment").val();

        console.log(booking_id);
        console.log(price);
        console.log(method);

        $.ajax({
            type: "POST",

            url: "http://localhost/customer/clearbooking/" + booking_id,
            
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
                        if(method=="card"){
                        let card = $("#storeCardTotal").val();
                        let newCard = String(card - price);
                        $("#storeCardTotal").val(newCard);
                        let displayCardTotal = "LKR " + newCard;
                        $("#showCardTotal").html(displayCardTotal);
                        $("#gatewayAmount").val(newCard);
                        }
                        $("#showTotal").html(displayTotal);
                    }  
            }
        })
    })
})