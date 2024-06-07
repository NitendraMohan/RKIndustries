jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/bomsController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                $("#bomsTableContents").html(result);
                var total_records = $("#bomsTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    $("#category").on("change", function(e){
        e.preventDefault();
        var category = $(this).val();
        $("#subcategory").html("<option value='' selected>Select..</option>");
        console.log(category);
        // if(category!==''){
            $.ajax({
                url: "../controller/bomsController.php",
                type: "POST",
                data: { action: 'load_subcategories', category_id: category },
                success: function (result) {
                    $("#subcategory").html(result);
                }
            });
        // }
    })

    $("#subcategory").on("change", function(e){
        e.preventDefault();
        var subcategory = $(this).val();
        $("#product").html("<option value='' selected>Select..</option>");
        console.log(category);
        // if(category!==''){
            $.ajax({
                url: "../controller/bomsController.php",
                type: "POST",
                data: { action: 'load_products', subcategory_id: subcategory },
                success: function (result) {
                    $("#product").html(result);
                }
            });
        // }
    })
    $("#product").on("change", function(e){
        e.preventDefault();
        var product = $(this).val();
        $("#brand").html("<option value='' selected>Select..</option>");
        console.log(category);
        // if(category!==''){
            $.ajax({
                url: "../controller/bomsController.php",
                type: "POST",
                data: { action: 'load_brands', product_id: product },
                success: function (result) {
                    $("#brand").html(result);
                }
            });
        // }
    })
    /**
     * Code for submit model form data
     */
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var productname = $("#productname").val();
        var category = $("#category").val();
        var subcategory = $("#subcategory").val();
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
            var id = $('#modalid').val();
            console.log('id='.id);
            if(id =='' || id == undefined){
                action = 'insert';
                formData.append("action","insert");
            }
            else{
                action = 'update';
                var currentImage =  $("#logo_image").attr('src') ?? '';
                formData.append("image",currentImage);
                formData.append("action","update");
            }
            $.ajax({
                url: "../controller/bomsController.php",
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
            url: "../controller/bomsController.php",
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
            url: "../controller/bomsController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                var cat_id = arr['category_id']; 
                console.log('category id:'+ cat_id);
                console.log(arr['product_name']);
                $.ajax({
                    url: "../controller/bomsController.php",
                    type: "POST",
                    data: { action: 'load_subcategories', category_id: cat_id },
                    success: function (list) {
                        $("#subcategory").html(list);
                        $("#subcategory").val(arr['subcategory_id']);
                        $.ajax({
                            url: "../controller/bomsController.php",
                            type: "POST",
                            data: { action: 'load_products', subcategory_id: arr['subcategory_id'] },
                            success: function (product_list) {
                                $("#product").html(product_list);
                                $("#product").val(arr['product_id']);
                                $.ajax({
                                    url: "../controller/bomsController.php",
                                    type: "POST",
                                    data: { action: 'load_brands', product_id: arr['product_id'] },
                                    success: function (result) {
                                        $("#brand").html(result);
                                        $("#brand").val(arr['brand_id']);
                                    }
                                });
                            }
                        });
                    }
                });
                $("#modalid").val(arr['id']);
                $("#logo_image").attr('src',arr['image']);
                $("#bomname").val(arr['bom_name']);
                $("#category").val(arr['category_id']);
                $("#brand").val(arr['brand_id']);
                $("#unit").val(arr['unit_id']);
                $("#qty").val(arr['qty']);
                $("#detail").val(arr['detail']);
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
            url: "../controller/bomsController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#bomsTableContents").html(data);
            var total_records = $("#bomsTableContents tr").length;
            // $('#total_records').html("<h6><b>Total Records: "+total_records+"</b></h6>");
            $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");

        }
        });
    });

    //show material records
    $(document).on("click", ".material", function () {

        var uid = $(this).data("id");
        console.log(uid);
        // var uaction = "show_material";
        // var element = this;
        link = '../view/bommaterialsView.php';
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