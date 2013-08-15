var filterTimeoutProc = false;

$("")



function loadMaterial(material_id){
    loadFunc = function(){
        clearInputs("#material_center input, #material_center textarea, #material_center select");

        $.ajax({
            url: uri+"/submitted/loadMaterial",
            type: 'GET',
            dataType: 'json',
            data: {material_id: material_id},
            async: true,
            success: function(ret){
                if(ret != -1){
                    hideStatus();
                    procReturn(ret);
                } else {
                    showStatus("Error on loading content.");
                }
            }
        });
    };

    showStatus('Loading...',false, loadFunc);
}



/*
function delMaterial() {
    
    if($("#material__material_id").val()){

        var del = confirm("Delete This Content?");
        if(del){

            data = {
                'cls': 'material',
                'id' : $("#material__material_id").val()
            };

            $.ajax({
                url: uri+"/svc/delete",
                type: 'POST',
                dataType: 'json',
                data: data,
                async: true,
                success: function(ret){
                    if(ret != -1){
                        //remove from material list
                        $("#"+$("#material__material_id").val()).remove();
                        //clear all the fields
                        clearInputs("#submitted_center input,#submitted_center select,#submitted_center textarea");
                        $("#submitted_form").css('display','none');
                        showStatus("Content deleted.");
                    } else {
                        showStatus("Error on deleting content.");
                    }
                }
            });
        }
    }
}*/


function saveMaterial(){
    saveFunc = function(){
        //If there are "book_review" fields to save
        if($(".dirty_input.book_review").length != 0){
                var data = {
                    id : $("#book_review__book_review_id").val(),
                    cls : "bookreview",
                    data : {}
                };
                var dirty = $(".dirty_input.book_review");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("book_review__","")] = $(dirty[i]).val();    
                }

                if($("#book_review__book_review_id").val()){
                    $.ajax({
                        url: uri+"/svc/update",
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        async: true,
                        success: function(ret){
                            if(ret != -1){
                                showStatus("Successfully Saved!");
                            } else {
                                showStatus("Error on saving content.");
                            }
                        }
                    });
                } else {
                    data.data['book_or_material'] = 1;
                    data.data['book_id'] = $("#material__material_id").val();

                    $.ajax({
                        url: uri+"/svc/create",
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        async: true,
                        success: function(ret){
                            if(ret != -1){
                                showStatus("Successfully Saved!");
                            } else {
                                showStatus("Error on saving content.");
                            }
                        }
                    });
                }


        }


        //If there are "material" fields to save
        if($(".dirty_input.material").length != 0 && $("#material__material_id").val()){
            var data = {
                id : $("#material__material_id").val(),
                cls : "material",
                data : {}
            };
            var dirty = $(".dirty_input.material");
            if($("#material__author_is_submitter").is(".dirty_input") && $("#material__author_is_submitter").is(":checked")){
                $("#material__submitter_name").val($("#material__author_name").val());
                $("#material__submitter_email").val($("#material__author_email").val());
            }
            if($("#material__screen_status").is(".dirty_input") && $("#material__screen_status").val() != 4){
                $("#material__ae_id").val("");
            }
            for(var i = 0; i < dirty.length; i++){
                if($("#material__author_is_submitter").is(':checked') && dirty[i].id == "material__auther_name" || dirty[i].id == "material__author_email"){
                    data.data[dirty[i].id.replace("material__","")] = $("#material__submitter_"+dirty[i].id.replace("material__author_","")).val();
                } else {
                    data.data[dirty[i].id.replace("material__","")] = $(dirty[i]).val();    
                }
            }
            $.ajax({
                url: uri+"/svc/update",
                type: 'POST',
                dataType: 'json',
                data: data,
                async: true,
                success: function(ret){
                    if(ret != -1){
                        showStatus("Successfully Saved!"); 
                    } else {
                        showStatus("Error on saving content.");
                    }   
                }
            });
        }

        //Remove dirt
        clearDirty("#submitted_center input,#submitted_center select,#submitted_center textarea"); 
    };

    showStatus("Saving...",false, saveFunc);
}



function filterMaterial(){
    if(filterTimeoutProc != false) clearTimeout(filterTimeoutProc);
    filterTimeoutProc = setTimeout(execFilter,250);
}

function execFilter(){
    filterTimeoutProc = false;

    var filter = $("#submitted_filter").val();


    if(filter != ""){
        $("#submitted_list ul").html('');
        
        var parts = filter.split(" ");

        var query = [];
        for(var p in parts){
            if(parts[p] != ""){
                var l = [['submitter_name','like','%'+parts[p]+'%'],
                         'OR',
                         ['submitter_email','like','%'+parts[p]+'%'],
                         'OR',
                         ['title','like','%'+parts[p]+'%'],
                         'OR',
                         ['description','like','%'+parts[p]+'%'],
                         'OR',
                         ['author_name','like','%'+parts[p]+'%'],
                         'OR',
                         ['author_email','like','%'+parts[p]+'%']];
                query.push(l);
                query.push('AND');
            }
        }
        
        if(!$("#submitted_unscreened").is(":checked")){
            var l = ['screen_status','=',1];    
            query.push(l);
        } else {
            query.splice(query.length-1,1);
        }

        $.ajax({
            url: uri+"/svc/read",
            type: 'GET',
            dataType: 'json',
            data: {
                'cls'    : 'material',
                'query'  : query,
                'select' : ['title','author_name', 'screen_status']
            },
            async: true,
            success: function(ret){
                ret = ret.results;
                for(var idx in ret){
                   r = ret[idx];
                   li = document.createElement("li");
                   li.id = r.material_id;

                   if(r.screen_status == 1) text = "";
                   else if(r.screen_status == 2) text = "(Reject) ";
                   else if(r.screen_status == 3) text = "(Distribute) ";
                   else if(r.screen_status == 4) text = "(Assign) ";

                   var res = text+r.title+" - "+r.author_name;
                   var filter = $("#submitted_filter").val();
                   if(filter != ""){
                        var parts = filter.split(" ");
                        for(var p in parts){
                            if(parts[p] != ""){
                                res = res.replace(parts[p],"<strong>"+parts[p]+"</strong>");
                            }
                        }
                   }

                   li.innerHTML = res;
                   $(li).bind("click",function(){
                        $("#submitter_list li").each(function(idx,el){
                            $(el).removeClass("selected");
                        });

                        $(this).addClass("selected");
                        loadMaterial(this.id);
                    });
                   $("#submitted_list ul").append(li);
                }
            }
        });
    } else {
        $("#submitted_list li").each(function(idx, el){
            $(el).remove();
        });
    }
}

