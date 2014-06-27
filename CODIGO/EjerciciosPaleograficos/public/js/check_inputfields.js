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
	
	function check_dni(campo){
		var dni = $(campo).val();
	   	var numero = dni.substring(0,dni.length-1);
		var l = dni.substring(dni.length-1,dni.length);
		var letra = l.toUpperCase();
		var letras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E', 'T'];
		
		if(numero<0 || numero>99999999){
			return false;
		}else{
			var letraObtenida = letras[numero%23];
			if(letraObtenida!=letra){
				return false;
			}
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
	
	function check_empty(campo,mensaje){
		if($(campo).val() == ""){
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
	
	function set_tooltip_left(campo,mensaje){
		$(campo).qtip({ 
			content: mensaje,
            style: {
                classes: 'qtip-blue'
            },
            position: {
                my: 'right center',  // Position my top left...
                at: 'left center', // at the bottom right of...
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
	
	function set_tooltipBD(campo,mensaje){
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
         	hide: 'unfocus'
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
	
	function dialogue(content, title) {
        $('<div />').qtip({
            content: {
                text: content,
                title: title
            },
            position: {
                my: 'center', at: 'center',
                target: $(window)
            },
            show: {
                ready: true,
                modal: {
                    on: true,
                    blur: false
                }
            },
            hide: false,
            style: {classes: 'qtip-ubupaleodialog'
            },
            events: {
                render: function(event, api) {
                    $('button', api.elements.content).click(function(e) {
                        api.hide(e);
                    });
                },
                hide: function(event, api) { api.destroy(); }
            }
        });
    }
	
	
	
	
	
	
