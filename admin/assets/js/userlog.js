jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/usersLogController.php",
            type: "POST",
            data: { action: "loadUserLogs" },
            success: function (result) {
                // Assuming the result returned by controller/unitController.php is the HTML table content
                $("#userLogTableContents").html(result);
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    //Code delete record from table
    $(document).on("click", ".unitDelete", function () {
        var uid = $(this).data("id");
        var uaction = "undo";
        var element = this;
        $.ajax({
            url: "../controller/usersLogController.php",
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
    $(document).on("click", ".btnUpdate", function () {
        var uid = $("#unitId").val();
        var editUnit = $("#editunitname").val();
        var editStatus = $("#editstatus").val();;
        var uaction = "update";
        $.ajax({
            url: "../controller/unitController.php",
            type: "POST",
            data: { action: uaction, id: uid, unit: editUnit, status: editStatus },
            success: function (result) {
                $("#myModalUpdate").modal('hide');
                if (result == 1) {
                    load_table();
                    $("#unitForm").trigger("reset");
                } else {
                    alert("not saved");
                }
            }
        });
    });
});
//End