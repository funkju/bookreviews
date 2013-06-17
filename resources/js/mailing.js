function markAllSent(el){
    var person_id = el.id.replace("mark_all_sent_","");


    $.ajax({
        url: uri+"/mailing/markAllSent",
        type: 'POST',
        dataType: 'json',
        data: {
            person_id: person_id
        },
        async: true,
        success: function(ret){
            if(ret != -1){
                if(ret.book_reviews){
                    for(var i = 0; i < ret.book_reviews.length; i++){
                        br_id = ret.book_reviews[i].book_review_id;

                        el = $("#mark_sent_"+br_id);
                        el.removeClass("button2").addClass("button3");
                        el.html("Sent");
                        el.unbind("click");
                    }

                    if(ret.book_reviews[0]){
                        el = $("#mark_all_sent_"+ret.book_reviews[0].assoc_editor_id);
                        el.removeClass("button").addClass("button3");
                        el.html("All Sent");
                        el.unbind("click");
                    }
                }
            }else{
                showStatus("Error marking all sent.");
            }
        
        },
        error: function(ret){
            showStatus("Error marking all sent.");
        }


    });

}

function markSent(el){
    var book_review_id = el.id.replace("mark_sent_","");

    $.ajax({
        url: uri+"/svc/update",
        type: 'POST',
        dataType: 'json',
        data: {
            cls: 'BookReview',
            id: book_review_id,
            data: {
                date_sent:  new Date().getTime()/1000
            }
        },
        async: true,
        success: function(ret){
            if(ret != -1){
                if(ret.book_review_id){
                    el = $("#mark_sent_"+ret.book_review_id);
                    el.removeClass("button2").addClass("button3");
                    el.html("Sent");
                    el.unbind('click');

                    var buttons = $("ul#mailing_for_"+ret.assoc_editor_id+" div.button2");
                    if(buttons.length == 0){
                        el = $("#mark_all_sent_"+ret.assoc_editor_id);
                        el.removeClass("button").addClass("button3");
                        el.html("All Sent");
                        el.unbind("click");
                    }
                }
            } else {
                showStatus("Error on marking sent.");
            }
        }
        

    });
}
