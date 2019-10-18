$(document).ready(function(){
	
// --------------------------------------------------------- 
	
	$("#systeme_lan_netmask_cidr option[value='"+lan_netmask_cidr+"']").attr('selected', true);	
				
	$("#enregistrer_parametres").on("click", function(e) {
		e.preventDefault();
		
		var datasConfigNetwork = {
				/* champs traités par config-reseau.php */

				idefix_id			    	: $("#systeme_idefix_id").val(),
				lan_ip				    	: $("#systeme_lan_ip").val(),
				lan_netmask					: $("#systeme_lan_netmask").val(),
				lan_subnet		    	: $("#systeme_lan_subnet").val(),
				proxy_http_port	    : $("#systeme_proxy_http_port").val(),

				lan_network			    : $("#dhcp_lan_network").val(),
				dhcp_begin		    	: $("#dhcp_begin").val(),
				dhcp_end			    	: $("#dhcp_end").val(),
				lan_broadcast		    : $("#dhcp_lan_broadcast").val(),

				/* champs cachés venant des paramètres gérés par config-internet.php */

				ftp						    	: $("#ftp_ftp").val(),
				login					    	: $("#ftp_login").val(),
				password			    	: $("#ftp_password").val(),
				
        dns_filtering				: $("#dns_filtering").val(),
				dns_nameserver1			: $("#dns_nameserver1").val(),
				dns_nameserver2			: $("#dns_nameserver2").val(),
      
				ip_type							: $("#ip_type").val(),
        dyn_ip_handler			: $("#dyn_ip_handler").val(),
				ddclient_login      : $("#ddclient_login").val(),
				ddclient_password	  : $("#ddclient_password").val(),
				ddclient_domain     : $("#ddclient_domain").val(),
				ddclient_server     : $("#ddclient_server").val(),
				ddclient_web        : $("#ddclient_web").val(),
				protocol						: 'dyndns2'
				};

		// On vérifie (basique) que les champs sont tous saisis :	
				
		var msg = "";
		for (var cle in datasConfigNetwork) {
			if(datasConfigNetwork[cle] == ""
					&& cle != "ftp"
					&& cle != "login"
					&& cle != "password"
					
					&& cle != "dns_filtering"
					&& cle != "dns_nameserver1"
					&& cle != "dns_nameserver2"
					
					&& cle != "ip_type"
					&& cle != "dyn_ip_handler"
					&& cle != "ddclient_login"
					&& cle != "ddclient_password"
					&& cle != "ddclient_domain"
					&& cle != "ddclient_server"
					&& cle != "ddclient_web") {
				msg += "\t" + cle + "\n";
			}
		}

		if(msg != "") {
			alert(alerte["ready_nav4_1"] + " :\n\n" + msg);	// Veuillez compléter les champs suivants
		} else {
			var demande = {
				url			: "ajax_write_conf_reseau.php",
				type		: "POST",
				dataType: 'html',
				data		: {	datasConfigNetwork: datasConfigNetwork
									},
				success	: function(retour){
										alert(
											retour
										);
									},
				error		: function(retour){ alert( alerte["erreur_SP"]+' SP_03.'); }
				};
			$.ajax(demande);
		} 
		
	});

	// --------------------------------------------------------- 
	
	$("#systeme_proxy_http_port").mask("99999");
	
	$("#systeme_proxy_http_port").on("keyup", function(e) {
		e.preventDefault();
		if($(this).val() > 65535) {
			alert( alerte["ready_nav4_2"] );	// Le port doit être inférieur à 65535 !");	// : numérique, positif et compris entre 1000 et 65535
			$(this).val('');
		}
	});
	
	// --------------------------------------------------------- 

	$("#calcul").on("click", function(e) {
		e.preventDefault();

		// Validation de l'adresse IP
		var ipaddress = $("#systeme_lan_ip").val();
		var splitChaine = ipaddress.split('.');

		// Supprime les éventuels zéros en début de bloc
		var nb;
		for(var k = 0; k < 4; k++) {
			nb = parseInt(splitChaine[k]);
			splitChaine[k] = nb.toString();
		}
		ipaddress = splitChaine.join('.');

		if(	isNaN(splitChaine[0]) || splitChaine[0] < 1 || splitChaine[0] > 255 ||
				isNaN(splitChaine[1]) || splitChaine[1] < 0 || splitChaine[1] > 255 ||
				isNaN(splitChaine[2]) || splitChaine[2] < 0 || splitChaine[2] > 255 ||
				isNaN(splitChaine[3]) || splitChaine[3] < 1 || splitChaine[3] > 255 ) {
			alert( alerte["ready_nav4_3"] );		// Veuillez corriger l'adresse IP
			$("#systeme_lan_ip").focus();
		} else {
			$("#systeme_lan_ip").val(ipaddress);
			//var lan_netmask = $("#systeme_lan_netmask option:selected").val();
			var lan_netmask_cidr = $("#systeme_lan_netmask_cidr option:selected").val();


			var demande = {
				url			: "ajax_calcul_infos_reseau.php",
				type		: "POST",
				dataType: 'json',
				data		: {	ipaddress					: ipaddress,
										lan_netmask_cidr	: lan_netmask_cidr
									},
				success	: function(retour){
									/*
									alert(
										retour["Supernet"] 
									);
									*/
									$("#systeme_lan_subnet").val( retour["systeme_lan_subnet"]+"\/"+retour['Supernet'] ).css({"background-color":"#C8FFFF"});
									// Caché :
									$("#systeme_lan_netmask").val( retour["systeme_lan_netmask"] ).css({"background-color":"#E0FFE0"});

									$("#dhcp_lan_network").val( retour["systeme_lan_subnet"] ).css({"background-color":"#C8FFFF"});
									
									$("#dhcp_begin").val( retour["dhcp_begin"] );
									$("#dhcp_end").val( retour["dhcp_end"] );

									$("#dhcp_lan_broadcast").val( retour["dhcp_lan_broadcast"] ).css({"background-color":"#C8FFFF"});
									},
				error		: function(retour){ alert( alerte["erreur_SP"]+' SP_14.'); }
				};
	$.ajax(demande);

		}
		
	});
    
  /*----------------------------------------------------------*\
		Lancement automatique des calculs si :
		
		1) Au lancement d'une nouvelle configuration
		2) Modification de l'adresse IP (#systeme_lan_ip)
		3) Changement de classe réseau (#systeme_lan_netmask_cidr)

		SAUF au chargement d'une configuration existante.
		Mais ensuite toute modification de #systeme_lan_ip ou
		#systeme_lan_netmask_cidr réactive les calculs
  \*----------------------------------------------------------*/
  
  if( $("#systeme_lan_ip").val() != "") {
    if(confNouvelle) $("#calcul").trigger('click');
  }

  $('#systeme_lan_netmask_cidr').on('change', function(){
    if(confNouvelle) $("#calcul").trigger('click');
  });

  confNouvelle = true;

  $('#systeme_lan_ip').on('keyup', function(){
    $("#calcul").trigger('click');
  });


// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 
	
});



