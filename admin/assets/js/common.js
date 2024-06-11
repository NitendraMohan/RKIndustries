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
    
    // $("#category").keyup( function() {
        // Call the setupAutocomplete function with appropriate parameters
        setupAutocomplete("category", "category_list", "list", "tbl_category" ,"category_name");
        setupAutocomplete("brandName", "brand_list", "list", "tbl_brand", "brand_name");
        setupAutocomplete("productName", "product_list", "list", "tbl_products", "product_name");
        // setupAutocomplete("branch", "branch_list", "list", "tbl_branch", "branch_name");
        // setupAutocomplete("user", "user_list", "list", " tbl_users", "username");
    // });

    function setupAutocomplete(inputId, listContainerId, actionName, tablename, tablefield) {
        var typingTimer;
        var doneTypingInterval = 200;
        $("#" + inputId).keyup(function(){
            clearTimeout(typingTimer);
            var category = $(this).val();
            if(category != ''){
                typingTimer = setTimeout(function() {
                    $.ajax({
                        url : "../controller/commanController.php",
                        type: "POST",
                        data: {category:category, tablename:tablename,  action: actionName, tablefield:tablefield},
                        success: function(data){
                            console.log(data);
                            $("#" + listContainerId).html(data).fadeIn("fast");
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX request failed:", status, error);
                            // Handle errors here
                        }
                    });
                }, doneTypingInterval);
            } else {
                $("#" + listContainerId).fadeOut();
            }
        });
    
        // Handle click on autocomplete list item
        $(document).on('click', '#' + listContainerId + ' li', function() {
            var selectedCategory = $(this).text();
            $("#" + inputId).val(selectedCategory);
            var categoryId = $(this).data("category-id");
                // Set the data-id attribute of the input field
             $("#" + inputId).attr("data-id", categoryId);

            $("#" + listContainerId).fadeOut();
        });

        $(document).on('mouseenter', '#' + listContainerId + ' li', function() {
            $(this).css('cursor', 'pointer');
        });
        
        $(document).on('mouseleave', '#' + listContainerId + ' li', function() {
            $(this).css('cursor', 'auto');
        });
    }
    
   
    
    // Call the setupAutocomplete function with different IDs for different text boxes
    // $(document).ready(function() {
    //     // Example usage:
    //     setupAutocomplete("category1", "category_list1"); // Replace "category1" and "category_list1" with the IDs of your input field and list container respectively
    //     setupAutocomplete("category2", "category_list2"); // Similarly, for the second text box
    //     // Add more setupAutocomplete calls for additional text boxes as needed
    // });
    


});
//End