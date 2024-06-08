jQuery.noConflict();
jQuery(document).ready(function ($) {
    $(document).on("click", ".modalClose", function () {
        console.log('close button triggered');
        $("#userForm").trigger("reset");
        $("#modelid").val('');
    });

    $('.decimalplaces').on('input', function() {
        // Get the value entered by the user
        let value = $(this).val();
        
        // If the input is empty, leave it as is
        if (value === "") return;
        
        // If the input is already formatted with two decimal places, leave it as is
        if (value.includes(".") && value.split(".")[1].length === 2) return;
        
        // Parse the input value as a float
        let floatValue = parseFloat(value);
        
        // If the parsed value is a valid number, format it to two decimal places
        if (!isNaN(floatValue)) {
            $(this).val(floatValue.toFixed(2));
        }
    });


    $(document).on('click', '.btn_toggle', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var status = $(this).data('status');
        var dbtable = $(this).data('dbtable');
        
        $.ajax({
            url: '../controller/commanController.php',
            type: 'POST',
            data: { id: id, dbtable:dbtable, action: status == "active" ? "Deactive" : "Active"},
            success: function(response) {
                if (response == 1) {
                    toggleButtonStatus(id); // Toggle button status
                } else {
                    console.log("Error occurred while toggling button status.");
                }
            }
        });
    });
    function toggleButtonStatus(id) {
        var $btn = $(".btn_toggle[data-id='" + id + "']");
        var status = $btn.data('status');
        // Toggle button text and class
        if (status == "active") {
            $btn.text("Deactive").removeClass('btn-success').addClass('btn-secondary').data('status', 'deactive');
        } else {
            $btn.text("Active").removeClass('btn-secondary').addClass('btn-success').data('status', 'active');
        }
    }
    


});
//End