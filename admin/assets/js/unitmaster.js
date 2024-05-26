jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/unitController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                // Assuming the result returned by controller/unitController.php is the HTML table content
                $("#unitTableContents").html(result);
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
        $(document).on("click", ".modalsubmit", function (e) {
        e.preventDefault();
        var unitname = $("#unitname").val();
        var unitstatus = $("#status").val();
        if (unitname == "" || unitstatus == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            var formData = $('#unitForm').serialize() + '&action=insert';
            console.log(formData);
            $.ajax({
                url: "../controller/unitController.php",
                type: "POST",
                data: formData,
                dataType: 'json',
                success: function (response) {
                    // console.log(response);
                    if (response.duplicate) {
                        $("#msg").fadeIn().removeClass('sucess-msg').addClass('error-msg').html("Duplicate Record Detected: Please Make Changes.");
                    } else if (response.success) {
                        $("#msg").fadeIn().removeClass('error-msg').addClass('sucess-msg').html("Save successful: Your record has been successfully saved.");
                        load_table(); // Assuming this function loads the table data
                    } else {
                        $("#msg").fadeIn().removeClass('sucess-msg').addClass('error-msg').html("Save Failed: Record Not Saved.");
                    }
                    setTimeout(function () {
                        $("#msg").fadeOut("slow");
                        $("#unitForm").trigger("reset");
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
            url: "../controller/unitController.php",
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
            url: "../controller/unitController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                $("#myModalUpdate").html(result);
                $("#myModalUpdate").modal('show');
            }
        });
    });
    //End

    //Update model form record
    $(document).on("click", ".btnUpdate", function (e) {
        e.preventDefault();
        var uid = $("#unitId").val();
        var editUnit = $("#editunitname").val();
        var editStatus = $("#editstatus").val();;
        var uaction = "update";
        $.ajax({
            url: '../controller/unitController.php',
            type: 'POST',
            data: { action: uaction, id: uid, unit: editUnit, status: editStatus },
            dataType: 'json',
            success: function (response) {
                if (response.duplicate) {
                    $("#msg").fadeIn().removeClass('sucess-msg').addClass('error-msg').html("Duplicate Record Detected: Please Make Changes.");
                } else if (response.success) {
                    $("#msg1").fadeIn().removeClass('error-msg').addClass('sucess-msg').html(response.msg);
                    load_table(); // Assuming this function loads the table data
                } else {
                    $("#msg1").fadeIn().removeClass('sucess-msg').addClass('error-msg').html(response.msg);
                }
                setTimeout(function () {
                    $("#msg1").fadeOut("slow");
                    $("#myModalUpdate").modal("hide");
                }, 2000);
            }
        });
    });

    /**
     * Live Search
     */
    $("#search").on("keyup",function(){
        console.log("searching...");
        var search_term = $(this).val();
        var eventaction = "search";
        $.ajax({
            url: "../controller/unitController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#unitTableContents").html(data);
        }
        });
    });
});
//End