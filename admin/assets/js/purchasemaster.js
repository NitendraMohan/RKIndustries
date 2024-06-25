jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/purchaseController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                $("#purchaseTableContents").html(result);
                var total_records = $("#purchaseTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

   function handelBlur(){
        var cost = parseFloat($("#costId").val()) || 0;
        var tax = parseFloat($("#taxId").val()) || 0;
        var totalCost = cost + tax;
        $("#totalCostId").val(totalCost);
   }

   $("#costId, #taxId").blur(handelBlur);
      
    /**
     * Code for submit model form data
     */
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var billno = $("#billNumberId").val();
        var vendor = $("#vendorId").val();
        // var userstatus = $("#status").val();
        if (billno == "" || vendor == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            // var formData = $('#userForm').serialize() + '&action=insert';
            var formData = new FormData(this);
            var id = $('#purchaseHiddenId').val();
            console.log('id='.id);
            if(id =='' || id == undefined){
                action = 'insert';
                formData.append("action","insert");
            }
            else{
                action = 'update';
                // var currentImage =  $("#logo_image").attr('src') ?? '';
                // formData.append("image",currentImage);
                formData.append("action","update");
            }
            $.ajax({
                url: "../controller/purchaseController.php",
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
                        $("#modelid").val('');
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
            url: "../controller/purchaseController.php",
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
        // console.log("product id "+ uid);
        $.ajax({
            url: "../controller/purchaseController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                console.log(arr);
                // $.ajax({
                //     url: "../controller/purchaseController.php",
                //     type: "POST",
                //     data: { action: 'load_subcategories', category_id: cat_id },
                //     success: function (list) {
                //         $("#subcategory").html(list);
                //         $("#subcategory").val(arr['subcategory_id']);
                //         $.ajax({
                //             url: "../controller/purchaseController.php",
                //             type: "POST",
                //             data: { action: 'load_products', subcategory_id: arr['subcategory_id'] },
                //             success: function (product_list) {
                //                 $("#product").html(product_list);
                //                 $("#product").val(arr['product_id']);
                //                 $.ajax({
                //                     url: "../controller/purchaseController.php",
                //                     type: "POST",
                //                     data: { action: 'load_brands', product_id: arr['product_id'] },
                //                     success: function (result) {
                //                         $("#brand").html(result);
                //                         $("#brand").val(arr['brand_id']);
                //                     }
                //                 });
                //             }
                //         });
                //     }
                // });
                $("#purchaseHiddenId").val(arr['id']);
                $("#billNumberId").val(arr['billno']);
                $("#vendorId").val(arr['vendorid']);
                $("#costId").val(arr['cost']);
                $("#taxId").val(arr['tax_amount']);
                $("#totalCostId").val(arr['total_cost']);
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
            url: "../controller/purchaseController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#purchaseTableContents").html(data);
            var total_records = $("#purchaseTableContents tr").length;
            // $('#total_records').html("<h6><b>Total Records: "+total_records+"</b></h6>");
            $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");

        }
        });
    });

    //show material records
    $(document).on("click", ".purchaseitem", function () {

        var uid = $(this).data("id");
        console.log(uid);
        // var uaction = "show_material";
        // var element = this;
        link = '../view/purchaseItemsView.php';
        $.ajax({
            url: link,
            type: "POST",
            data: {
               purchaseId: uid,
            },
            success: function() {
               window.location.href = link;
            },
            error: function(xhr, status, error) {
               console.error("AJAX request failed:", status, error);
            }
         });
    });
    //End


    //show charges records
    $(document).on("click", ".charges", function () {

        var uid = $(this).data("id");
        console.log(uid);
        // var uaction = "show_material";
        // var element = this;
        link = '../view/bomOtherChargesView.php';
        $.ajax({
            url: link,
            type: "POST",
            data: {
               bomid: uid,
            },
            success: function() {
               window.location.href = link;
            },
            error: function(xhr, status, error) {
               console.error("AJAX request failed:", status, error);
            }
         });
    });
    //End
});
//End