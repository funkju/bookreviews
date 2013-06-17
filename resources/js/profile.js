function savePerson(){
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
                    clearInputs("#people_center input.people, #people_center textarea.people, #people_center select.people, #people_center div.readonly");
                    loadPersonRecord(ret, 'person');
                } else {
                    alert("Error on saving person.");
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
                        clearInputs("#people_center input.address. #people_center. div.readonly.address");
                        loadPersonRecord(ret, 'address');
                    } else {
                        alert("Error on saving address.");
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
                data.data[dirty[i].id] = $(dirty[i]).val();
            }
            $.ajax({
                url: uri+"/svc/create",
                type: 'POST',
                dataType: 'json',
                data: data,
                async: true,
                success: function(ret){
                    if(ret != -1){
                        clearInputs("#people_center input.address, #people_center div.readonly.address");
                        loadPersonRecord(ret, 'address');
                    } else {
                        alert("Error on creating address.");
                    }
                }
            });
        }
    }
}

function changePassword(){
    var pass1 = prompt("Enter new password.");
    var pass2 = prompt("Confirm new password.");

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
                if(ret != -1){
                    alert("Success!");
                } else {
                    alert("Password change failed.");
                }
            }
        });

    } else {
        alert("Passwords do not match.");
    }
}


