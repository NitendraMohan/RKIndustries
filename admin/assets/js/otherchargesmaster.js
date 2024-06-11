jQuery.noConflict();
jQuery(document).ready(function ($) {
    /**
     * Function for loading complete data of units
     */
    function load_table() {
        $.ajax({
            url: "../controller/otherchargesController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                // Assuming the result returned by controller/otherchargesController.php is the HTML table content
                $("#otherchargesTableContents").html(result);
                var total_records = $("#otherchargesTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>" + total_records + "</span></b></h6>");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    /**
     * code for active and deactive
     */
    // $(document).on('click', '.btn_toggle', function(e) {
    //     e.preventDefault();
    //     var id = $(this).data('id');
    //     var status = $(this).data('status');
        
    //     $.ajax({
    //         url: '../controller/otherchargesController.php',
    //         type: 'POST',
    //         data: { id: id, action: status == "active" ? "Deactive" : "Active" },
    //         success: function(response) {
    //             if (response == 1) {
    //                 toggleButtonStatus(id); // Toggle button status
    //             } else {
    //                 console.log("Error occurred while toggling button status.");
    //             }
    //         }
    //     });
    // });
    
    function toggleButtonStatus(id) {
        var $btn = $(".btn_toggle[data-id='" + id + "']");
        var status = $btn.data('status');
        // Toggle button text and class
        if (status == "active") {
            $btn.text("Deactive").removeClass('btn-info').addClass('btn-warning').data('status', 'deactive');
        } else {
            $btn.text("Active").removeClass('btn-warning').addClass('btn-info').data('status', 'active');
        }
    }
    
   
    /**
     * Code for submit model form data
     */
    $("#unitForm").on("submit", function (e) {
        e.preventDefault();
        var expanse_name = $("#expanse_name").val();
        var status = $("#status").val();
        if (expanse_name == "" || status == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            // var formData = new FormData(this);
            var formData = new FormData(this);
            var id = $("#modalid").val();
            if (id == '' || id == undefined) {
                var action = "inserrt";
                formData.append("action", "insert");
            } else {
                var action = 'update';
                formData.append("action", "update");
            }
            // console.log(formData);
            $.ajax({
                url: "../controller/otherchargesController.php",
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
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
                        $("#unitForm").trigger("reset");
                        $("#modalid").val('');
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
            url: "../controller/otherchargesController.php",
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
            url: "../controller/otherchargesController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                // $("#myModalUpdate").html(result);
                $arr = JSON.parse(result);
                console.log($arr);
                
                $("#modalid").val($arr['id']);
                $("#expanse_name").val($arr['expanse_name']);
                if($arr['is_percentage']==1){
                    $("#is_percentage").prop('checked',true);
                }
                else{
                    $("#is_percentage").prop('checked',false);
                }
                $("#expanse_value").val($arr['value']);
                $("#detail").val($arr['detail']);
                $("#status").val($arr['status']);
                $("#myModal").modal('show');
            }
        });
    });
    //End

   /**
     * Live Search
     */
    $("#search").on("keyup", function () {
        console.log("searching...");
        var search_term = $(this).val();
        var eventaction = "search";
        $.ajax({
            url: "../controller/otherchargesController.php",
            type: "POST",
            data: { action: eventaction, search: search_term },
            success: function (data) {
                $("#otherchargesTableContents").html(data);
            }
        });
    });
});
//End