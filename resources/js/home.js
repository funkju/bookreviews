function dismissAttachmentMsg(){
	showStatus('Dismissing...',false,function(){
		$.ajax({
			url: uri+'/svc/call',
			dataType: 'json',
			method: 'POST',
			data: {
				cls: 'Person',
				fn: 'updateLastLogin',
				params: [user_id]
			}
			success: function(ret){
					if(ret != -1){
						hideStatus();
						$("#new_attach_status").fadeOut();
					} else {
						showStatus("Some Error Occurred.");
						$("#new_attach_status").fadeOut();
					}
			}
		});
	});
}
