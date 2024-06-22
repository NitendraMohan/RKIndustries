jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        var bomid = $('#bomid').val();
        console.log('bom id : '+bomid);
        $.ajax({
            url: "../controller/bomOtherChargesController.php",
            type: "POST",
            data: { action: "load", bomid: bomid },
            success: function (result) {
                data = JSON.parse(result);
                console.log(data);
                var bomdata = data['bom_data'];
                $('#bomname').text(bomdata['product_name']);
                $('#brandname').text(bomdata['brand_name']);
                // $('#product_image').attr('src',bomdata['image']); //mcost
                $('#materialcost').text(bomdata['mcost']);
                $('#othercost').text(bomdata['ocost']);
                $('#totalcost').text(bomdata['total_cost']);
                $("#usersTableContents").html(data['charges_data']);
                var total_records = $("#usersTableContents tr").length;
                $('#total_records').html("Total Records: "+total_records);
                
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();

    $(document).on("click", ".btn_toggle", function(){
        load_table();
    });

    $('#selected_user').change(function() {
        load_table();
    });
    /**
     * Code for submit model form data
     */
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var username = $("#expanse_name").val();
        var userstatus = $("#status").val();
        if (username == "" || userstatus == "") {
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
                formData.append("action","update");
            }
            $.ajax({
                url: "../controller/bomOtherChargesController.php",
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
            url: "../controller/bomOtherChargesController.php",
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
            url: "../controller/bomOtherChargesController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                $("#modalid").val(arr['id']);
                $("#expanse_name").val(arr['charge_id']);
                $("#is_percentage").val(arr['is_percentage']);
                if(arr['is_percentage']==1){
                    $("#is_percentage").prop('checked',true);
                }
                else{
                    $("#is_percentage").prop('checked',false);
                }
                $("#apply_on_material").val(arr['apply_on_material']);
                if(arr['apply_on_material']==1){
                    $("#apply_on_material").prop('checked',true);
                }
                else{
                    $("#apply_on_material").prop('checked',false);
                }
                $("#charge_value").val(arr['charge_value']);
                $("#status").val(arr['status']);
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
            url: "../controller/bomOtherChargesController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#usersTableContents").html(data);
            var total_records = $("#usersTableContents tr").length;
            $('#total_records').html("Total Records: "+total_records);
        }
        });
    });
    $(document).on("change", ".chkBox", function () {
        var $row = $(this).closest('tr');
        var charge_id = $row.find(".charge_id").val();
        var is_applicable = $row.find("#is_applicable").is(":checked")?1:0;
        var is_percentage = $row.find("#is_percentage").is(":checked")?1:0;
        var apply_on_material = $row.find("#apply_on_material").is(":checked")?1:0;
        var charge_value = $row.find("#charge_value").val();
        var bom_id = $('#bomid').val();
        // console.log('user id:'+bom_id+',module id:'+charge_id+',insert:'+insert_status+',update:'+update_status+',delete:'+delete_status);
        $.ajax({
            url: "../controller/bomOtherChargesController.php",
        type: "POST",
        data: { action: 'checkbox_submit', 
                bom_id: bom_id, 
                charge_id : charge_id, 
                is_applicable: is_applicable,
                is_percentage: is_percentage,
                apply_on_material: apply_on_material,
                charge_value: charge_value
            },
        success : function(data){
            console.log(data);
            load_table();
        }
        });
    });
});


//End