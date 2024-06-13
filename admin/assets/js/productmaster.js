jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */

    function load_table() {
        $.ajax({
            url: "../controller/productsController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                $("#productsTableContents").html(result);
                var total_records = $("#productsTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    $('#image').on('change', function(){
        var file = this.files[0]; // Get the selected file
        if (file) {
            var reader = new FileReader(); // Create a new FileReader object
            reader.onload = function(e) {
                $('#logo_image').attr('src', e.target.result); // Set the src attribute of the image with the data URL of the selected file
            };
            reader.readAsDataURL(file); // Read the selected file as a data URL
        }
    });

    // $("#category").on("change", function(e){
    //     e.preventDefault();
    //     var category = $(this).val();
    //     $("#subcategory").html("<option value='' selected>Select..</option>");
    //     console.log(category);
    //     // if(category!==''){
    //         $.ajax({
    //             url: "../controller/productsController.php",
    //             type: "POST",
    //             data: { action: 'load_subcategories', category_id: category },
    //             success: function (result) {
    //                 $("#subcategory").html(result);
    //             }
    //         });
    //     // }
    // })
    /**
     * Code for submit model form data
     */
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var productname = $("#productname").val();
        var category = $("#categoryName").val();
        var categoryId = $("#categoryName").data("id");
        var subcategory = $("#subcategoryInput").val();
        var subcategoryId = $("#subcategoryInput").data("id");
        var userstatus = $("#status").val();
        if (productname == "" || userstatus == "" || category == "" || subcategory == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            // var formData = $('#userForm').serialize() + '&action=insert';
            var formData = new FormData(this);
            var id = $('#productHiddenId').val();
            console.log('id='.id);
            if(id =='' || id == undefined){
                action = 'insert';
                formData.append("action","insert");
                formData.append("categoryId",categoryId);
                formData.append("subcategoryId",subcategoryId);
            }
            else{
                action = 'update';
                var currentImage =  $("#logo_image").attr('src') ?? '';
                formData.append("image",currentImage);
                formData.append("action","update");
                formData.append("categoryId",categoryId);
                formData.append("subcategoryId",subcategoryId);
            }
            $.ajax({
                url: "../controller/productsController.php",
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
                        $("#productHiddenId").val('');
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
            url: "../controller/productsController.php",
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
        console.log("product id "+ uid);
        $.ajax({
            url: "../controller/productsController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                console.log(result);
                var arr = JSON.parse(result);
                $("#productHiddenId").val(arr['id']);
                $("#logo_image").attr('src',arr['image']);
                $("#brandId").val(arr['brand_id']);
                $("#categoryName").val(arr['category_name']);
                $("#categoryName").data("id",arr['category_id']);
                $("#subcategoryInput").val(arr['subcategory_name']);
                $("#subcategoryInput").data("id",arr['subcategory_id']);
                $("#productname").val(arr['product_name']);
                $("#productCodeId").val(arr['product_code']);
                $("#unit").val(arr['unit_id']);
                $("#minLimitId").val(arr['min_limit']);
                $("#maxLimitId").val(arr['max_limit']);
                $("#price").val(arr['price']);
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
            url: "../controller/productsController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#productsTableContents").html(data);
            var total_records = $("#productsTableContents tr").length;
            // $('#total_records').html("<h6><b>Total Records: "+total_records+"</b></h6>");
            $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");

        }
        });
    });
});
//End