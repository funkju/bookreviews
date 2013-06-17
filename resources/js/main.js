if(!window.console) {
    window.console = new function(){
        this.log = function(str) {};
        this.dir = function(str) {};
    };
}


String.prototype.trim = function() {
    return this.replace(/^\s*(\S*(\s+\S+)*)\s*$/, "$1");
};

function jumpToAnchor(anchor) {
    window.location.hash = anchor;
}

function doLogin() {
    var u = $("#username").val();
    var p = $("#password").val();

    $.ajax({
        url: uri+"/svc/doLogin",
        type: 'POST',
        dataType: 'json',
        data: {username:u, password:p},
        async: false,
        success: function(ret){
            procReturn(ret);
        }
    });

}

function initInputs(selector) {
    $('.comboBoo').each(function(el){
        var readonly = el.hasClass('readonly');

        var my_select = new MavSelectBox({
            elem: el,
            onSelect: el.onselect
        });
        if(!readonly) {
            my_select.elementSelect.bind('mouseover',function(){
                this.addClass('hover');
            });
            y_select.elementSelect.bind('mouseout',function(){
                ul = $(this).getChildren('ul')[0];
                if(ul.style.visibility != "visible"){
                    this.removeClass('hover');
                }
            });
        } else {
            my_select.elementDisplay.removeEvents()
            my_select.elementOptions.destroy();
            my_select.element.destroy();
            my_select.elementDisplay.destroy();
        }
        my_select.element.mavselect = my_select;
    });



    onChangeFunc = function(){
        if($(this).type == "radio"){
            $('input[name='+this.name+']').each(function(el){
                $(el).dirty = 1;
                $(el).addClass("dirty_input");
            });
        }

        $(this).addClass("dirty_input");
        $(this).dirty = 1;
    }

    $(selector).each(function(idx,el){
        if(!$(el).dirty) $(el).dirty = 0;
        $(el).bind('change',onChangeFunc);
        $(el).bind('keydown',onChangeFunc);
    });

    $("[disabled]").addClass("disabled");

}

function clearInputs(selector){
    $(selector).each(function(idx,el){
        $(el).dirty = 0;
        $(el).removeClass("dirty_input");

        if(el.tagName != "div") $(el).val("");
        else $(el).html("&nbsp;");
    });
}

function clearDirty(selector){
    $(selector).each(function(idx, el){
        $(el).removeClass("dirty_input");
    });
}

function goTo(href) {
    window.location = href;
}


var hideStatusProc = null;

function showStatus(text, hide, callback){
    if(typeof hide == "undefined") hide = true;
    
    $("#status_message").html(text);

    if(hideStatusProc){
        window.clearTimeout(hideStatusProc);
        if(typeof callback == "function") callback();
    } else {
        $('#status_message').fadeIn(100,callback);
    }

    if(hide) hideStatusProc = window.setTimeout(hideStatus,5000);

    return $(this);
}

function hideStatus(){
    $('#status_message').fadeOut();
    hideStatusProc = null;
}

//By Brandon Aaron
jQuery.fn.swap = function(b) {
    b = jQuery(b)[0];
    var a = this[0],
        a2 = a.cloneNode(true),
        b2 = b.cloneNode(true),
        stack = this;

    a.parentNode.replaceChild(b2, a);
    b.parentNode.replaceChild(a2, b);

    stack[0] = a2;
    return this.pushStack( stack );
};

function bookModal(book_id){
    $.ajax({
        url:  uri+"/svc/call",
        type: 'GET',
        dataType: 'json',
        data: {
            cls: 'Book',
            fn: 'getBookModal',
            params: [book_id]
        },
        success: function(ret){
          procReturn(ret);
        }
    });
}
