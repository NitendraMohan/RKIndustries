jQuery.noConflict();
jQuery(document).ready(function ($) {
/**
 * Function for loading complete data of units
 */
    function load_table() {
        $.ajax({
            url: "../controller/subcategoryController.php",
            type: "POST",
            data: { action: "load" },
            success: function (result) {
                $("#subcategoryTableContents").html(result);
                var total_records = $("#subcategoryTableContents tr").length;
                // $('#total_records').html("Total Records: "+total_records);
                $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
        });
    }
    load_table();
   
// var typingTimer;
// var doneTypingInterval = 500; // Adjust the interval as needed
// $("#category").keyup(function(){
//     clearTimeout(typingTimer);
//     var category = $(this).val();
//     if(category != ''){
//         typingTimer = setTimeout(function() {
//             $.ajax({
//                 url : "../controller/subcategoryController.php",
//                 type: "POST",
//                 data: {category:category, action: "categorylist"},
//                 success: function(data){
//                     console.log(data);
//                     $("#category_list").html(data).fadeIn("fast");
//                 },
//                 error: function(xhr, status, error) {
//                     console.error("AJAX request failed:", status, error);
//                     // Handle errors here
//                 }
//             });
//         }, doneTypingInterval);
//     } else {
//         $("#category_list").fadeOut();
//     }
// });

// Handle click on autocomplete list item
// $(document).on('click', '#category_list li', function() {
//     var selectedCategory = $(this).text();
//     $("#category").val(selectedCategory);
//     $("#category_list").fadeOut();
// });




    /**
     * Code for submit model form data
     */
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        var action = "";
        var categoryName = $("#categoryName").val();
        var categoryId = $("#categoryName").data("id");
        var subcategoryName = $("#subcategoryName").val();
        // var status = $("#status").val();
        if (subcategoryName == "" || categoryName == "") {
            $("#msg").fadeIn();
            $("#msg").removeClass('sucess-msg').addClass('error-msg').html('All fields are required.');
            setTimeout(function () {
                $("#msg").fadeOut("slow");
            }, 2000);
        } else {
            // var formData = $('#userForm').serialize() + '&action=insert';
            var formData = new FormData(this);
            var id = $('#subcat').val();
            // console.log('id='.id);
            if(id =='' || id == undefined){
                action = 'insert';
                formData.append("action","insert");
                formData.append("categoryName",categoryName);
                formData.append("categoryId",categoryId)
            }
            else{
                action = 'update';
                formData.append("action","update");
                formData.append("categoryName",categoryName);
                formData.append("categoryId",categoryId)

            }
            $.ajax({
                url: "../controller/subcategoryController.php",
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
                        load_table(); // this function loads the table data
                    } else {
                        $("#msg").fadeIn().removeClass('sucess-msg').addClass('error-msg').html(response.msg);
                    }
                    setTimeout(function () {
                        $("#msg").fadeOut("slow");
                        $("#userForm").trigger("reset");
                        $("#subcat").val('');
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
            url: "../controller/subcategoryController.php",
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
            url: "../controller/subcategoryController.php",
            type: "POST",
            data: { action: uaction, id: uid },
            success: function (result) {
                var arr = JSON.parse(result);
                console.log(arr);
                // console.log(arr['subcategory_name']);
                $("#subcat").val(arr['id']);
                $("#categoryName").val(arr['category_name']);
                $("#categoryName").data("id",arr['category_id'])
                $("#subcategoryName").val(arr['subcategory_name']);
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
            url: "../controller/subcategoryController.php",
        type: "POST",
        data: { action: eventaction, search : search_term },
        success : function(data){
            $("#subcategoryTableContents").html(data);
            var total_records = $("#subcategoryTableContents tr").length;
            // $('#total_records').html("<h6><b>Total Records: "+total_records+"</b></h6>");
            $('#total_records').html("<h6><b style='font-size: 18px;'>Total Records: <span style='color: red;'>"+total_records+"</span></b></h6>");

        }
        });
    });
});
//End