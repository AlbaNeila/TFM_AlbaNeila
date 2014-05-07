	function check_names(){
		if(!(/^[a-zA-ZñÑ\s]*$/.test($("#username").val()))){
			return false;
		}
		return true;
	}
	
	function check_email(campo){
		if(!(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/.test($(campo).val()))){
			return false;
		}
		return true;
	}
	
	function check_user(campo){
		if(!(/^[a-zA-Z0-9]{3,15}$/.test($(campo).val()))){
			return false;
		}
		return true;
	}
	
	function check_password(campo){
		if(!(/(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9]{8,10})$/.test($(campo).val()))){ //(Entre 8 y 10 caracteres, por lo menos un digito y un alfanumérico, y no puede contener caracteres espaciales)
			return false;
		}
		return true;
	}
	
	function check_passwords(){
		if(($("#password").val() != $("#password2").val())){
			return false;
		}
		return true;
	}
	
	function check_empty(campo){
		if($(campo).val() == ""){
			$(campo).qtip({ 
				content: 'Este campo es requerido',
	            style: {
	                classes: 'qtip-blue'
	            },
	            position: {
	                my: 'left center',  // Position my top left...
	                at: 'right center', // at the bottom right of...
	                target: $(campo) // my target
	            },
	            show: {
	                event: false, // Don't specify a show event
	                ready: true // Show the tooltip when ready                        
	            },
	            hide: {
	                event: false,
	                inactive:2000
	            }
	        });
	        return true;
		}
		else{
			return false;
		}
	}
	
	function set_tooltip(campo,mensaje){
		debugger;
		$(campo).qtip({ 
			content: mensaje,
            style: {
                classes: 'qtip-blue'
            },
            position: {
                my: 'left center',  // Position my top left...
                at: 'right center', // at the bottom right of...
                target: $(campo) // my target
            },
            show: {
                event: false, // Don't specify a show event
                ready: true // Show the tooltip when ready                        
            },
            hide: {
                event: false,
                inactive:2500
            }
        });
	}
	
	function set_tooltipInfo(campo,mensaje){
		$(campo).qtip({ 
			content: mensaje,
            style: {
                classes: 'qtip-blue'
            },
            position: {
                my: 'left center',  // Position my top left...
                at: 'right center', // at the bottom right of...
                target: $(campo) // my target
            },
            show: {
                event: false, // Don't specify a show event
                ready: true // Show the tooltip when ready                        
            },
            hide: {
                event: 'unfocus'
            }
        });
	}
	
	
	
	
	
	
	
