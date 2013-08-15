$(document).ready(function(){

	$("#tabs").tabs();
	
	$("input, textarea").focus(function(){
		if($(this).is(".default")){
			$(this).addClass($(this).val());
			$(this).removeClass("default");
		    $(this).val("");
		}
	});
	$("input, textarea").blur(function(){
		if(!$(this).is(".default") && $(this).val() == ""){
			$(this).val(this.className);
			$(this).addClass("default");
		}
	});

	$("#author_yes").change(function(){
		if($(this).is(":checked")){
			$("#author_name_wrap").hide();
			$("#author_email_wrap").hide();
		} else {
			$("#author_name_wrap").show();
			$("#author_email_wrap").show();	
		}
	});

	$("input[name=t]").val(Math.round(new Date().getTime()/1000));


	$("#for-reviewers a, #for-aes a").each(function(){
		var parts = $(this).attr('href').split(".");
		if(parts.length > 1) $(this).addClass(parts[parts.length-1]);
	});






});
var resetSubmitContent = function(){
	$("#submit-content").show();
	$("#submit-thanks").hide();
}


var submitContent = function(){
	//clear "defaults"
	$("#submit-content .default").val("");
	$("#submit-error").hide();

	$.ajax({
		url: uri+"/svc/submitContent",
		dataType: 'json',
		data : {
				'submitter_name': $("input[name=submitter_name]").val(),
				'submitter_email': $("input[name=submitter_email]").val(),
				'title': $("input[name=title]").val(),
				'description': $("textarea[name=description]").val(),
				'price': $("input[name=price]").val(),
				'notes': $("textarea[name=notes]").val(),
				'material_url': $("input[name=material_url]").val(),
				'access_inst': $("textarea[name=access_inst]").val(),
				'author_name': $("input[name=author_name]").val(),
				'author_email': $("input[name=author_email]").val(),
				'ebook': $("input[name=ebook]").is(":checked") ? 1 : 0,
				'author_yes': $("input[name=author_yes]").is(":checked") ? 1 : 0,
				'hp_name': $("input[name=name]").val(),
				'hp_time': $("input[name=t]").val()
			},
		success: function(d, r){
			if(d.status == -1){
				$("#submit-error").show();
			} else {
				$("input[name=title]").val(''),
				$("textarea[name=description]").val($("textarea[name=description]")[0].className).addClass("default");
				$("input[name=price]").val('');
				$("textarea[name=notes]").val('');
				$("input[name=material_url]").val($("input[name=material_url]")[0].className).addClass("default");
				$("textarea[name=access_inst]").val($("textarea[name=access_inst]")[0].className).addClass("default");
				$("input[name=author_name]").val(''),
				$("input[name=author_email]").val(''),
				$("input[name=ebook]").attr('checked','checked');
				$("input[name=author_yes]").attr('checked',"checked");
				$("#author_name_name").hide();
				$("#author_email_wrap").hide();
				$("#submit-content").hide();
				$("#submit-thanks").show();
			}
		}

	});
}
