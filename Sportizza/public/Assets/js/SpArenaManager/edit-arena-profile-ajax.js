//Ajax for adding staff
$(document).ready(function () {
    //When the cursor is on
    $("#spArenaName").bind("keyup focusout", function () {
        $("#edit_arena_profile").prop("disabled", false);
        $("#spArenaNameMsg").text("");
        $("#spArenaName").css("border-color", "#26de81");

        //Assigning the facility's name
        let arena_name = $("#spArenaName").val();
        let category = $("#category").val();
        let location = $("#location").val();

        let argument = `${arena_name}__${category}__${location}`;
        $.ajax({
          type: "POST",
          url:
            "http://localhost/sparenamanager/validateeditarenaname/" + argument,
          dataType: "text",

          success: function (response) {
            //If there's a facility with the given name
            if (response) {
              $("#spArenaNameMsg").text(
                "A Sports arena already exists with name, location, and category"
              );
              $("#spArenaName").css("border-color", "#e74c3c");

              $("#edit_arena_profile").prop("disabled", true);
            }
          },
        });
    });

    $("#category").bind("keyup focusout", function () {
      $("#edit_arena_profile").prop("disabled", false);
      $("#other-categoryMsg").text("");
      $("#category").css("border-color", "#26de81");

      //Assigning the facility's name
      let arena_name = $("#spArenaName").val();
      let category = $("#category").val();
      let location = $("#location").val();

      let argument = `${arena_name}__${category}__${location}`;
      $.ajax({
        type: "POST",
        url:
          "http://localhost/sparenamanager/validateeditarenaname/" + argument,
        dataType: "text",

        success: function (response) {
          //If there's a facility with the given name
          if (response) {
            $("#other-categoryMsg").text(
              "A Sports arena already exists with this category same name and location"
            );
            $("#category").css("border-color", "#e74c3c");

            $("#edit_arena_profile").prop("disabled", true);
          }
        },
      });
    });

    $("#location").bind("keyup focusout", function () {
      $("#edit_arena_profile").prop("disabled", false);
      $("#other-locationMsg").text("");
      $("#location").css("border-color", "#26de81");

      //Assigning the facility's name
      let arena_name = $("#spArenaName").val();
      let category = $("#category").val();
      let location = $("#location").val();

      let argument = `${arena_name}__${category}__${location}`;
      $.ajax({
        type: "POST",
        url:
          "http://localhost/sparenamanager/validateeditarenaname/" + argument,
        dataType: "text",

        success: function (response) {
          //If there's a facility with the given name
          if (response) {
            $("#other-locationMsg").text(
              "A Sports arena already exists with this location same name and category"
            );
            $("#location").css("border-color", "#e74c3c");

            $("#edit_arena_profile").prop("disabled", true);
          }
        },
      });
    });
    
});
