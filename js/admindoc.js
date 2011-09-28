jQuery(document).ready(function($){	
	
	$('.individualemail').click(function(){
				
		var spanid = regGet($(this).attr('id'));
		
		$('#textarea-'+spanid).toggleClass('customtextarea');
		return false;
	});
	
	
	$(".send").click(function(){
		var sendid = regGet($(this).attr('id'));
		//ajax calling
		var content = $('#text-'+sendid).val();
		var subject = $('#ind-'+sendid).val();			
		m = eamilsening(sendid,content,subject);
		if(m){
			alert(m);
		}	
		return false;
	});
	
	function eamilsening(sendid,content,subject){
		var message = '';		
		$.ajax({						
			async: false,
			type:'post',
			url:PsyAjax.ajaxurl,
			dataType: "html",
			cache:false,
			timeout:10000,
			data:{
				'action':'psychologist_email',
				'pid':sendid,
				'text':content,
				'subject':subject										
			},
			
			success:function(result){				
				message = result;
				return false;								
			},
			
			error: function(jqXHR, textStatus, errorThrown){
				jQuery('#footer').html(textStatus);
				alert(textStatus);
				return false;
			}
					
		});
		return message;		
	}
		
	
	function regGet(str){
		var reg=/[0-9]+/;		
		
		//var reg1=/<postid>|<\/postid>/g;
		if(reg.test(str)){							
			var todoStr=reg.exec(str);						
			todoStr=String(todoStr);
			//alert(todoStr);
			return todoStr;
		}				
	}
	
	$('#basetemailservice').click(function(){
		$('#blastclass').toggleClass('customtextarea');		
		return false;
	});
	
	$('#sendmail').click(function(){
		var subject = $('#blustsubject').val();
		var content = $('#balstmail').val();
		var sendid = 'all';		
		
		m = eamilsening(sendid,content,subject);
		if(m){
			alert(m);
		}	
		return false;
	});
					
	
});
