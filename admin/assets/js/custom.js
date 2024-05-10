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

    $("#btnSave").on("click",function(e){
        $("#modalid").val() == ''? save() : update();
        // $(".modalsubmit").data('id') == 'save'? save() : update();
        
    });

    function save(){
        params="";
        let isDuplicateFound = false;
        $('#tableContents tr').each(function(){
            var year_from = $(this).find('td:eq(2)').text();
            if($("#year_from").val() == year_from.trim()) {
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
        if(params != ""){
            params+="&action=create";
            console.log(params);
            $.ajax({
                url: "sessionController.php",
                type: "POST",
                data: params,
                success: function(response){
                    // $("#tableContents").append(response);
                    $("#tableContents").prepend(response);
                    $(".yearlimit").val("");
                    $("#hmsg").text("Record inserted");
                    $("#hmsg").fadeOut(5000);
                }

            })
        }
    }
    function update(){
        params="";
        let isDuplicateFound = false;
        $('#tableContents tr').each(function(){
            var year_from = $(this).find('td:eq(2)').text();
            var current_id = $(this).find('td:eq(1)').text().trim();
            if($("#year_from").val() == year_from.trim() && $("#modalid").val() != current_id) {
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
        if($("#modalid").val()!=''){
            params+=$("#modalid").attr('name')+"="+$("#modalid").val();
        }
        if($("#year_from").val()!=''){
            params+="&"+$("#year_from").attr('id')+"="+$("#year_from").val();
        }
        if($("#year_to").val()!=''){
            params+="&"+$("#year_to").attr('id')+"="+$("#year_to").val();
        }
        if($("#status").val()!=''){
            params+="&"+$("#status").attr('id')+"="+$("#status").val();
        }
        if(params != ""){
            params+="&action=update";
            console.log(params);
            $.ajax({
                url: "sessionController.php",
                type: "POST",
                data: params,
                success: function(response){
                    // $("#tableContents").append(response);
                    // $(".yearlimit").val("");
                    if(response>0)
                    location.reload();
                }

            })
        }
    }
    $("#btnUpdate").on("click",function(e){
        let isDuplicateFound = false;
        // $('#tableContents tr').each(function(){
        //     var year_from = $(this).find('td:eq(2)').text();
        //     if($("#year_from").val() == year_from.trim()){
        //         alert('Error! Duplicate financial year');
        //         isDuplicateFound = true;
        //         return false;
        //     }
        // })
        // if(isDuplicateFound === true) return false;
        // $("td[contentEditable='true']").each(function(){
        //     params+= $(this).data('id')+"="+$(this).text();
        //     if($(this).text()!=""){
        //         params+="&";
        //     }
        // })
        if($("#modalid").val()!=''){
            params+=$("#modalid").attr('name')+"="+$("#modalid").val();
        }
        if($("#year_from").val()!=''){
            params+="&"+$("#year_from").attr('id')+"="+$("#year_from").val();
        }
        if($("#year_to").val()!=''){
            params+="&"+$("#year_to").attr('id')+"="+$("#year_to").val();
        }
        if($("#status").val()!=''){
            params+="&"+$("#status").attr('id')+"="+$("#status").val();
        }
        params+="&action=update";
        if(params != ""){
            console.log(params);
            $.ajax({
                url: "sessionController.php",
                type: "POST",
                data: params,
                success: function(response){
                    location.reload();
                    // $("#tableContents").append(response);
                    // $(".yearlimit").val("");
                }

            })
        }
    });
    $("#tblContents").on("click",".del", function(){
        var el = this;
        var delid = $(this).data('id');
        var confirmalert = confirm("Are you sure?");
        if(confirmalert == true){
            $.ajax({
                url: "sessionController.php",
                type: "POST",
                data: {id: delid, action: "del"},
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
    $("#tblContents").on("click",".edit", function(){
        // $("#tblContents").find('.save').hide();
        // $("#tblContents").find('.cancel').hide();
        // $("#tblContents").find('.edit').show();
        // $(this).hide();
        // $(this).siblings('.save').show();
        // $(this).siblings('.cancel').show();
        var tddata=[];
        $(this).closest('td').siblings().each( function(){
            // var inp = $(this).find('input');
            // if(inp.length){
                // $(this.text($(inp.val())));
            // }
            // else{
                tddata.push($(this).attr("class")+"="+($(this).text().trim()));
                // $(this).attr('contentEditable', true);
            // }
        })
        $.ajax({
            url: "session.php",
            type: "GET",
            data: {
                datatd: JSON.stringify(tddata),
                action: "edit"
            },
            success:function($response){


            }
        });
    })
    $("#tblContents").on("click",".cancel", function(){
        $(this).hide();
        $(this).siblings('.save').hide();
        $(this).siblings('.cancel').hide();
        $(this).siblings('.edit').show();
        $(this).closest('td').siblings().each( function(){
            
                $(this).attr('contentEditable', false);
                location.reload();
            
        })
    })
})