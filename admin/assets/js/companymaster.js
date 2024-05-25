jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/companyController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                if(result!=null){
                    data = JSON.parse(result);
                    $('#company_name').val(data['company_name'])
                    $('#gst_no').val(data['gst_no'])
                    $('#address').val(data['address'])
                    $('#mail_id').val(data['email'])
                    $('#contact_number').val(data['contact_number'])
                    // $("#unitTableContents").html(result);

                }
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
        if (company_name == "" || gst_no == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            var logo = $('#logo')[0].files[0];
            console.log(logo);
            var formData = $('#companyForm').serialize() + '&action=submit&file='+logo;
            // formData.append('file', logo);
            console.log(formData);
            $.ajax({
                url: "../controller/companyController.php",
                type: "POST",
                data: formData,
                dataType: 'json',
                // contentType: false,
                success: function (response) {
                    console.log(response);
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
                        // $("#unitForm").trigger("reset");
                    }, 2000);
                }
            });
        }
    });

    //Code delete record from table
    // $(document).on("click", ".unitDelete", function () {
    //     var uid = $(this).data("id");
    //     var uaction = "delete";
    //     var element = this;
    //     $.ajax({
    //         url: "../controller/unitController.php",
    //         type: "POST",
    //         data: { action: uaction, id: uid },
    //         success: function (result) {
    //             if (result == 1) {
    //                 $(element).closest("tr").fadeOut();
    //                 load_table();
    //             } else {
    //                 alert("can't delete");
    //             }
    //         }
    //     });
    // });
    //End

    //Update model form record
    // $(document).on("click", ".btnUpdate", function () {
    //     var uid = $("#unitId").val();
    //     var editUnit = $("#editunitname").val();
    //     var editStatus = $("#editstatus").val();;
    //     var uaction = "update";
    //     $.ajax({
    //         url: "../controller/unitController.php",
    //         type: "POST",
    //         data: { action: uaction, id: uid, unit: editUnit, status: editStatus },
    //         success: function (result) {
    //             $("#myModalUpdate").modal('hide');
    //             if (result == 1) {
    //                 load_table();
    //                 $("#unitForm").trigger("reset");
    //             } else {
    //                 alert("not saved");
    //             }
    //         }
    //     });
    // });
});
//End