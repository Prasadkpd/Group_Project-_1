
$(document).ready(function () {
  $(".removeItem").click(function () {
      let form = document.getElementById("addtocartform");
      let id = $(".removeItem").val();
      form.submit();
      // let pay_method = $(".checkbox").val();
      // let booking_date = $("#dateInput").val();
      // console.log(id);

      $.ajax({
          type: "POST",

          url: "http://localhost/customer/hidebooking/",
          // data: temp,
          dataType: "text",
          // data: {
          //     courseId: temp
          // },
          success: function (response) {
              if(response){
                console.log(id);
                $("#"+id).hide();
              }
          }
      })
  })
})
