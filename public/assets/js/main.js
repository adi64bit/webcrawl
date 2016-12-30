jQuery(document).ready(function($){
	$('a[data-href]').click(function(){
		var href = $(this).attr('data-href');
		$.ajax({
		  method: "GET",
		  url: href,
		  success : function(response){
		  	$('#content-push').html(response);
		  },
		  error : function(msg){
		  	notify(msg['status'],href +' '+ msg['statusText'],'','danger','');
		  	console.log(msg);
		  	return false;
		  }
		});
		return false;
	});

	$('body').on('click', 'button[data-target="#immModal"]', function(evt){
		var contentUrl = $(this).attr('data-content-url');
		$.ajax({
		  method: "GET",
		  url: contentUrl,
		  success : function(response){
		  	$('#immModal #modal-push').html(response);
		  },
		  error : function(msg){
		  	$('#immModal #modal-push').html(msg['statusText']);
		  	notify(msg['status'],href +' '+ msg['statusText'],'','danger','');
		  }
		});
		return true;
	});

	function notify(title,msg,url,type,icon){
		$.notify({
			// options
			icon: icon,
			title: title,
			message: msg,
			url: url
		},{
			// settings
			element: 'body',
			position: null,
			type: type,
			allow_dismiss: true,
			newest_on_top: false,
			showProgressbar: false,
			placement: {
				from: "top",
				align: "right"
			},
			offset: 20,
			spacing: 10,
			z_index: 1031,
			delay: 5000,
			timer: 1000,
			url_target: '_blank',
			mouse_over: null,
			animate: {
				enter: 'animated fadeInDown',
				exit: 'animated fadeOutUp'
			},
			onShow: null,
			onShown: null,
			onClose: null,
			onClosed: null,
			icon_type: 'class',
			template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
				'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
				'<span data-notify="icon"></span> ' +
				'<span data-notify="title">{1}</span> ' +
				'<span data-notify="message">{2}</span>' +
				'<div class="progress" data-notify="progressbar">' +
					'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
				'</div>' +
				'<a href="{3}" target="{4}" data-notify="url"></a>' +
			'</div>' 
		});
	}
});