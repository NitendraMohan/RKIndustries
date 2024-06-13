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

    function setupAutocomplete(inputId, listContainerId, actionName, tablename, tablefield, onItemSelected) {
        var typingTimer;
        var doneTypingInterval = 200;
    
        $("#" + inputId).keyup(function() {
            clearTimeout(typingTimer);
            var searchValue = $(this).val();
            if (searchValue != '') {
                typingTimer = setTimeout(function() {
                    $.ajax({
                        url: "../controller/commanController.php",
                        type: "POST",
                        data: {
                            searchValue: searchValue,
                            tablename: tablename,
                            action: actionName,
                            tablefield: tablefield
                        },
                        success: function(data) {
                            console.log(data);
                            $("#" + listContainerId).html(data).fadeIn("fast");
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX request failed:", status, error);
                        }
                    });
                }, doneTypingInterval);
            } else {
                $("#" + listContainerId).fadeOut();
            }
        });
    
        // Handle click on autocomplete list item
        $(document).on('click', '#' + listContainerId + ' li', function() {
            var selectedItem = $(this).text();
            $("#" + inputId).val(selectedItem);
            var itemId = $(this).data("id");
    
            if ($("#" + inputId).attr("data-id") == undefined) {
                $("#" + inputId).data("id", itemId);
            } else {
                $("#" + inputId).attr("data-id", itemId);
            }
    
            $("#" + listContainerId).fadeOut();
    
            if (typeof onItemSelected === 'function') {
                onItemSelected(itemId);
            }
        });
    
        $(document).on('mouseenter', '#' + listContainerId + ' li', function() {
            $(this).css('cursor', 'pointer');
        });
    
        $(document).on('mouseleave', '#' + listContainerId + ' li', function() {
            $(this).css('cursor', 'auto');
        });
    }
    
    function setupSubcategoryAutocomplete(categoryId, inputId, listContainerId, actionName, tablename, tablefield) {
        var typingTimer;
        var doneTypingInterval = 200;
    
        $("#" + inputId).keyup(function() {
            clearTimeout(typingTimer);
            var subcategory = $(this).val();
            if (subcategory != '') {
                typingTimer = setTimeout(function() {
                    $.ajax({
                        url: "../controller/commanController.php",
                        type: "POST",
                        data: {
                            query: subcategory,
                            categoryId: categoryId,
                            tablename: tablename,
                            action: actionName,
                            tablefield: tablefield
                        },
                        success: function(data) {
                            console.log(data);
                            $("#" + listContainerId).html(data).fadeIn("fast");
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX request failed:", status, error);
                        }
                    });
                }, doneTypingInterval);
            } else {
                $("#" + listContainerId).fadeOut();
            }
        });
    
        $(document).on('click', '#' + listContainerId + ' li', function() {
            var selectedSubcategory = $(this).text();
            $("#" + inputId).val(selectedSubcategory);
            var subcategoryId = $(this).data("id");
    
            if ($("#" + inputId).attr("data-id") == undefined) {
                $("#" + inputId).data("id", subcategoryId);
            } else {
                $("#" + inputId).attr("data-id", subcategoryId);
            }
    
            $("#" + listContainerId).fadeOut();
        });
    
        $(document).on('mouseenter', '#' + listContainerId + ' li', function() {
            $(this).css('cursor', 'pointer');
        });
    
        $(document).on('mouseleave', '#' + listContainerId + ' li', function() {
            $(this).css('cursor', 'auto');
        });
    }
    
    $(document).ready(function() {
        setupAutocomplete("categoryName", "category_list", "list", "tbl_category", "category_name", function(categoryId) {
            setupSubcategoryAutocomplete(categoryId, 'subcategoryInput', 'subcategoryList', 'list', 'tbl_subcategory', 'subcategory_name');
        });
       
        setupAutocomplete("brandName", "brand_list", "list", "tbl_brand", "brand_name");
       
        setupAutocomplete("productName", "product_list", "list", "tbl_products", "product_name");
    });

});
//End