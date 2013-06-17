var filterTimeoutProc = false;


function loadPerson(person_id){
    loadFunc = function(){
        clearInputs("#people_center input, #people_center textarea, #people_center select");

        $.ajax({
            url: uri+"/people/loadPerson",
            type: 'GET',
            dataType: 'json',
            data: {person_id: person_id},
            async: true,
            success: function(ret){
                if(ret != -1){
                    hideStatus();
                    procReturn(ret);
                } else {
                    showStatus("Error on loading person.");
                }
            }
        });
    };

    showStatus('Loading...',false, loadFunc);
}

function addPerson() {
    showStatus("Creating Person", false, function(){
        var d = new Date();

        var res = $.ajax({
                url: uri+"/svc/create",
                type: "POST",
                dataType: 'json',
                data: {
                    cls: "Person",
                    data: {
                        last_name : "Person",
                        first_name: "New",
                        role_id : 4,
                        institution: "Enter an Institution",
                        department: "Enter a Department"
                    }
                },
                async: false
            });

        if(res != -1){
            eval("res = "+res.responseText);

            showStatus("Person Created");
            loadPerson(res.person_id);
        } else {
            showStatus("Person Creation Failed.");
        }
    });

}

/*
function delPerson() {
    
    if($("#person__person_id").val()){

        var del = confirm("Delete This Person?");
        if(del){

            data = {
                'cls': 'person',
                'id' : $("#person__person_id").val()
            };

            /** 
             * NOTE: the PHP Person class takes care
             * of data integrity, deleting all other
             * references and data associated
             * with this person.
             */
             /*
            $.ajax({
                url: uri+"/svc/delete",
                type: 'POST',
                dataType: 'json',
                data: data,
                async: true,
                success: function(ret){
                    if(ret != -1){
                        //remove from people list
                        $("#"+$("#person__person_id").val()).remove();
                        //clear all the fields
                        clearInputs("#people_center input,#people_center select,#people_center textarea");
                        $("#people_form").css('display','none');
                        showStatus("Person deleted.");
                    } else {
                        showStatus("Error on deleting person.");
                    }
                }
            });
        }
    }
}*/


function savePerson(){
    saveFunc = function(){
        //If there are "people" fields to save
        if($(".dirty_input.people").length != 0 && $("#person__person_id").val()){
            var data = {
                id : $("#person__person_id").val(),
                cls : "person",
                data : {}
            };
            var dirty = $(".dirty_input.people");
            for(var i = 0; i < dirty.length; i++){
                data.data[dirty[i].id.replace("person__","")] = $(dirty[i]).val();    
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
                        showStatus("Error on saving person.");
                    }   
                }
            });
        }
    
        //If there are address fields to save
        if($(".dirty_input.address").length != 0){
            //update
            if($("#address__address_id").val()){
                var data = {
                    id : $("#address__address_id").val(),
                    cls : "address",
                    data : {}
                };
    
                var dirty = $(".dirty_input.address");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("address__","")] = $(dirty[i]).val();
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
                            showStatus("Error on saving address.");
                        }
                    }
                });
            //create
            }else if($("#person__person_id").val()){
                var data = {
                    id : $("#address__address_id").val(),
                    cls : "address",
                    data : {
                        person_id: $("#person__person_id").val()
                    }
                };
    
                var dirty = $(".dirty_input.address");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("address__","")] = $(dirty[i]).val();
                }
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
                            showStatus("Error on creating address.");
                        }
                    }
                });
            }
        }

        //Remove dirt
        clearDirty("#people_center input,#people_center select,#people_center textarea"); 
    };

    showStatus("Saving...",false, saveFunc);
}

function changePassword(){
    var pass1 = $("#change_password_table #password_1").val();
    var pass2 = $("#change_password_table #password_2").val();
    
    if(pass1 == pass2){
        $.ajax({
            url: uri+"/svc/update",
            type: 'POST',
            dataType: 'json',
            data: {
                'cls': 'person',
                'id' : $("#person__person_id").val(),
                'data' : { password: pass1}
            },
            async: true,
            success: function(ret){
                $('#password_modal').fadeOut(500, function(){
                    $('#password_modal_gray').remove(); 
                    $('#password_modal').remove();
                    $('#body').height(null);
                    $('#body').css('overflow-y','auto');
                });
                if(ret != -1){
                    showStatus("Password Changed!");
                } else {
                    showStatus("Password change failed.");
                }
            }
        });

    } else {
        $("#change_password_message").html("Passwords do not match.");
    }
}


function filterPerson(){
    if(filterTimeoutProc != false) clearTimeout(filterTimeoutProc);
    filterTimeoutProc = setTimeout(execFilter,250);
}

function execFilter(){
    filterTimeoutProc = false;

    var filter = $("#people_filter").val();


    if(filter != ""){
        $("#people_list ul").html('');
        
        var parts = filter.split(" ");

        var query = [];
        for(var p in parts){
            if(parts[p] != ""){
                var l = [['first_name','like',parts[p]+'%'],
                         'OR',
                         ['last_name','like',parts[p]+'%']];
                query.push(l);
                query.push('AND');
            }
        }
        query.splice(query.length-1,1);

        $.ajax({
            url: uri+"/svc/read",
            type: 'GET',
            dataType: 'json',
            data: {
                'cls'    : 'person',
                'query'  : query,
                'select' : ['first_name','last_name']
            },
            async: true,
            success: function(ret){
                ret = ret.results;
                for(var idx in ret){
                   r = ret[idx];
                   li = document.createElement("li");
                   li.id = r.person_id;

                   var res = r.last_name+", "+r.first_name;
                   var filter = $("#people_filter").val();
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
                        $("#people_list li").each(function(idx,el){
                            $(el).removeClass("selected");
                        });

                        $(this).addClass("selected");
                        loadPerson(this.id);
                    });
                   $("#people_list ul").append(li);
                }
            }
        });
    } else {
        $("#people_list li").each(function(idx, el){
            $(el).remove();
        });
    }
}


function passwordModal(){
    $.ajax({
        url:  uri+"/svc/call",
        type: 'GET',
        dataType: 'json',
        data: {
            cls: 'Person',
            fn: 'getPasswordModal',
            params: [1]
        },
        success: function(ret){
          procReturn(ret);
        }
    });
}

