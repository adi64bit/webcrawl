jQuery(document).ready(function($){
setNavigation();

	$('a').click(function(){
		if ($(this).attr('data-href')){
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
		} else {
			return true;
		}
	});

	$('#insertDomain').submit(function(e){
		e.preventDefault();
		var contentUrl = $(this).attr('action');
		var data = $(this).serialize();
		var url = $('input[name="url"]').val();
		console.log(contentUrl);
		$.ajax({
		  method: "POST",
		  data: data,
		  url: contentUrl,
		  success : function(msg){
		  	if(msg['status'] == 1){
		  		notify(url,'Domain Status 200 and Added to Queue','','info','');
		  	} else {
		  		notify(url,msg['message'],'','danger','');
		  	}
		  },
		  error : function(msg){
		  	notify('failed','check your internet connection','','danger','');
		  }
		});
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

	function setNavigation() {
	    var path = window.location.pathname;
	    path = path.replace(/\/$/, "");
	    path = decodeURIComponent(path);
	    var nothome = 0;

	    $(".nav a").each(function () {
	        var href = $(this).attr('href');
	        if (path.substring(0, href.length) === href) {
	            $(this).closest('li').addClass('active');
	            nothome = 1;
	        }
	    });

	    if(nothome == 0){
	    	$('.linkhome').addClass('active');
	    }
	}

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