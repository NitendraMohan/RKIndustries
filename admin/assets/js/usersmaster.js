jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/usersController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                $("#usersTableContents").html(result);
                var total_records = $("#usersTableContents tr").length;
                $('#total_records').html("Total Records: "+total_records);
                
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    /**
     * Code for submit model form data
     */
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var username = $("#username").val();
        var userstatus = $("#status").val();
        if (username == "" || userstatus == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            // var formData = $('#userForm').serialize() + '&action=insert';
            var formData = new FormData(this);
            var id = $('#modalid').val();
            console.log('id='.id);
            if(id =='' || id == undefined){
                action = 'insert';
                formData.append("action","insert");
            }
            else{
                action = 'update';
                var currentImage =  $("#logo_image").attr('src') ?? '';
                formData.append("image",currentImage);
                formData.append("action","update");
            }
            $.ajax({
                url: "../controller/usersController.php",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                cache:false,
                processData:false,
                success: function (response) {
                    // console.log(response);
                    if (response.duplicate) {
                        $("#msg").fadeIn().removeClass('sucess-msg').addClass('error-msg').html("Duplicate Record Detected: Please Make Changes.");
                    } else if (response.success) {
                        $("#msg").fadeIn().removeClass('error-msg').addClass('sucess-msg').html(response.msg);
                        load_table(); // Assuming this function loads the table data
                    } else {
                        $("#msg").fadeIn().removeClass('sucess-msg').addClass('error-msg').html(response.msg);
                    }
                    setTimeout(function () {
                        $("#msg").fadeOut("slow");
                        $("#userForm").trigger("reset");
                        if(action == 'update') $("#myModal").modal("hide");
                    }, 2000);
                }
            });
        }
    });

    //Code delete record from table
    $(document).on("click", ".unitDelete", function () {
        var uid = $(this).data("id");
        var uaction = "delete";
        var element = this;
        $.ajax({
            url: "../controller/usersController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                if (result == 1) {
                    $(element).closest("tr").fadeOut();
                    load_table();
                } else {
                    alert("can't delete");
                }
            }
        });
    });
    //End

    //Edit record from table
    $(document).on("click", ".unitEdit", function () {
        var uid = $(this).data("id");
        var uaction = "edit";
        $.ajax({
            url: "../controller/usersController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                console.log(arr['username']);
                $("#modalid").val(arr['id']);
                $("#logo_image").attr('src',arr['image']);
                $("#username").val(arr['username']);
                $("#role").val(arr['role']);
                $("#gender").val(arr['gender']);
                $("#dob").val(arr['dob']);
                $("#mobile").val(arr['mobile']);
                $("#email").val(arr['email']);
                $("#address").val(arr['address']);
                $("#password").val(arr['password']);
                $("#status").val(arr['status']);
                $("#myModal").modal('show');
            }
        });
    });
    //End
    /**
     * Live Search
     */
    $("#search").on("keyup",function(){
        console.log("searching...");
        var search_term = $(this).val();
        var eventaction = "search";
        $.ajax({
            url: "../controller/usersController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#usersTableContents").html(data);
            var total_records = $("#usersTableContents tr").length;
            $('#total_records').html("Total Records: "+total_records);
        }
        });
    });
});
//End