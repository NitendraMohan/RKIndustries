jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        var saleorder_id = $('#saleorder_id').val();
        console.log('saleorder id : '+saleorder_id);
        $.ajax({
            url: "../controller/saleOrderProductsController.php",
            type: "POST",
            data: { action: "load", saleorder_id: saleorder_id },
            success: function (result) {
                console.log(result);
                data = JSON.parse(result);
                var sale_order = data['sale_order'];
                $('#party_name').text(sale_order['party_name']);
                $('#bill_no').text(sale_order['bill_no']);
                $('#order_date').text(sale_order['order_date']);
                $('#delivery_date').text(sale_order['delivery_date']);
                $('#title_total_cost').text('Rs. '+sale_order['total_cost']);
                $("#saleOrderProductsTableContents").html(data['sale_products']);
                var total_records = $("#saleOrderProductsTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    $("#tax_id").on("change", function(e){
        e.preventDefault();
        var selectedOption = $('#tax_id option:selected');
        var tax_perc = selectedOption.text();
        var cost = $("#cost").val();
        var tax_amt = parseFloat(cost * tax_perc)/100.00;
        console.log(tax_amt);
        typeof tax_amt == 'number' && $("#tax_amt").val(tax_amt.toFixed(2));
        var total_cost = parseFloat(cost) + parseFloat(tax_amt);
        typeof total_cost == 'number' && $("#total_cost").val(total_cost.toFixed(2));
    })

    $("#category").on("change", function(e){
        e.preventDefault();
        var category = $(this).val();
        $("#subcategory").html("<option value='' selected>Select..</option>");
        console.log(category);
        // if(category!==''){
            $.ajax({
                url: "../controller/saleOrderProductsController.php",
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
        $("#product").html("<option value='' selected>Product..</option>");
        console.log(category);
        // if(category!==''){
            $.ajax({
                url: "../controller/saleOrderProductsController.php",
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
        var productid = $(this).val();
        // $("#munit").html("<option value='' selected>Unit..</option>");
        console.log(category);
        // if(category!==''){
            $.ajax({
                url: "../controller/saleOrderProductsController.php",
                type: "POST",
                data: { action: 'load_rateunit', product_id: productid },
                success: function (result) {
                    var data = JSON.parse(result);
                    $("#munit").val(data['unit_id']);
                    $("#mrate").val(data['price']);
                    $("#mqty").val(1);
                    $("#cost").val(data['price']);
                    $("#tax_amt").val(0.00);
                    $("#total_cost").val(data['price']);
                }
            });
        // }
    })


    $("#mqty").on('input', function(){
        var price = $("#mrate").val();
        var qty = $("#mqty").val();
        if(isNaN(price) || isNaN(qty)){return;}
        var cost = price * qty;
        if (!isNaN(cost)) {
            $("#cost").val(cost.toFixed(2));
        }
        var tax_amt = $("#tax_amt").val();
        var selectedOption = $('#tax_id option:selected');
        var tax_perc = selectedOption.text();
        if(!isNaN(tax_perc)){
            var tax_amt = parseFloat(cost * tax_perc)/100.00;
            typeof tax_amt == 'number' && $("#tax_amt").val(tax_amt.toFixed(2));
        }
        var tax_amt = $("#tax_amt").val();
        if (!isNaN(tax_amt)) {
            var total_cost= parseFloat(cost) + parseFloat(tax_amt);
            $("#total_cost").val(total_cost.toFixed(2));
        }
    })
    
    $("#mrate").on('input', function(){
        var price = $("#mrate").val();
        var qty = $("#mqty").val();
        if(isNaN(price) || isNaN(qty)){return;}
        var cost = price * qty;
        if (!isNaN(cost)) {
            $("#cost").val(cost.toFixed(2));
        }
        var tax_amt = $("#tax_amt").val();
        var selectedOption = $('#tax_id option:selected');
        var tax_perc = selectedOption.text();
        if(!isNaN(tax_perc)){
            var tax_amt = parseFloat(cost * tax_perc)/100.00;
            typeof tax_amt == 'number' && $("#tax_amt").val(tax_amt.toFixed(2));
        }
        if (!isNaN(tax_amt)) {
            var total_cost= parseFloat(cost) + parseFloat(tax_amt);

            $("#total_cost").val(total_cost.toFixed(2));
        }
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
        if (productname == "" || category == "" || subcategory == "") {
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
                // var currentImage =  $("#logo_image").attr('src') ?? '';
                // formData.append("image",currentImage);
                formData.append("action","update");
            }
            $.ajax({
                url: "../controller/saleOrderProductsController.php",
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
            url: "../controller/saleOrderProductsController.php",
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
            url: "../controller/saleOrderProductsController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                var cat_id = arr['category_id']; 
                $("#category").val(arr['category_id']);
                $.ajax({
                    url: "../controller/saleOrderProductsController.php",
                    type: "POST",
                    data: { action: 'load_subcategories', category_id: cat_id },
                    success: function (list) {
                        $("#subcategory").html(list);
                        $("#subcategory").val(arr['subcategory_id']);
                        $.ajax({
                            url: "../controller/saleOrderProductsController.php",
                            type: "POST",
                            data: { action: 'load_products', subcategory_id: arr['subcategory_id'] },
                            success: function (product_list) {
                                $("#product").html(product_list);
                                $("#product").val(arr['product_id']);
                            }
                        });
                    }
                });
                $("#modalid").val(arr['id']);
                $("#brand").val(arr['brand_id']);
                $("#munit").val(arr['unit_id']);
                $("#mrate").val(arr['rate']);
                $("#mqty").val(arr['qty']);
                $("#cost").val(arr['cost']);
                $("#tax_id").val(arr['tax_id']);
                $("#tax_amt").val(arr['tax_amt']);
                $("#total_cost").val(arr['total_cost']);
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
            url: "../controller/saleOrderProductsController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#saleOrderProductsTableContents").html(data);
            var total_records = $("#saleOrderProductsTableContents tr").length;
            // $('#total_records').html("<h6><b>Total Records: "+total_records+"</b></h6>");
            $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");

        }
        });
    });

    //show material records
    $(document).on("click", ".showMaterials", function () {

        var bomid = $(this).data("bomid");
        var saleorder_id = $('#saleorder_id').val();
        var product_id = $(this).data("id");
        console.log(bomid);
        // var uaction = "show_material";
        // var element = this;
        link = '../view/checkMaterialStockView.php';
        $.ajax({
            url: link,
            type: "POST",
            data: {
               bomid: bomid,
               saleorder_id: saleorder_id,
               product_id: product_id
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