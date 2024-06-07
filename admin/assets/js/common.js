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


});
//End