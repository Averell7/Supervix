$(document).ready(function(){
	
// --------------------------------------------------------- 

		$("#enregistrer_parametres").on("click", function(e) {
			e.preventDefault();
			
			var effacement = false;
/**/
			// Contrôle de la modification des paramètres FTP
			if( $("#ftp_ftp").val() != $("#ftp_ftp_actuel").val() ||
					$("#ftp_login").val() != $("#ftp_login_actuel").val() ) {
			
					if( !confirm(	alerte["ready_nav7_3"]+" !\n\n"
											+ alerte["ready_nav7_4"]+".\n\n"
											+ alerte["ready_nav7_5"]+"\n"
											+ alerte["ready_nav7_6"]+".\n\n"
											+ alerte["ready_nav7_7"] )) {
					//if( !confirm("Attention !\n\n"
					//					+ "La modification des paramètres FTP est critique.\n\n"
					//					+ "Elle va entraîner l'effacement total\n"
					//					+ "de la configuration actuelle.\n\n"
					//					+ "Continuer ?" )) {
						return;
					}
					// Effacement complet du contenu de /home/rock64/idefix
					effacement = true;
			 }
		
			// ----- Filtrage DNS
			var dns_filtering = $("#filtreDNS option:selected").val();
			var dns_nameserver1, dns_nameserver2;
			switch(dns_filtering) {
				case "auto"						:	/* Pas de sauvegarde des serveurs DNS */
																dns_nameserver1 = null;
																dns_nameserver2 = null;
																break;
				case "SafeDNS"				:	/* Sauvegarde des serveurs DNS */
				case "OpenDNS"				:
																dns_nameserver1 = $("#dns_nameserver1").val();
																dns_nameserver2 = $("#dns_nameserver2").val();
																break;
				case "other"					:
																dns_nameserver1 = $("#dns_nameserver1").val();
																dns_nameserver2 = $("#dns_nameserver2").val();
																break;
				case "None"					:	/* suppr 09-03-2019 : Pas de sauvegarde des serveurs DNS ? */
																dns_nameserver1 = $("#dns_nameserver1").val();
																dns_nameserver2 = $("#dns_nameserver2").val();
																break;
				default								:
																dns_nameserver1 = null;
																dns_nameserver2 = null;
																break;
			}
			
			// ----- IP dynamique ou fixe
			var ip_type = $('input[name=ip_dyn_fixe]:checked').val();
			var choix_type_dynfixe, dyn_ip_handler;
			var ddclient_login, ddclient_password, ddclient_domain, ddclient_server, ddclient_web;
						
			switch(ip_type) {
				case	'static'				:
																/* Pas de sauvegarde des paramètres */
																dyn_ip_handler			= null;
																ddclient_login			= null;
																ddclient_password		= null;
																ddclient_domain			= null;
																ddclient_server			= null;
																ddclient_web				= null;
																break;
																
				case	'dynamic'	:
																dyn_ip_handler			= $("#typeDNS option:selected").val();
																switch(dyn_ip_handler) {
																	case "auto"						:	/* Pas de sauvegarde des paramètres */
																													dyn_ip_handler		= dyn_ip_handler;
																													ddclient_login		= null;
																													ddclient_password	= null;
																													ddclient_domain		= null;
																													ddclient_server		= null;
																													ddclient_web			= null;
																													break;
																	case "noip"						:	/* Sauvegarde des paramètres */
																	case "SafeDNS"				:
																	case "OpenDNS"				:
																													dyn_ip_handler	= dyn_ip_handler;
																													ddclient_login		= $("#ddclient_login").val();
																													ddclient_password	= $("#ddclient_password").val();
																													ddclient_domain		= $("#ddclient_domain").val();
																													ddclient_server		= $("#ddclient_server").val();
																													ddclient_web			= $("#ddclient_web").val();
																	case "None"				: /* Pas de sauvegarde des paramètres */
																													dyn_ip_handler			= null;
																													ddclient_login			= null;
																													ddclient_password		= null;
																													ddclient_domain			= null;
																													ddclient_server			= null;
																													ddclient_web				= null;
																													break;
																	case "DynDNS"					:	/* Non implémenté : futur ? */
																	default								:
																}
																break;
																
				default								: alert( alerte["ready_nav7_1"] );	// Erreur : choix non implémenté.
																break;
			}

			var datasConfigNetwork = {
				/* champs traités par config-reseau.php */
				
				effacement					: effacement,
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

        dns_filtering 			: dns_filtering,
        dns_nameserver1			: dns_nameserver1,
        dns_nameserver2			: dns_nameserver2,

				ip_type							: ip_type,
				dyn_ip_handler			: dyn_ip_handler,
				ddclient_login      : ddclient_login,
				ddclient_password	  : ddclient_password,
				ddclient_domain     : ddclient_domain,
				ddclient_server     : ddclient_server,
				ddclient_web        : ddclient_web,
				protocol						: 'dyndns2'
			};
					
			// On vérifie (basiquement) que les champs sont tous saisis :	
		
			var msg = "";
      for (var cle in datasConfigNetwork) {
        /*alert('Clé : ' + cle
                + "\nvaleur = *" + datasConfigNetwork[cle] + "*");*/
				if(datasConfigNetwork[cle] == ""
						&& cle != "effacement"
						&& cle != "idefix_id"
						&& cle != "lan_ip"
						&& cle != "lan_netmask"
						&& cle != "lan_subnet"
						&& cle != "proxy_http_port"
						
						&& cle != "lan_network"
						&& cle != "dhcp_begin"
						&& cle != "dhcp_end"
						&& cle != "lan_broadcast") {
					msg += "\t" + cle + "\n";
				}
			}
			
			if(msg != "") {
				alert( alerte["ready_nav7_2"]+" :\n\n" + msg);	// Veuillez compléter les champs suivants
			} else {
				var demande = {
					url			: "ajax_write_conf_internet.php",
					type		: "POST",
					dataType: 'html',
					data		: {	datasConfigNetwork: datasConfigNetwork
										},
					success	: function(retour){
											//$("#result").html(retour);
											$(".colonne_1").html("");
											$(".colonne_2").html("<div class='message'>"+retour+"</div>");
											//alert(retour);
										},
					error		: function(retour){ alert( alerte["erreur_SP"]+' SP_05.'); }
					};
				$.ajax(demande);
			} 
			
		});

// --------------------------------------------------------- 
	
		$("#verifier_filtrage_DNS").on("click", function(e) {
			e.preventDefault();
			var domaine = $("#urlSite_choix").val();
			//	alert('Non implémentée à ce jour.\n' + domaine);
			if(domaine != '') {
			
				var demande = {
						url			: "ajax_verif_blocage_par_dns.php",
						type		: "POST",
						dataType: 'json',
						data		: {	domaine: domaine
											},
						success	: function(retour){
												$("#info_blocageDNS").html(retour[0]);
												if(retour[1] != "" ) $("#urlList").html(retour[1]);
												$("#urlSite_choix")
															.val('')
															.focus();
												$("#verifier_filtrage_DNS")
															.attr({"disabled":"disabled"});
											},
						error		: function(retour){ alert('Erreur SP_13.' + retour[0]); }
						};
				$.ajax(demande);

			}
		});
		
		var boutons = new Array;

		$("#urlSite_choix").on('input', function(e) {
			e.preventDefault();
			if( $(this).val() != '') {
				boutons["verifier_filtrage_DNS"]			= true;
			} else {
				boutons["verifier_filtrage_DNS"]			= false;
			}
		  Active_Boutons(boutons);
		});

		$("#urlSite_choix").trigger('input');		
	
	/* --------------------------------------------------------- *\
			(Dés)active boutons
	\* --------------------------------------------------------- */
		
	function Active_Boutons(boutons) {
		for (var cle in boutons) {
			var valeur = boutons[cle];
			if (boutons.hasOwnProperty(cle)) {
				cle = "#" + cle;
				if( valeur == true ) {								// true : on active le bouton
					$(cle).removeAttr("disabled");
          $(cle+">img").css({"opacity":"1"});
				} else {															// false : on désactive le bouton
					$(cle).attr("disabled","disabled");
					$(cle+">img").css({"opacity":"0.5"});
				}
			}
		}
  }
	
	/* --------------------------------------------------------- *\
			#filtreDNS change
  \* --------------------------------------------------------- */
  
  $("#filtreDNS").on("change", function(e) {
    e.preventDefault();
    var filtragedns = $("#filtreDNS option:selected" ).val();

    // --- Impossible d'avoir SafeDNS dans cette combobox
    // --- et OpenDNS dans l'autre et inversement

    switch(filtragedns) {
      case 'auto' : 
                $("#divcacher_1").css('visibility', 'hidden');
                $("#typeDNS option")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'SafeDNS' : // On désactive l'option dans la combobox "#typeDNS"
                $("#divcacher_1").css('visibility', 'hidden');
                $("#typeDNS option[value='" + 'OpenDNS' + "']")
                  .attr("disabled","disabled")
                  .css({"background-color":"#EBEBEB"});
                $("#typeDNS option[value='" + filtragedns + "']")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'OpenDNS' : // On désactive l'option dans la combobox "#typeDNS"
                $("#divcacher_1").css('visibility', 'hidden');
                $("#typeDNS option[value='" + 'SafeDNS' + "']")
                  .attr("disabled","disabled")
                  .css({"background-color":"#EBEBEB"});
                $("#typeDNS option[value='" + filtragedns + "']")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'other' : 
								if($("#dns_nameserver1").val() == "8.8.8.8") {
									$("#dns_nameserver1").val("195.46.39.39");
									$("#dns_nameserver2").val("195.46.39.40");
								}

                $("#divcacher_1")
                  .css('visibility', 'visible');
                $("#typeDNS option")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                //$("#dns_nameserver1, #dns_nameserver2")
                //  .removeAttr('readonly'); 
                break;
      case 'None' : 
                //$("#divcacher_1").css('visibility', 'hidden'); // modif 09-03-2019
								if($("#dns_nameserver1").val() == "195.46.39.39") {
									$("#dns_nameserver1").val("8.8.8.8");
									$("#dns_nameserver2").val("8.8.4.4");
								}

                $("#divcacher_1")
                  .css('visibility', 'visible');
                break;
      default : 
                break;
    }
  });    
  $("#filtreDNS").trigger('change');

	/* --------------------------------------------------------- *\
			#typeDNS change
	\* --------------------------------------------------------- */

  $("#typeDNS").on("change", function(e) {
    e.preventDefault();
    var dns = $("#typeDNS option:selected" ).val();

    switch(dns) {
      case 'auto' : 
                //$("#ddclient_server").val( choixDNS['automatique']['ddclient_server'] );
                //$("#ddclient_web").val( choixDNS['automatique']['ddclient_web'] );
                //$("#ddclient_login_libelle").html( "Identifiant '" + dns + "'" );
                //$("#dclient_password_libelle").html( "Mot de passe '" + dns + "'" );
                $("#divcacher_2b").css('visibility', 'hidden');
                $("#filtreDNS option")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'noip' : 
                $("#ddclient_server").val( choixDNS['noip']['ddclient_server'] );
                $("#ddclient_web").val( choixDNS['noip']['ddclient_web'] );
                //$("#ddclient_login_libelle").html( 'Identifiant ' + dns );
                $("#ddclient_login_libelle").html( chaine["Identifiant"]+' ' + dns );
                //$("#dclient_password_libelle").html( 'Mot de passe ' + dns );
                $("#dclient_password_libelle").html( chaine["Mot_de_passe"]+' ' + dns );
                $("#divcacher_2b").css('visibility', 'visible');
								
                $("#filtreDNS option")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'SafeDNS' : 
                $("#ddclient_server").val( choixDNS['SafeDNS']['ddclient_server'] );
                $("#ddclient_web").val( choixDNS['SafeDNS']['ddclient_web'] );
                $("#ddclient_login_libelle").html( chaine["Identifiant"]+' ' + dns );
                $("#dclient_password_libelle").html( chaine["Mot_de_passe"]+' ' + dns );
                $("#divcacher_2b").css('visibility', 'visible');

                $("#filtreDNS option[value='" + 'OpenDNS' + "']")
                  .attr("disabled","disabled")
                  .css({"background-color":"#EBEBEB"});
                $("#filtreDNS option[value='" + dns + "']")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'OpenDNS' : 
                $("#ddclient_server").val( choixDNS['OpenDNS']['ddclient_server'] );
                $("#ddclient_web").val( choixDNS['OpenDNS']['ddclient_web'] );
                $("#ddclient_login_libelle").html( chaine["Identifiant"]+' ' + dns );
                $("#dclient_password_libelle").html( chaine["Mot_de_passe"]+' ' + dns );
                $("#divcacher_2b").css('visibility', 'visible');

                $("#filtreDNS option[value='" + 'SafeDNS' + "']")
                  .attr("disabled","disabled")
                  .css({"background-color":"#EBEBEB"});
                $("#filtreDNS option[value='" + dns + "']")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
                break;
      case 'DynDNS' : 
                $("#ddclient_server").val( choixDNS['DynDNS']['ddclient_server'] );
                $("#ddclient_web").val( choixDNS['DynDNS']['ddclient_web'] );
                $("#ddclient_login_libelle").html( chaine["Identifiant"]+' ' + dns );
                $("#dclient_password_libelle").html( chaine["Mot_de_passe"]+' ' + dns );
                $("#divcacher_2b").css('visibility', 'visible');
                $("#filtreDNS option")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});
      case 'None' :
                $("#divcacher_2b").css('visibility', 'hidden');
                $("#filtreDNS option")
                  .removeAttr('disabled')
                  .css({"background-color":"#ffffff"});

                break;
      default : 
                //$("#ddclient_server").val('');
                //$("#ddclient_web").val('');
                //$("#ddclient_login_libelle").html( 'Identifiant' );
                //$("#dclient_password_libelle").html( 'Mot de passe' );
                //$("#divcacher_2b").css('visibility', 'visible');
                break;
    }
  });
  $("#typeDNS").trigger('change');
	
// --------------------------------------------------------- 
	
	$("input[type=radio][name=ip_dyn_fixe]").change(function(e) {
    e.preventDefault();
		//alert('test = ' + $(this).val() )	;
		switch( $(this).val() ) {
      case 'dynamic' :	$("#divcacher_2a").css('visibility', 'visible');
                            var dns = $("#typeDNS option:selected" ).val();
                            if(dns == "auto") {
                              $("#divcacher_2b").css('visibility', 'hidden');
                            } else {
                              $("#divcacher_2b").css('visibility', 'visible');
                            }
                            break;
			case 'static' 			:	$("#divcacher_2a, #divcacher_2b").css('visibility', 'hidden');
														break;
		}
  });

	/* --------------------------------------------------------- *\
      Saisie des mots de passe
      
      https://stackoverflow.com/questions/7747536/jquery-trigger-keycode-ctrlshiftz-ctrlz-in-wysiwyg-textarea
      https://stackoverflow.com/questions/3781142/jquery-or-javascript-how-determine-if-shift-key-being-pressed-while-clicking-an

      keyup et keydown ne peut pas détecter les majuscules/minuscules.
      Seul keypress peut le faire !
      Mais dans ce cas escape, ctrl+z et autres frappes ne fonctionnent plus !
	\* --------------------------------------------------------- */

  var mdp_ftp_password_original = $("#ftp_password").val();
  var mdp_ddclient_password_original = $("#ddclient_password").val();
/*
  $("#ftp_password, #ddclient_password")
  .on('keyup keypress', function(e) {
      var event = e.type;
      var type = $(this).attr('type');
      var id = $(this).attr('id');
      var value = String.fromCharCode(e.which);
      alert( "event : " + e.type
       + "\n - type : " + type
       + "\n - id : " + id
       + "\n - value : " + value
      );

      $(this).attr('type', 'text').val(value);

  });

    var char = String.fromCharCode(e.which), 
     isUpper = char == char.toUpperCase(); 
   console.log(char + ' is pressed' + (isUpper ? ' and uppercase' : '')) 

*/

if( $("#ftp_password").val() == "" ) $("#ftp_password").attr('type', 'text');
if( $("#ddclient_password").val() == "" ) $("#ddclient_password").attr('type', 'text');

  $("#ftp_password, #ddclient_password").on("keyup", function(e) {
    e.preventDefault();
    var type = $(this).attr('type');
    var id = $(this).attr('id');

    if(type == 'password'){
      var value = String.fromCharCode(e.keyCode);
      $(this).attr('type', 'text').val('');
    } else {
      if(e.which === 27 || (e.which === 90 && e.ctrlKey)) {
        $(this).attr('type', 'password');
        if(id == 'ftp_password') $(this).val(mdp_ftp_password_original);
        if(id == 'ddclient_password') $(this).val(mdp_ddclient_password_original);
      }
    }

  });  

// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 
	
});




