$(document).ready(function () {
    $(".yearlimit").on("input",function(){

        var value= $(this).val();
        if(value.length > 4){
            $(this).val(value.slice(0, 4));
        }
        if(value > $(this).prop("max")){
            $(this).val($(this).prop("max"));
        }
        if(value < $(this).attr("min")){
            $(this).val($(this).prop("min"));
        }
    });
    $("#year_from").on("blur",function(){
        var value= $(this).val();
        value = Number.parseInt(value)+1;
        $("#year_to").val(value);
    });
    // function limitYearInput(input){
    //     if(input.value.length >4){
    //         input.value = input.value.slice(0,4);
    //     }
    // }
    // placeholders = ['starting year','ending year', 'enter status'];
    // $("td[contentEditable='true']").each(function(index){
    //     $(this).text(placeholders[index]);
    //     $(this).on('focus',()=>{
    //         if($(this).text() === placeholders[index]){
    //             $(this).text('');
    //             // $(this).removeClass('placeholder');
    //         }
    //     })
    //     $(this).on('blur',()=>{
    //         if($(this).text().trim() === ''){
    //             $(this).text(placeholders[index]);
    //             // $(this).addClass('placeholder');
    //         }
    //     })
    // })

    params="";
    $("#btnSave").on("click",function(e){
        let isDuplicateFound = false;
        $('#tableContents tr').each(function(){
            var year_from = $(this).find('td:eq(2)').text();
            if($("#year_from").val() == year_from.trim()){
                alert('Error! Duplicate financial year');
                isDuplicateFound = true;
                return false;
            }
        })
        if(isDuplicateFound === true) return false;
        // $("td[contentEditable='true']").each(function(){
        //     params+= $(this).data('id')+"="+$(this).text();
        //     if($(this).text()!=""){
        //         params+="&";
        //     }
        // })
        if($("#year_from").val()!=''){
            params+=$("#year_from").attr('id')+"="+$("#year_from").val();
        }
        if($("#year_to").val()!=''){
            params+="&"+$("#year_to").attr('id')+"="+$("#year_to").val();
        }
        if($("#status").val()!=''){
            params+="&"+$("#status").attr('id')+"="+$("#status").val();
        }
        params+="&action=create";
        if(params != ""){
            console.log(params);
            $.ajax({
                url: "sessionController.php",
                type: "POST",
                data: params,
                success: function(response){
                    $("#tableContents").append(response);
                    $(".yearlimit").val("");
                }

            })
        }
    });
    $("#tblContents").on("click",".del", function(){
        var el = this;
        var delid = $(this).data['id'];
        var confirmalert = confirm("Are you sure?");
        if(confirmalert == true){
            $.ajax({
                url: "sessionController.php",
                type: "POST",
                data: {
                    id: delid,
                    action: "del"
                },
                success: function(response){
                    if(response == true){
                        $(el).closest('tr').css('background','tomato');
                        $(el).closest('tr').fadeOut(800, function(){
                            $(this).remove();
                        })
                    }
                    else{
                        alert('record not deleted');
                    }
                }

            })
        }
    })
})