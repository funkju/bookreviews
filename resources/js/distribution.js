function createDistList(){
    showStatus("Creating List...", false, function(){
        //Get Checked Book IDs
        var trs = $("#dist_create_table input:checked").parent().parent();
        var book_ids = [];
        for(var i = 0; i< trs.length; i++){
            book_ids.push([trs[i].id,trs[i].className]);
        }


        var name = $("#dist_name").val();
        var expire = $("#dist_expire").val();

        $.ajax({
            url:      uri+"/svc/call",
            type:     'GET',
            dataType: 'json',
            data: {
                cls: 'DistributionList',
                fn: 'create',
                params: [{name: name, expires: expire, books: book_ids}]
            },
            async: true,
            success: function(ret){
                var retFn = function(id){
                    window.location = uri+"/distribution?dl_id="+id;
                }

                if(ret != -1){
                    showStatus("Success!", false, function(){
                       retFn(ret); 
                    });
                } else {
                    showStatus("ERROR creating distribution list!");
                }
            },
            error: function(ret){
                showStatus("ERROR creating distribution list.");
            }
        });    
    });
}

function saveDistList(){
    showStatus("Saving List...", false, function(){
        //Get Unchecked Book IDs
        var trs = $("#dist_create_table input").not(":checked").parent().parent();
        var rem_book_ids = [];
        for(var i = 0; i< trs.length; i++){
            rem_book_ids.push(trs[i].id);
        }
        
        //Get Checked Book IDs
        var trs = $("#dist_create_table input:checked").parent().parent();
        var add_book_ids = [];
        for(var i = 0; i< trs.length; i++){
            add_book_ids.push(trs[i].id);
        }

        if(add_book_ids.length == 0) add_book_ids = -1;
        if(rem_book_ids.length == 0) rem_book_ids = -1;


        var dl_id = $("#dist_list_id").val();
        var name = $("#dist_name").val();
        var expire = $("#dist_expire").val();



        $.ajax({
            url:      uri+"/svc/call",
            type:     'GET',
            dataType: 'json',
            data: {
                cls: 'DistributionList',
                fn: 'alter',
                params: [dl_id, name, expire, rem_book_ids, add_book_ids]
            },
            async: true,
            success: function(ret){
                if(ret != -1){
                    showStatus("Success!");
                } else {
                    showStatus("ERROR altering distribution list!");
                }
            },
            error: function(ret){
                showStatus("ERROR altering distribution list.");
            }
        });    
    });



}

function rankChange() {

    var saveRank = function(el){
        var book_id = $(el).parent().parent()[0].id;
        var rank    = $(el).val();

        var selects = $("#distribution_books select");
        for(var i = 0; i < selects.length; i++){
            if($(selects[i]).val() == rank && el != selects[i]){
                $(selects[i]).val("--");
            }
        }

        $.ajax({
            url:    uri+"/svc/call",
            type:   'GET',
            dataType: 'json',
            data: {
                cls: 'DistributionListPreference',
                fn:  'updatePreferences',
                params: [distribution_list_id, book_id, rank]
            },
            async: true,
            success: function(ret){
                procReturn(ret);
                showStatus("Selections Saved!",4000 );
            }, 
            error: function(ret){
                showStatus("ERROR updating rank.");
            }
        });
    }

    var el = this;
    showStatus("Updating Selections...", false, function(){
        saveRank(el);
    });


}

function assignBook(assign,distribution_list_preference_id, dlp_array){

    if(typeof dlp_array != "undefined"){
       //Find Distribution List Book for this person and book_id
       var dlb  = $.ajax({
            url: uri+"/svc/read",
            type: 'GET',
            dataType: 'json',
            async: false,
            data: {
                cls: 'DistributionListBook',
                query: [
                    ['book_id',dlp_array.book_id],
                    'AND',
                    ['distribution_list_id',dlp_array.distribution_list_id]
                ]
            }
        });
        eval("var dlb = "+dlb.responseText);
        if(dlb.results.length != 0){
            dlb_id = dlb.results[0].distribution_list_book_id;

            //Create Distribution List Preference for this
            var dlp = $.ajax({
                url: uri+"/svc/create",
                type: "POST",
                dataType: 'json',
                async: false,
                data: {
                    cls: 'DistributionListPreference',
                    data: {
                        distribution_list_book_id: dlb_id,
                        person_id: dlp_array.person_id,
                        rank: null
                    }
                }
            });
            eval("dlp = "+dlp.responseText);
            if(typeof dlp.distribution_list_preference_id != "undefined"){
                distribution_list_preference_id =  dlp.distribution_list_preference_id;
            } else {
                showStatus("ERROR: Assigning to Other AE Failed.(190)");
                return;
            }

        } else {
            showStatus("ERROR: Assigning to Other AE Failed.(191");    
            return;
        }
    }

    var callback = function(assign, dlpid){
            $.ajax({
                url:  uri+"/svc/call",
                type: 'GET',
                dataType: 'json',
                data: {
                    fn: 'assignBook',
                    cls: 'DistributionListPreference',
                    params: [dlpid,  assign]
                },
                async: true,
                success: function(ret){
                    procReturn(ret);
                    hideStatus();
                },
                error: function(err, textStatus, errorThrow){
                    showStatus("ERROR assigning book.");
                    console.log(err, textStatus, errorThrow);
                }

            });
        };

    statusText = "Assigning...";
    if(!assign) statusText = "Unassigning...";

    showStatus(statusText,false,function(){
        callback(assign, distribution_list_preference_id);
    });

    

}

function showHistory(person_id){
    var callback = function(person_id){
        $.ajax({
            url: uri+"/svc/call",
            type: 'GET',
            dataType: 'json',
            data: {
                cls: 'DistributionList',
                fn: 'getPersonHistory',
                params: [person_id]
            },
            success: function(ret){
                procReturn(ret);
                hideStatus();
            }
        });
    }

    showStatus('Loading', false, function(){
        callback(person_id);
    });
}

function distHistSort(el,sel){
    var order = (!$(sel).hasClass('headerSortDown')) ? 0 : 1;
    var num   = 0;

    if(sel.indexOf('pend') != -1){
        num = 0;
    } else if(sel.indexOf('rank') != -1){
        num = 1;
    } else if(sel.indexOf('promised') != -1){
        num = 2;
    } else if(sel.indexOf('title') != -1){
        num = 3;
    }


    $("table.dist_header  td").css("fontWeight","");
    $(el).css('fontWeight',  "bold");
    $('table.dist_hist').trigger('sorton',[[[num,order]]]);
}

function sortDL(by){
   var sortFn;

    if(by == "active") {
        $("#sort_by_active").css('font-weight','bold');
        $("#sort_by_name").css('font-weight','normal');
        $("#sort_by_created").css('font-weight','normal');
        $("#sort_by_expires").css('font-weight','normal');

        sortFn = function(a,b){
            a_v = $("td > span",a).css('font-weight') == "bold";
            b_v = $("td > span",b).css('font-weight') == "bold";
            return a_v < b_v;
        }        
    } else if(by == "name") {
        $("#sort_by_active").css('font-weight','normal');
        $("#sort_by_name").css('font-weight','bold');
        $("#sort_by_created").css('font-weight','normal');
        $("#sort_by_expires").css('font-weight','normal');

        sortFn = function(a,b){
            a_v = $("td > span",a).html();
            b_v = $("td > span",b).html();
            return a_v > b_v;
        }        
    } else if(by == "created"){
        $("#sort_by_active").css('font-weight','normal');
        $("#sort_by_name").css('font-weight','normal');
        $("#sort_by_created").css('font-weight','bold');
        $("#sort_by_expires").css('font-weight','normal');

        sortFn = function(a,b){
            a_v = $("td input.created",a).val();
            b_v = $("td input.created",b).val();
            return a_v < b_v;
        }        
    } else if(by == "expires") {
        $("#sort_by_active").css('font-weight','normal');
        $("#sort_by_name").css('font-weight',   'normal');
        $("#sort_by_created").css('font-weight','normal');
        $("#sort_by_expires").css('font-weight','bold');

        sortFn = function(a,b){
            a_v = $("td input.expires",a).val();
            b_v = $("td input.expires",b).val();
            return a_v < b_v;
        }        
    } else { return };

    $(".dist_list_row").sort(sortFn).appendTo('#dist_list_holder');

}


function howToSaveMsg(){

    alert("Selections are automatically updated.\nNo need to click a save button!");
}


function toggleDLAct(dl_id, tog){
    var msg;
    if(tog) {
        msg = "Activating...";
    } else {
        msg = "Deactivating...";
    }

    var activateFn = function(dl_id, tog){
        $.ajax({
           url : uri+"/svc/call",
           type: 'GET',
           dataType: 'json',
           data: {
               cls: 'DistributionList',
               fn: 'activate',
               params: [dl_id, tog]
           },
           success: function(dl_id){
               $(".dist_list_row > td > span").css('font-weight', 'normal');
               $(".manage_dl_deact").css('display','none');
               $(".manage_dl_act").css('display','');
               if(dl_id){
                    $("#dist_list_"+dl_id + " >td > span").css('font-weight', 'bold');

                    $("#deact_"+dl_id).css('display','');
                    $("#act_"+dl_id).css('display','none');

                    hideStatus();
               } else {
                    showStatus("Activation failed.");
               }
           }
        });
    }
    
    showStatus(msg,false, function(){
        activateFn(dl_id, tog);
    });
}

function deactivateDistList(id) {
    var deactivateFn = function(id){
        $.ajax({
            url: uri+"/svc/update",
            type: 'POST',
            dataType: 'json',
            data: {
                cls: 'DistributionList',
                id: id,
                data: {
                    active : 0
                }
            },
            success: function(ret){
                if(ret != -1){
                    showStatus("List Deactivated!",false);
                    window.location.reload();
                } else {
                    showStatus("ERROR: List Deactivation Failed!",5000);
                }
            }, 
            error: function(ret){
                    showStatus("ERROR: List Deactivation Failed!",5000);
            }
        });
    }
    
    showStatus("Deactivating List", false, function(){
        deactivateFn(id);
    });

}

function toggleAllAEs(el, id, t){
    var table = $("#all_"+t+"_"+id);
    var link = el;

    var t = (t == "books") ? "Books" : "AEs";

    if($(table).css('display') == 'none'){
        $(table).css('display', "");
        $(link).html('Hide All '+t);
    } else {
        $(table).css('display', 'none');
        $(link).html('Show All '+t);
    }
}


jQuery.fn.sort = function() {  
   return this.pushStack( [].sort.apply( this, arguments ), []);  
}; 

