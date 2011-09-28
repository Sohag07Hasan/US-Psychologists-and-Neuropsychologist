jQuery(document).ready(function($){	
	
	var email = null;
	var prob = null;
	var check = null;
	$('.entry-doc').click(function(){
			return false;
	});
//	$('.entry-doc').css({'margin-left':'150px','margin-bottom':'15px'});
	$('.entry-title').css({'margin-left':'2px','margin-bottom':'15px'});
	$('#page-title').css({'margin-left':'300px'});
	
	$('.submitcheck').click(function(){
		var email = trim($('#emailchecking').val());
		var postid = trim($('#tracingnum').val());
		if(emailchecking(email) == false){
			alert('OOps! Invalid Email');
			classadding(true,false,'#emailchecking');
			return false;
		}
		else{
			classadding(false,true,'#emailchecking');
			if(alphaneumeric(postid) == false){
				alert('OOps! Invalid Tracing Number');
				classadding(true,false,'#tracingnum');
				return false;
			}
		}		
	});
	
	function alphaneumeric(value){
		var alphanum=/[0-9a-bA-B]+$/; //This contains A to Z , 0 to 9 and A to B
		//if(value.match(alphanum)){
		if(alphanum.test(value) == true){
			return true;
		}
		else{
			
			return false;
		}
	}
	
	$('#doctor-form-submit').click(function(){
		var name = trim($('#firstname').val()+' '+$('#lastname').val());
		var city = trim($('#city1').val());
		var email = $('#email').val();
		
		var phone = $("#telephone").val();
		var zip = $("#zip1").val();
		var hidden = $('#hiddencheck').val();
		
		if(problem(name,4)){
			classadding(false,true,'#firstname');
			if(problem(city,4)){
				classadding(false,true,'#city1');
				if(zipcheck(zip,true)){
					classadding(false,true,'#zip1');
					if(zipcheck(phone,false)){
						classadding(false,true,'#telephone');											
						if(emailchecking(email)){						
							if(regchecking(email,hidden)){
								alert('Sorry! Your email has already been registered');
								classadding(true,false,'#email');
								return false;
								}
							else{
								classadding(false,true,'#email');								
							}
						}
						else{
							alert('Invalid Email.Please try with a valid one');
							classadding(true,false,'#email');						
							return false;
						}
					}
					else{
						alert('Please Check Your Phone Number');
						classadding(true,false,'#telephone');							
						return false;
					}	
					
				}
				else{
				alert('Zip Code should be 5 digits');
				classadding(true,false,'#zip1');
				return false;
				}
			}
			else{
				alert('Please Check The following redmarked fields');
				classadding(true,false,'#city1');
				
				return false;
			}
		}
		else{
			alert('Your Name should have at least 5 characters');
			classadding(true,false,'#firstname');
			
			return false;
		}		
				
		
	});
	
	function zipcheck(zip,z){
		var num = /^-{0,1}\d*\.{0,1}\d+$/;
		if(num.test(zip) == true){
			if(z){
				if(zip.length == 5){
					return true;
				}
				else{
					return false;
				}
			}
			else{
				return true;
			}
		}
		else{
			return false;
		}
	}
	
	// remove multiple, leading or trailing spaces
	function trim(s) {
		s = s.replace(/(^\s*)|(\s*$)/gi,"");
		s = s.replace(/[ ]{2,}/gi," ");
		s = s.replace(/\n /,"\n");
		return s;
	}
	
	function problem(t,check){
		if(t.length>check){
			return true;
		}
		else{
			return false;
		}
	}
	
	function emailchecking(email){
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if(reg.test(email) == false) {
		  return false;
		}
		else{
			return true;			
		}
	}
	
	function regchecking(email,hidden){
		var y = false;
		$.ajax({						
			async: false,
			type:'post',
			url:PsyAjax.ajaxurl,
			dataType: "html",
			cache:false,
			timeout:1000,
			data:{
				'action':'email_verification',
				'email':email,
				'hidden':hidden									
			},
			
			success:function(result){
										
				if(result == 'y'){
					y = true;
				}
				else{
					y = false;
				}												
			},
			
			error: function(jqXHR, textStatus, errorThrown){
				jQuery('#footer').html(textStatus);
				alert(textStatus);
				return false;
			}			
		});	
		
		return y;
	}
	
	function classadding(a,b,tyor){
		if(a){
						
			$(tyor).css({'background-color':'#F8A0A0'});			
		}
		if(b){
			
			$(tyor).css({'background-color':'#F9F9F9'});
		}
	}

	
	// data checiking
	
	
	$('#already').click(function(){
		$('#checkform').toggleClass('formhidden');
		//$('#checkform').removeClass('formhidden');
		//$('#checkform').addClass('formshown');
		return false;
	});	
	
	$('.forget').click(function(){
		email = prompt('Please Insert Your Registered Email');
		
		if(email){			
			//ajax calling
			$.ajax({						
			async: true,
			type:'post',
			url:PsyAjax.ajaxurl,
			dataType: "html",
			cache:false,
			timeout:10000,
			data:{
				'action':'psychologist_verification',
				'email':email,
				'nonce':PsyAjax.nonce							
			},
			
			success:function(result){				
					alert(result);								
			},
			
			error: function(jqXHR, textStatus, errorThrown){
				jQuery('#footer').html(textStatus);
				alert(textStatus);
				return false;
			}			
		});	
			
		}
		else{
			return false;
		}
		
		return false;
	});	
	
});
