var ServiceTypes = {
    "US-FC": "First-Class Mail",
    "US-MM": "Media Mail",
    "US-PP": "Parcel Post",
    "US-PM": "Priority Mail",
    "US-XM": "Express Mail",
    "US-EMI": "Express Mail International",
    "US-PMI": "Priority Mail International",
    "US-FCI": "First Class Mail International"
}

function addPostageLog(logmsg){
    var el = document.createElement("li");
    $(el).html(logmsg);
    
    if($("div#status > ul > li").length != 0){
        $("div#status > ul > li:first").before(el);
    } else {
        $("div#status > ul").append(el);
    }
}

function cleanseAddress(address, sel, person_id){

    if(address.Address1 == ""){
        $("#status_text").html("To Address 1 is required.");
        return;
    }else if(address.City == ""){
        $("#status_text").html("To City is required.");
        return;
    }else if(address.Country == "" && address.State == ""){
        $("#status_text").html("To State is required.");
        return; 
    }else if(address.FullName == ""){
        $("#status_text").html("To Full Name is required.");
        return;
    }
    showStatus("Cleasing Address...",false);

    var data = {address: address};
    if(typeof person_id != "undefined") data['person_id'] = person_id;


    addPostageLog("Cleasing Address...");
    res = $.ajax({
        url: uri+"/postage/cleanseAddress",
        type: "POST",
        dataType: 'json',
        data: data,
        async: false
    });

    eval("ret = "+res.responseText);
    
    if(typeof sel != "undefined"){
        var el = $(sel);

        if(ret.Address){
            if(el){
                for(var name in ret.Address){
                    var val = ret.Address[name];
                    if(val == null || val.toLowerCase() == "null") val = "";
                    if($(sel+"> #postage_to__"+name)) $(sel+ "> #postage_to__"+name).val(val);
                }

                if(ret.AddressMatch == "true"){
                    $(sel +">#to_address_match").css('display','');
                }  else {
                    $(sel +">#to_address_match").css('display','none');
                }
                if(ret.CityStateZipOK == "true"){
                    $(sel+">#to_csz_match").css('display','');
                    $("#postage__package_info").show();
                } else {
                    $(sel+">#to_csz_match").css('display','none');
                    $("#postage__package_info").hide();
                }
                addPostageLog("Address cleansed.");
                showStatus("Success!");
            }
        }
        
        if(ret.Error){
            addPostageLog(ret.Error);
            showStatus("Error Cleasing Address.");
        }
    }

    
}

function getRates(){
    var rate = {
        PackageType : $("#postage_details__PackageType").val(),
        ToZIPCode : $("#postage_to__ZIPCode").val(),
        WeightOz : $("#postage_details__WeightOz").val(),
        ShipDate: $("#postage_details__ShipDate").val()
    };


    if(rate.PackageType == ""){
        showStatus("Package Type is required.");
        return;
    }else if(rate.ToZIPCode == ""){
        showStatus("To ZIP Code is required.");
        return;
    }else if(rate.WeightOz== ""){
        showStatus("Weight (Oz) is required.");
        return;
    }


    res = $.ajax({
        url: uri+"/postage/getRates",
        type: "POST",
        dataType: 'json',
        data: {rate: rate},
        async: false
    });

    eval("ret = "+res.responseText);
    if(ret.Rate){
        $("#postage__rates").empty();
        for(var i = 0; i < ret.Rate.length; i++){
            var r = ret.Rate[i];
            
            var div = document.createElement("div");

            var input = document.createElement("input");
            $(input).attr('id',"postage_rates__"+i);
            $(input).attr('type','radio');
            $(input).attr('name','postage_rates');
            $(input).attr('style','width: 10px; margin-top: 4px; margin-right: 2px;');
            $(input).bind('click', function(){
                $("a#purchase_postage").css('display','');
            });
            $(div).append(input);

            var b = document.createElement("b");
            $(b).html(ServiceTypes[r.ServiceType]);
            $(div).append(b);
            $(div).append("<br>");

            $(div).append("<span style='padding-left: 20px'>$"+r.Amount+"</span>");
            $(div).append("<br>");
            $(div).append("<span style='padding-left: 20px'>"+r.DeliverDays+" day(s)</span>");
            $(div).append("<br>");
            $("#postage__rates").append(div);
        }
        $("#postage__rates").css('display','');
    }

    if(ret.Error){
        addPostageLog(ret.Error);
        showStatus("Error Generating Rates.");
    
    }
}

function createIndicium(){
    //SERVER has the latesest cleansed FROM address
    //SERVER has our TO address
    //SERVER has all the rates
    //We just need to tell it which number of rate we
    //want to use.

    var num = $("input[name=postage_rates]:checked").attr('id').replace("postage_rates__","");


    var ret = $.ajax({
        url: uri+"/postage/createIndicium",
        type: 'POST',
        dataType: 'json',
        data: {rate_num: num},
        async: false
    });
    eval("ret ="+ret.responseText);

    if(ret.Error){
        addPostageLog(ret.Error);
        showStatus("Error Creating Indicium");
    }
    
}

function loadRecipientSelect(){
    $.ajax({
        url:      uri+"/svc/read",
        type:     'GET',
        dataType: 'json',
        data: {
            cls:   'person',
            query: [['last_name','!=', ''],'AND',['role_id',3]],
            order: 'last_name, first_name',
            select: 'last_name, first_name, institution'
        },
        async: true,
        success: function(ret){
            ret = ret.results;
            if(ret == -1){
                showStatus("Populating Recipients Failed");
                return;
            }

            var select = $("#postage_to__assoc_editor_id");
            var val = $(select).val();
            $(select).html('');
            var el = document.createElement('option');
            el.value = "";
            el.innerHTML = "Select One";
            $(select).append(el);

            for(i in ret){
                var p = ret[i];

                var el = document.createElement('option');
                el.value = p.person_id;
                el.selected = p.person_id == val;
                el.innerHTML = p.last_name + ", " + p.first_name;
                el.data = p;

                                $(select).append(el);
            }

           $("#recipient_loading_spinner").remove();
        },
        error: function(ret){
            showStatus("Loading Recipient Select List Failed!");
        }

    });
}

function do_cleanseAddress(){
        
    showStatus("Cleansing To Address...",false, function(){
        var address = {
            'FullName'   : $("option[value="+$("#postage_to__assoc_editor_id").val()+"]").html(),
            'Company'    : $("#postage_to__Company").val(),
            'Department' : $("#postage_to__Department").val(),
            'Address1'   : $("#postage_to__Address1").val(),
            'Address2'   : $("#postage_to__Address2").val(),
            'City'       : $("#postage_to__City").val(),
            'State'      : $("#postage_to__State").val(),
            'ZIPCode'    : $("#postage_to__ZIPCode").val(),
            'Country'    : $("#postage_to__Country").val()
        };

        cleanseAddress(address, "#postage__recipient", $("#postage_to__assoc_editor_id").val());
    });
}
