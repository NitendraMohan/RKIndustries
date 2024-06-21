jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/saleOrderController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                $("#saleOrderTableContents").html(result);
                var total_records = $("#saleOrderTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");
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
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var bill_no = $("#bill_no").val();
        var userstatus = $("#status").val();
        if (bill_no == "" || userstatus == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            // var formData = $('#userForm').serialize() + '&action=insert';
            var formData = new FormData(this);
            var id = $('#userHiddenId').val();
            console.log('id='.id);
            if(id =='' || id == undefined){
                action = 'insert';
                formData.append("action","insert");
            }
            else{
                action = 'update';
                formData.append("action","update");
            }
            $.ajax({
                url: "../controller/saleOrderController.php",
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
                        $("#userHiddenId").val('');
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
            url: "../controller/saleOrderController.php",
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
            url: "../controller/saleOrderController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                console.log(arr['username']);
                $("#userHiddenId").val(arr['id']);
                $("#party_id").val(arr['party_id']);
                $("#bill_no").val(arr['bill_no']);
                $("#voucher_no").val(arr['voucher_no']);
                $("#order_date").val(arr['order_date']);
                $("#delivery_date").val(arr['delivery_date']);
                $("#payment_mode").val(arr['payment_mode']);
                $("#delivery_address").val(arr['delivery_address']);
                $("#terms").val(arr['terms']);
                $("#other_detail").val(arr['other_detail']);
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
            url: "../controller/saleOrderController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#saleOrderTableContents").html(data);
            var total_records = $("#saleOrderTableContents tr").length;
            // $('#total_records').html("<h6><b>Total Records: "+total_records+"</b></h6>");
            $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");

        }
        });
    });
});
//End
//show sale order products
$(document).on("click", ".showProducts", function () {

    var sid = $(this).data("id");
    console.log(sid);
    // var uaction = "show_material";
    // var element = this;
    link = '../view/saleOrderProductsView.php';
    $.ajax({
        url: link,
        type: "POST",
        data: {
           saleorder_id: sid,
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