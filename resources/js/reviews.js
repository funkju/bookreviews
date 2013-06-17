var search_offset = 0;
var search_order = "book_title";


function loadReviewerContact(person_id){
    $.ajax({
        url:      uri+"/svc/read",
        type:     "GET",
        dataType: 'json',
        data: {
            cls:    'address',
            query:  [['person_id', person_id]],
            select: 'voice, email'
        },
        async:    true,
        success:  function(ret){
            ret = ret.results;
            if(ret.length >= 1){
                ret[0].voice = (!ret[0].voice) ? "---" : ret[0].voice;
                ret[0].email = (!ret[0].email) ? "---" : ret[0].email;

                $("#reviewer__phone").html(ret[0].voice);
                $("#reviewer__email").html(ret[0].email);
            } else {
                $("#reviewer__phone").html("---");
                $("#reviewer__email").html("---");
            }
        },
        error: function(ret){
            showStatus("Loading Reviewer Contact Failed.");

        }
    });
}

function loadAssocEditorContact(person_id){
    $.ajax({
        url:      uri+"/svc/read",
        type:     "GET",
        dataType: 'json',
        data: {
            cls:    'address',
            query:  [['person_id', person_id]],
            select: 'voice, email'
        },
        async:    true,
        success:  function(ret){
            ret = ret.results;
            if(ret.length >= 1){
                ret[0].voice = (!ret[0].voice) ? "---" : ret[0].voice;
                ret[0].email = (!ret[0].email) ? "---" : ret[0].email;

                $("#assoc_ed__phone").html(ret[0].voice);
                $("#assoc_ed__email").html(ret[0].email);
            } else {
                $("#assoc_ed__phone").html("---");
                $("#assoc_ed__email").html("---");
            }
        },
        error: function(ret){
            showStatus("Loading Assoc Editor Contact Failed.");

        }
    });
}

function createNewBookReview() {

    showStatus("Creating New Review...",false,function(){
        $.ajax({
            url:      uri+'/svc/create',
            type:     "POST",
            dataType: 'json',
            data: {
                cls:  'BookReview'
            },
            async:    true,
            success:  function(ret){
                if(ret != -1){
                    showStatus("Created Review!", false);
                    window.location = uri+'/reviews/edit/'+ret.book_review_id;
                } else {
                    showStatus("Review Creating Failed!");
                }
            },
            error:  function(ret){
                showStatus("Review Creating Failed!");

            }

        });
    });

}

function saveBookReview(){
    var dirty = $(".dirty_input");

    if(dirty.length != 0){
        showStatus("Saving...",false,function(){
            var dirty = $(".dirty_input");


            var data = {};
            for(var i = 0; i < dirty.length; i++){
                if($(dirty[i])[0].type == "checkbox"){
                    data[dirty[i].id.replace("book_review__","")] = $(dirty[i]+":checked").val();
                }else{
                    data[dirty[i].id.replace("book_review__","")] = $(dirty[i]).val();
                }
            }
            data.do_not_publish = (data.do_not_publish == "on") ? 1 : 0;
            
            //An Extra is deleted
            delete(data.title);
        

            $.ajax({
                url:      uri+'/svc/update',
                type:     "POST",
                dataType: 'json',
                data: {
                    cls: 'BookReview',
                    id: $("#book_review__book_review_id").val(),
                    data: data
                },
                async: true,
                success: function(ret){
                    if(ret != -1){      
                        showStatus("Updated!");
                        clearDirty("input.reviews, select.reviews, textarea.reviews");
                    } else {
                        showStatus("Updating Book Review failed");
                    }

                },
                error: function(err){
                    showStatus("Updating Book Review failed");
                }
            });
        });

    }
}

function delBookReview(){
    showStatus("Deleting...",false,function(){
        var review_id = $("#book_review__book_review_id").val();

        $.ajax({
            url:    uri+"/svc/delete",
            type:   "POST",
            dataType: 'json',
            data: {
                cls: 'BookReview',
                id: review_id
            },
            async: true,
            success: function(ret){
                if(ret != -1){
                    showStatus("Deleted!");
                    window.location = uri+"/reviews";
                }else{
                    showStatus("Deleting Book Review Failed!");
                }
            },
            error: function(ret){
                showStatus("Deleting Book Review Failed!");
            }
        });
    });
}

function searchReviews(offset, order){
    if(typeof offset != "undefined"){
        search_offset = offset;
    }
    if(typeof order != "undefined"){
        search_order = order;
    }

    showStatus("Searching...", false, function(){
        var params = $("#reviews_search >  input[type=text]").val();
        var search = $("#reviews_search > select").val();

        if(params != ""){
           $.ajax({
                url: uri+"/reviews/searchReviews",
                type: 'GET',
                dataType: 'json',
                data: {
                    'query': params,
                    'limit' : 25,
                    'offset' : search_offset,
                    'order'  : search_order,
                    'search' : search
                },
                async: true,
                success: function(ret){
                    procReturn(ret);
                    hideStatus();
                },
                error: function(ret){
                    showStatus("Finding Reviews failed.");
                }
           });

        }


    });

}


function showPending(ae_id){
    $("#review_message").hide();

    if($("#reviews_list > div.show").length != 0 && $("#reviews_list > div.show")[0].id == "right_"+ae_id) return;
    if($("#reviews_list > div.show").id == "right_"+ae_id) return;

    //Undo the bold item on the left
    $("div.reviews_pending_name.show").css("font-weight","normal").removeClass("show");
    //Make this item bold
    $("#left_"+ae_id).css("font-weight","bold").addClass("show");



    var show = function(ae_id){
        $("#right_"+ae_id).fadeIn();
        $("#right_"+ae_id).addClass("show");
        $.cookie('show_ae',ae_id);
    }

    //If there is a list shown, fade out, then show the new one
    if($("#reviews_list > div.show").length != 0){
        $("#reviews_list > div.show").fadeOut(function(){
            $(this).removeClass("show");
            show(ae_id);   
        });

    //Else just show the new one
    } else {
        show(ae_id);
    }



}


function sortPendingReviews(by){
    var sortFn;
    if(by == "name") {
        $("#sort_by_name").css('font-weight','bold');
        $("#sort_by_count").css('font-weight','normal');

        sortFn = function(a,b){
            return $("span.name",a).html() > $("span.name",b).html() ? 1: -1;
        }        
    } else if(by == "count"){
        $("#sort_by_name").css('font-weight','normal');
        $("#sort_by_count").css('font-weight','bold');

        sortFn = function(a,b){
            var intA = parseInt($("span.count",a).html().replace("(","").replace(")",""));
            var intB = parseInt($("span.count",b).html().replace("(","").replace(")",""));

            return intA < intB ? 1: -1;
        }        
    } else { return };

    $(".reviews_pending_name").sort(sortFn).appendTo('#ae_list_holder');
}


function fillMonths(){
    $("#month_spinner").css("display","");
    $("#month").css("display","none");
    $("#reviews_by_issue").css("display","none");
    
    
    var journal_id = $("#seljournal").val();
    if(journal_id == ""){
        return;
    }
    var year = $("#selyear").val();
    if(year  == ""){
        return;
    }

    var months = [
        'January','Febuary','March','April','May','June','July','August','September','October','November','December'
    ];


    $.ajax({
        url: uri+"/svc/read",
        type: 'GET',
        dataType: 'json',
        async: true,
        data: {
            cls: 'BookReview',
            select: 'issue_month',
            distinct: true,
            order: 'issue_month ASC',
            query: [
                ['journal_id',journal_id],'AND',['issue_year',year],'AND',['issue_month','>',0]
            ]
        },
        success: function(ret){
            $("#selmonth").children().remove();
            $("#selmonth").append("<option value=''>----</option>");
            if(ret != -1){
                for(var i = 0; i < ret.results.length; i++){
                    mon = ret.results[i];
                    $("#selmonth").append("<option value="+mon.issue_month+">"+months[mon.issue_month-1]+"</option>");
                } 
            }

            $("#month").css('display','');
            $("#month_spinner").css("display","none");
        }
    });
}

function fillYears(){
    $("#year_spinner").css("display","");
    $("#year").css("display","none");
    $("#month").css("display","none");
    $("#month_spinner").css("display","none");
    $("#reviews_by_issue").css("display","none");

    var journal_id = $("#seljournal").val();
    if(journal_id == ""){
        return;
    }

    $.ajax({
        url: uri+"/svc/read",
        type: 'GET',
        dataType: 'json',
        async: 'true',
        data: {
            cls: 'BookReview',
            select: 'issue_year',
            distinct: true,
            order: 'issue_year ASC',
            query: [
                ['journal_id',journal_id],'AND',['issue_year','>',0]
            ]

        },
        success: function(ret){
            $("#selyear").children().remove();
            $("#selyear").append("<option value=''>----</option");
            if(ret != -1){
                for(var i = 0; i < ret.results.length; i++){
                    yr = ret.results[i];
                    $("#selyear").append("<option>"+yr.issue_year+"</option");
                }
            }
            $("#year").css("display","");
            $("#year_spinner").css("display","none");
        }
    });

}

function getReviewsByIssue(){
    $("#content_spinner").css("display","");
    $("#reviews_by_issue").css("display","none");

    var issue_month = $("#selmonth").val();
    $.cookie('rev_by_issue_month',issue_month);
    if(issue_month == ""){
        $("#year").css("display", "none");
        return;
    }
    var journal_id = $("#seljournal").val();
    $.cookie('rev_by_issue_journal_id',journal_id);
    if(journal_id == ""){
        return;
    }
    var year = $("#selyear").val();
    $.cookie('rev_by_issue_year',year);
    if(year  == ""){
        return;
    }



    $.ajax({
        url: uri+"/svc/call",
        type: 'GET',
        dataType: 'json',
        async: 'true',
        data: {
            cls: 'BookReview',
            fn: 'getReviewsByIssue',
            params: [ journal_id, issue_month, year ]
        },
        success: function(ret){
            if(ret != -1){
                procReturn(ret);
                $("#reviews_by_issue").css("display","");
                $("#content_spinner").css("display","none");
            }
        }
    });
}
function getOrderToPublish(){
	$("#order_to_publish").css("display","");
	$("#content_spinner").css("display","none");

	var month =	$.cookie("rev_by_issue_month");
	var year =	$.cookie("rev_by_issue_year");
	var journal_id =	$.cookie("rev_by_issue_journal_id");


	if(month == "" || year == "" || journal_id == "") return;

	$.ajax({
		url: uri+"/svc/call",
		type: 'GET',
		dataType: 'json',
		async: 'true',
		data: {
			cls: 'BookReview',
			fn: 'getOrderToPublishList',
			params: [journal_id, month, year]
		},
		success: function(ret){
			if(ret != -1){
				procReturn(ret);
				$("#order_to_publish").css("display","");
				$("#content_spinner").css("display","none");
			}
		}
	});
}
function getAuthorInformation(){
	$("#author_information").css("display","");
	$("#content_spinner").css("display","none");

	var month =	$.cookie("rev_by_issue_month");
	var year =	$.cookie("rev_by_issue_year");
	var journal_id =	$.cookie("rev_by_issue_journal_id");


	if(month == "" || year == "" || journal_id == "") return;

	$.ajax({
		url: uri+"/svc/call",
		type: 'GET',
		dataType: 'json',
		async: 'true',
		data: {
			cls: 'BookReview',
			fn: 'getAuthorInformationList',
			params: [journal_id, month, year]
		},
		success: function(ret){
			if(ret != -1){
				procReturn(ret);
				$("#author_information").css("display","");
				$("#content_spinner").css("display","none");
			}
		}
	});
}


function cleanReviewsByIssueSelects() {

    var issue_month = $("#selmonth").val();
    var journal_id = $("#seljournal").val();
    var year = $("#selyear").val();
    
    if(issue_month == "" || journal_id == "" ||year  == ""){
        return;
    }
    
    
    var months = [
        'January','Febuary','March','April','May','June','July','August','September','October','November','December'
    ];

    $.ajax({
        url: uri+"/svc/read",
        type: 'GET',
        dataType: 'json',
        async: 'true',
        data: {
            cls: 'BookReview',
            select: 'issue_year',
            distinct: true,
            order: 'issue_year ASC',
            query: [
                ['journal_id',journal_id],'AND',['issue_year','>',0]
            ]

        },
        success: function(ret){
            var y = $("#selyear").val();
            $("#selyear").children().remove();
            $("#selyear").append("<option value=''>----</option");
            if(ret != -1){
                for(var i = 0; i < ret.results.length; i++){
                    yr = ret.results[i];
                    $("#selyear").append("<option>"+yr.issue_year+"</option");
                }
            }
            $("#selyear").val(y);
        }
    });
    
    $.ajax({
        url: uri+"/svc/read",
        type: 'GET',
        dataType: 'json',
        async: true,
        data: {
            cls: 'BookReview',
            select: 'issue_month',
            distinct: true,
            order: 'issue_month ASC',
            query: [
                ['journal_id',journal_id],'AND',['issue_year',year],'AND',['issue_month','>',0]
            ]
        },
        success: function(ret){
            var m = $("#selmonth").val();
            $("#selmonth").children().remove();
            $("#selmonth").append("<option value=''>----</option>");
            if(ret != -1){
                for(var i = 0; i < ret.results.length; i++){
                    mon = ret.results[i];
                    $("#selmonth").append("<option value="+mon.issue_month+">"+months[mon.issue_month-1]+"</option>");
                } 
            }
            $("#selmonth").val(m);
        }
    });

}

function changePubOrder(el){
    var new_val = $(el).val();
    var review_id = $(el).parent().parent().attr('id').replace("review_id_","");

    if(new_val != ""){
        var sels = $('select.publish_order');
        for(var i = 0; i < sels.length; i++){
            if($(sels[i]).val() == new_val && sels[i] != el ){
                $(sels[i]).val('');
                //This shouldn't recurse
                changePubOrder(sels[i]);
            }
        }
    }
    

    $.ajax({
        url: uri+"/svc/update",
        type: 'POST',
        dataType: 'json',
        async: 'true',
        data: {
            cls: 'BookReview',
            id: review_id,
            data: {
                'publish_order': new_val
            }
        },
        success: function(ret){
            if(ret != -1){
               showStatus('Order Saved...');
            } else {
                showStatus('Saving Order Failed!',5000);
            }

        }


    });

}

function deleteAttach(a_id){
	var answer = confirm("Delete This Attachment?");

	if(answer){
		$("tr#attach_"+a_id).addClass("delete_attach");

		showStatus("Deleting...",false,function(){
			$.ajax({
				url: uri+"/svc/delete",
				type: 'POST',
				dataType: 'json',
				async: 'true',
				data: {
					cls: 'BookReviewAttachment',
					id: a_id
				},
				success: function(ret){
					if(ret != -1){
						showStatus('Attachment Deleted...');
						$("tr.delete_attach").remove();
					} else {
						showStatus("Deleting Attachment Failed");
					}
				}
			});
		});

	}

}



jQuery.fn.sort = function() {  
   return this.pushStack( [].sort.apply( this, arguments ), []);  
};  
