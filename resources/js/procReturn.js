function procReturn(ret) {

    for (var i =0; i< ret.length; i++) {
        var item = ret[i];
        
        var els = [];
        if(typeof item.id != "undefined"){
            if($("#"+item.id)){
                els = $("#"+item.id);
            }
        }
        if( typeof item.tag != "undefined") {
            els = $$(item.tag);
        }
        for(var j = 0; j < els.length; j++){
            el = els[j];

            if(typeof item.html != "undefined") {
                //console.log(item);
                if(typeof item.append != "undefined" && item.append) {
                    el.innerHTML = el.innerHTML + item.html;
                } else {
                    el.innerHTML = item.html;
                }
                initInputs();
            }
            if(typeof item.style != "undefined"){
                el.style[item.name] = item.style.value;
            }
            if(typeof item.value != "undefined"){
                if(el.type == 'text'){
                    el.value = item.value;
                } else if(el.type == 'checkbox'){
                    if(item.value == "1") {
                        el.checked = true;
                    } else {
                        el.checked = false;
                    }
                } else if(el.type == 'select-one'){
                    for(var i = 0; i < el.options.length; i++) {
                        if(el.options[i].value == item.value) {
                            if(el.selectedIndex != i) {
                                el.selectedIndex = 1;
                            }
                        }
                    }
                } else if (el.type == 'textarea') {
                    el.value = item.value;
                } else {
                    /*console.log(item.id, item.value);*/
                }
            }
        }
        
        if(typeof item.js != "undefined") {
            eval(item.js);
        }
        if(typeof item.exception != "undefined") {
            showStatus("Exception: " + item.exception, 5000, 'red');

        }
        if(typeof item.status != "undefined") {
            showStatus(item.status);
        } 
        if(typeof item.load_finished != "undefined"){
            if(!item.load_finished){
                load();
            }
        }
        if(typeof item.message != "undefined"){
                showStatus(item.message, item.duration, item.color, item.fontcolor, item.wait);
        }
    }
}



