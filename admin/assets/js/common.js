jQuery.noConflict();
jQuery(document).ready(function ($) {
    $(document).on("click", ".modalClose", function () {
        console.log('close button triggered');
        $("#userForm").trigger("reset");
        $("#modelid").val('');
    });
});
//End