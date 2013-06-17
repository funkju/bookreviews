var search_offset = 0;
var search_order = "title";
var search_query = "";

function loadBook(book_id){
    clearInputs("#book_center input, #book_center textarea, #book_center select");


    $.ajax({
        url: uri+"/books/loadBook",
        type: 'GET',
        dataType: 'json',
        data: {book_id: book_id},
        async: true,
        success: function(ret){
            if(ret != -1){
                $("#book_list").slideUp(250, procReturn(ret));

                if($("#book_del").css('display') == "none"){
                    $("#book_del").css('display',"");
                }

                hideStatus();
            } else {
                showStatus("Error on reading book.");
            }
        },
        error: function(ret){
            showStatus("Error on reading book.");
        }
    });

}

function delBook() {
   
    if($("#book__book_id").val()){
        var del = confirm("Delete This Book?");
        if(del){

            showStatus("Deleting...",false, function(){
                data = {
                    'cls': 'book',
                    'id' : $("#book__book_id").val()
                };

                /** 
                 * NOTE: the PHP Book class takes care
                 * of data integrity, deleting all other
                 * references and data associated
                 * with this book.
                 */
                $.ajax({
                    url: uri+"/svc/delete",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            showStatus("Book Deleted!",false,function(){
                                window.location = uri+"/books";   
                            });
                        } else {
                            showStatus("Error on deleting book.");
                        }
                    },
                    error: function(ret){
                        showStatus("Error on deleting book.");
                    }
                });
            });
        }
    }
}

function addBook() {
    showStatus("Creating Book", false, function(){
        var d = new Date();


        var isbn = prompt("Enter an ISBN or press Cancel");
        if(isbn){
            addByISBN(isbn);
        } else {
            var res = $.ajax({
                url: uri+"/svc/create",
                type: 'POST',
                dataType: 'json',
                data: {
                    cls: "Book",
                    data: {
                        title: 'New Book',
                        authors: 'Last, First',
                        date_added: Math.floor(new Date().getTime()/1000),
                        synopsis: 'Add Synopsis Here'
                    }
                },
                async: false
            });
        
            if(res != -1){
                eval("res = "+res.responseText);

                showStatus("Book Created!");
                loadBook(res.book_id);

            } else {
                showStatus("Book Creation Failed.");
            }
        }
    });
}


function saveBook(){
    saveFunc = function(){
        var saving = false; 
        //If there are "book" fields to save
        if($(".dirty_input.book").length != 0 && $("#book__book_id").val()){
            saving = true; 
            var data = {
                id : $("#book__book_id").val(),
                cls : "book",
                data : {}
            };
            var dirty = $(".dirty_input.book");
            for(var i = 0; i < dirty.length; i++){
                data.data[dirty[i].id.replace("book__","")] = $(dirty[i]).val();    
            }
            $.ajax({
                url: uri+"/svc/update",
                type: 'POST',
                dataType: 'json',
                data: data,
                async: true,
                success: function(ret){
                    if(ret != -1){
                        clearDirty("#book_center input.book, #book_center textarea.book, #book_center select.book");
                        showStatus("Book Saved!");
                    } else {
                        showStatus("Error on saving book.");
                    }   
                },
                error: function(ret){
                    showStatus("Error on saving book.");
                }
            });
        }
    
        //If there are "book_marketing_info" fields to save
        if($(".dirty_input.book_marketing_info").length != 0 ) {
            saving = true; 
            if ($("#book__book_marketing_info_id").val()){
                var data = {
                    id : $("#book__book_marketing_info_id").val(),
                    cls : "BookMarketingInfo",
                    data : {}
                };
                var dirty = $(".dirty_input.book_marketing_info");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("book_marketing_info__","")] = $(dirty[i]).val();
                }
                $.ajax({
                    url: uri+"/svc/update",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            clearDirty("#book_center input.book_marketing_info, #book_center textarea.book_marketing_info, #book_center select.book_marketing_info");
                            showStatus("Book Saved!");
                        } else {
                            showStatus("Error on saving book.");
                        }
                    },
                    error: function(ret){
                        showStatus("Error on saving book.");
                    }
                });
            } else {
    
                var data = {
                    cls  : "BookMarketingInfo",
                    data : {}
                };
    
                var dirty = $(".dirty_input.book_marketing_info");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("book_marketing_info__","")] = $(dirty[i]).val();
                }
    
                $.ajax({
                    url:   uri+"/svc/create",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            clearDirty("#book_center input.book_marketing_info, #book_center textarea.book_marketing_info, #book_center select.book_marketing_info");
    
                            $.ajax({
                                url: uri+"/svc/update",
                                async: true,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    id   : $("#book__book_id").val(),
                                    cls  : "book",
                                    data : {
                                        book_marketing_info_id: ret.book_marketing_info_id
                                    }
                                }
                            });
                            showStatus("Book Saved!");
                        } else {
                            showStatus("Create Failed...");
                        }
                    },
                    error: function(ret){
                        showStatus("Create Failed...");
                    }
                });
    
            }
    
        }
    
        //If there are "extra_book_marketing_info" fields to save
        if($(".dirty_input.extra_book_marketing_info").length != 0){
            saving = true; 
            if($("#book__extra_book_marketing_info_id").val()){
                var data = {
                    id : $("#book__extra_book_marketing_info_id").val(),
                    cls : "BookMarketingInfo",
                    data : {}
                };
                var dirty = $(".dirty_input.extra_book_marketing_info");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("extra_book_marketing_info__","")] = $(dirty[i]).val();
                }
                $.ajax({
                    url: uri+"/svc/update",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            clearDirty("#book_center input.extra_book_marketing_info, #book_center textarea.extra_book_marketing_info, #book_center select.extra_book_marketing_info");
                        } else {
                            showStatus("Error on saving book.");
                        }
                    },
                    error: function(ret){
                        showStatus("Error on saving book.");
                    }
                });
            } else {
                var data = {
                    cls  : "BookMarketingInfo",
                    data : {}
                };
                
                var dirty = $(".dirty_input.extra_book_marketing_info");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("extra_book_marketing_info__","")] = $(dirty[i]).val();
                }
                
                $.ajax({
                    url:   uri+"/svc/create",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            clearDirty("#book_center input.extra_book_marketing_info, #book_center textarea.extra_book_marketing_info, #book_center select.extra_book_marketing_info");
                            
                            $.ajax({
                                url: uri+"/svc/update",
                                async: true,
                                dataType: 'json',
                                data: {
                                    id   : $("#book__book_id").val(),
                                    cls  : "book",
                                    data : {
                                        extra_book_marketing_info_id: ret.book_marketing_info_id
                                    }
                                }
                            });
                            showStatus("Book Saved!");
                        } else {
                            showStatus("Create Failed...");
                        }
                    },
                    error: function(ret){
                        showStatus("Create Failed...");
                    }
                });
    
            }
        }

        //If there are "book_review" fields to save
        if($(".dirty_input.book_review").length != 0){
            saving = true;
            if($("#book_review__book_review_id").val()){
                var data = {
                    id : $("#book_review__book_review_id").val(),
                    cls : "BookReview",
                    data : {}
                };
                var dirty = $(".dirty_input.book_review");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("book_review__","")] = $(dirty[i]).val();
                }
                $.ajax({
                    url: uri+"/svc/update",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            clearDirty("#book_center input.book_review");
                            hideStatus();
                        } else {
                            showStatus("Error on saving book review.");
                        }
                    },
                    error: function(ret){
                        showStatus("Error on saving book review.");
                    }
                });
            } else {
                var data = {
                    cls  : "BookReview",
                    data : {}
                };

                var dirty = $(".dirty_input.book_review");
                for(var i = 0; i < dirty.length; i++){
                    data.data[dirty[i].id.replace("book_review__","")] = $(dirty[i]).val();
                }
                data.data['book_id'] = $("#book__book_id").val();

                $.ajax({
                    url:   uri+"/svc/create",
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    async: true,
                    success: function(ret){
                        if(ret != -1){
                            clearDirty("#book_center input.book_review");

                            $.ajax({
                                url: uri+"/svc/update",
                                async: true,
                                dataType: 'json',
                                data: {
                                    id   : $("#book_review__book_review_id").val(),
                                    cls  : "book_review",
                                    data : {
                                        book_review_id: ret.book_review_id
                                    }
                                }
                            });
                            showStatus("Book Review Saved!");
                        } else {
                            showStatus("Create Failed...");
                        }
                    },
                    error: function(ret){
                        showStatus("Create Failed...");
                    }
                });

            }
        }

        if(!saving){
            hideStatus();
        }
    }; 

    showStatus("Saving...",false, saveFunc);
    
}
    
    var filterTimeoutProc = false;

function filterBook(){
    if(filterTimeoutProc != false) clearTimeout(filterTimeoutProc);
    filterTimeoutProc = setTimeout(execFilter,500);
}

function execFilter(offset, order){
    if(typeof offset == "undefined"){
        search_offset = 0;
    } else {
        search_offset = offset;
    }
    if(typeof order != "undefined"){
        search_order = order;
    }


    showStatus("Searching...",false, function(){

        filterTimeoutProc = false;
    
        $("#book_form").css("display","none");
        $("#book_list").css("display","");

        var params = $("#book_search").val();
        
        if(search_query != params) search_offset = 0;
        search_query = params;



        if(params != ""){
            $("#book_list ul").html('');
            var authorsSplit = params.split(" ");
            var authorsQuery = [];
            for(var i in authorsSplit){
                if(authorsSplit[i].toLowerCase() != "and" && authorsSplit[i].length >=3){
                    authorsQuery.push(['authors','like','%'+authorsSplit[i]+'%']);
                    authorsQuery.push('OR');
                }
            }
            authorsQuery.splice(-1,1);

            var query = [['title','like','%'+params+'%'],'OR',authorsQuery];

            $.ajax({
                url: uri+"/books/searchBooks",
                type: 'GET',
                dataType: 'json',
                data: {
                    'query'  : params,
                    'select' : ['title','authors'],
                    'limit'  : 25,
                    'offset' : search_offset,
                    'order'  : search_order
                },
                async: true,
                success: function(ret){
                    procReturn(ret);
                    hideStatus();

                },
                error: function(ret){
                    showStatus("Finding Books failed.");
                }
            });
    
        } else {
            hideStatus();
            $("#book_list").css("display","none");
            $("#book_list ul").html("");
            $("#book_form").fadeIn();
            $("#book_left div.button2").fadeOut();
        }
    });
}

function toggleSearchResults(){
    var el = $('#book_list');

    if($(el).css('display') != "none"){
        $('#book_list').css('display', 'none');
        $('#show_search_results').html("Show Search Results");
    } else {
        $('#book_list').css('display','');
        $('#show_search_results').html("Hide Search Results");
    }



}

function assignBookReview(book_id, book_review_id, journal_id, review_type_id){
    if(book_review_id){
        var data = {
            id : book_review_id,
            cls : "BookReview",
            data : {
            }
         };

         if(journal_id) data.data.journal_id = journal_id;
         if(review_type_id) data.data.review_type_id = review_type_id;

        $.ajax({
            url: uri+"/svc/update",
            type: 'POST',
            dataType: 'json',
            data: data,
            async: true,
            success: function(ret){
                if(ret != -1){
                    showStatus("Book Review Assigned!");
                } else {
                    showStatus("Error on saving book review.");
                }
            },
            error: function(ret){
                showStatus("Error on saving book review.");
            }
        });
    } else {
        var data = {
            cls  : "BookReview",
            data : {
                book_id    : book_id,
                journal_id : journal_id,
                review_type_id: review_type_id
            }
        };

        $.ajax({
            url:   uri+"/svc/create",
            type: 'POST',
            dataType: 'json',
            data: data,
            async: true,
            success: function(ret){
                if(ret != -1){
                    $("#book_review__"+ret.book_id+"__book_review_id").val(ret.book_review_id);
                    showStatus("Book Review Assigned!");
                } else {
                    showStatus("Creating Book Review Failed...");
                }
            },
            error: function(ret){
                showStatus("Create Failed...");
            }
        });
    }
}

function addByISBN(ISBN){
    showStatus("Querying for Book...",false);

    $.ajax({
        url: uri+"/svc/bookByISBN",
        type: "POST",
        dataType: "json",
        data: {
            isbn: ISBN
        },
        async: true,
        success: function(ret){

            var book = ret.responseData.results[0];
            if(book){

								var res = $.ajax({
										url: uri+"/svc/read",
										type: 'GET',
										async: false,
										dataType: 'json',
										data: {
											cls: 'BookMarketingInfo',
											query: [
												['isbn', book.ISBN]
											]
										}
								});

								eval("bookres = "+res.responseText);

								if(bookres.results.length != 0){
									alert("Sorry. Book Already Exists in Database");
									return;
								}


                var res = $.ajax({
                    url: uri+"/svc/create",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        cls: "BookMarketingInfo",
                        data: {
                            isbn: book.ISBN,
                            pages: book.pageCount,
                            'thumbnail_url': book.tbUrl
                        }
                    },
                    async: false
                });
                
                if(res != -1){
                    eval("bmi = "+res.responseText);

                    var d = new Date();

                    var res = $.ajax({
                        url: uri+"/svc/create",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            cls: "Book",
                            data: {
                                title: book.title,
                                authors: book.authors,
                                year: book.publishedYear,
                                date_added: Math.floor(new Date().getTime()/1000),
                                synopsis: book.synopsis,
                                publisher: book.publisher,
                                book_marketing_info_id: bmi.book_marketing_info_id
                            }
                        },
                        async: false
                    });

                    if(res != -1){
                        eval("res = "+res.responseText);
                        showStatus("Book Created!");
                        loadBook(res.book_id);

                    } else {
                        showStatus("Book Creation Failed.");
                    }

                } else {
									showStatus("Book Creation Failed.");
								}
            } else {
                hideStatus();
                alert("No Books Found");
            }
            
        },
        error: function(ret){
            showStatus("Error on ISBN Search");
        }
    });

}
