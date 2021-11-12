//Ajax for adding staff
$(document).ready(function () {
    //When the cursor is on
    $("#addmobile").bind("keyup focusout", function () {
        $("#btn-add-user").prop("disabled", false);
        $("#mobileMsg").text("");
        $("#addmobile").css("border-color", "#26de81");

        //Assigning the facility's name
        let mobile = $("#addmobile").val();

        $.ajax({
            type: "POST",
            url:
                "http://localhost/sparenamanager/validatemobilenumber/" +
                mobile,
            dataType: "text",

            success: function (response) {
                //If there's a facility with the given name
                if (response) {
                    $("#mobileMsg").text("Mobile Number already exists with this Number");
                    $("#addmobile").css("border-color", "#e74c3c");

                    $("#btn-add-user").prop("disabled", true);
                }
            },
        });
    });

    $("#addusername").bind("keyup focusout", function () {
        $("#btn-add-user").prop("disabled", false);
        $("#usernameMsg").text("");
        $("#addusername").css("border-color", "#26de81");

        //Assigning the facility's name
        let username = $("#addusername").val();

        $.ajax({
            type: "POST",
            url:
                "http://localhost/sparenamanager/validateusername/" +
                username,
            dataType: "text",

            success: function (response) {
                //If there's a facility with the given name
                if (response) {
                    $("#usernameMsg").text("User Name already exists with this Name");
                    $("#addusername").css("border-color", "#e74c3c");

                    $("#btn-add-user").prop("disabled", true);
                }
            },
        });
    });
});

