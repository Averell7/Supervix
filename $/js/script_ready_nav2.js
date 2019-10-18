/* sur la base de script_ready_nav8.js */
$(document).ready(function(){
	
// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 
function formatDate(date) {
	/*var monthNames = [
		"January", "February", "March",
		"April", "May", "June", "July",
		"August", "September", "October",
		"November", "December"
	];*/
	var monthNames = [
		"janvier", "février", "mars",
		"avril", "mai", "juin", "juillet",
		"août", "septembre", "octobre",
		"novembre", "décembre"
	];
	var day = date.getDate();
	if(day == 1) day += "er";
	var monthIndex = date.getMonth();
	var year = date.getFullYear();
	return day + ' ' + monthNames[monthIndex] + ' ' + year;
}
// --------------------------------------------------------- 

// --------------------------------------------------------- 
function afficherInformations(don) {
	var donnees = JSON.parse(don);
	//console.log(donnees);
	//console.log(donnees['fichier']);
	//console.log(donnees['Testing services']['Bandwidthd']['since']);

	/* -------------------------------------------------- Informations */

//	$("#inf_date") .html( formatDate(new Date()) );
	$("#inf_date")	.html( donnees['hardware']['date'] );
	$("#inf_heure")	.html( donnees['hardware']['hour'] );
	$("#inf_up")		.html( donnees['hardware']['up']['up'] );
	$("#inf_user")	.html( donnees['hardware']['up']['user'] );

	/* -------------------------------------------------- CPU */
	 
	cpu_t = donnees['hardware']['CPU temperature'];
		$("#cpu_t").html( cpu_t.toFixed(1) + " °C");
		//$("#cpu_t").html(donnees['hardware']['CPU temperature'] + " °C");
		//$("#cpu_1").html(donnees['hardware']['CPU load']['1mn']);
		//$("#cpu_5").html(donnees['hardware']['CPU load']['5mn']);
		//$("#cpu_15").html(donnees['hardware']['CPU load']['15mn']);

		z = donnees['hardware']['CPU load']['1mn'];
		$("#cpu_1").html( z.toFixed(2) );
		z = donnees['hardware']['CPU load']['5mn'];
		$("#cpu_5").html( z.toFixed(2) );
		z = donnees['hardware']['CPU load']['15mn'];
		$("#cpu_15").html( z.toFixed(2) );

	/* -------------------------------------------------- Mémoire */

	mem_totale = donnees['hardware']['MemTotal']['size'];
	mem_dispo  = donnees['hardware']['MemAvailable']['size'];
	mem_utilisee = (mem_totale-mem_dispo)*100/mem_totale;
	mem_utilisee = mem_utilisee.toFixed(1);

		$("#mem_totale").html(mem_totale/1000 + " " + donnees['hardware']['MemTotal']['unit']);
		$("#mem_dispo") .html(mem_dispo/1000 + " " + donnees['hardware']['MemAvailable']['unit']);
		$("#mem_utilisee") .html(mem_utilisee + " %");
	
	/* -------------------------------------------------- Disque dur */

		$("#hd_totale").html(donnees['hardware']['disk size']['size'] + " " + donnees['hardware']['disk size']['unit']);
		$("#hd_occup") .html(donnees['hardware']['disk occup']['size'] + " " + donnees['hardware']['disk occup']['unit']);
		$("#hd_libre") .html(donnees['hardware']['disk libre']['size'] + " " + donnees['hardware']['disk libre']['unit']);
		
		hd_pourcent_utilise = donnees['hardware']['disk used']['size'];
		$("#hd_pourcent_utilise") .html(hd_pourcent_utilise + " " + donnees['hardware']['disk used']['unit']);

	/* -------------------------------------------------- Testing config */

/*		if(donnees['Testing services']['Nftables config'] == "OK")
			$("#cfg_nftables").removeAttr("class").addClass( "green" );
		else
			$("#cfg_nftables").removeAttr("class").addClass( "red" );
		*/
		/*
		if(donnees['Testing config']['Nftables config'] == "OK")
			$("#cfg_nftables").html("<img src='$/icones/green.png' />");
		else
			$("#cfg_nftables").html("<img src='$/icones/red.png' />");
		*/
		//alert(donnees['Testing config']['Nftables']);
		$("#cfg_nftables").html("<img src='$/icones/"+ donnees['Testing config']['Nftables'] +".png' />");
	/*
		if(donnees['Testing config']['Proxy config'] == "OK")
			$("#cfg_squid").html("<img src='$/icones/green.png' />");
		else
			$("#cfg_squid").html("<img src='$/icones/red.png' />");
*/
		$("#cfg_squid").html("<img src='$/icones/"+ donnees['Testing config']['Proxy'] +".png' />");

	/* -------------------------------------------------- Testing services */


		$("#srv_squid").html("<img src='$/icones/"+ donnees['Testing services']['Squid']['Status'] +".png' />");
		$("#srv_dhcp_server").html("<img src='$/icones/"+ donnees['Testing services']['DHCP server']['Status'] +".png' />");
//		$("#srv_dhcp_client").html("<img src='$/icones/"+ donnees['Testing services']['DHCP client']['Status'] +".png' />");
		$("#srv_ddclient").html("<img src='$/icones/"+ donnees['Testing services']['ddclient']['Status'] +".png' />");
		$("#srv_bandwidthd").html("<img src='$/icones/"+ donnees['Testing services']['Bandwidthd']['Status'] +".png' />");
		$("#srv_ftp_server").html("<img src='$/icones/"+ donnees['Testing services']['FTP server']['Status'] +".png' />");

	/* -------------------------------------------------- FTP connexion */

		// Testing FTP connexion
		/*
		if(donnees['Testing FTP connexion']['Status'] == "green")
			$("#ftp_connexion").removeAttr("class").addClass( "green" );
		else
			$("#ftp_connexion").removeAttr("class").addClass( "red" );
		*/
		//$("#ftp_connexion").html("<img src='$/icones/"+ donnees['Testing FTP connexion']['FTP connexion'] +".png' />");
		
		$("#ftp_connexion").html("<img src='$/icones/"+ donnees['Testing FTP connexion']['Status'] +".png' />");

	/* -------------------------------------------------- DNS Filtering */
	
		// Testing DNS filtering
		//console.log(Object.keys(donnees['Testing DNS filtering']['Web site']));
		
		// Recensement des websites :
		if( donnees['Testing DNS filtering']['Web site'] != undefined ) {
			var items = Object.keys(donnees['Testing DNS filtering']['Web site']);
		
			// Note : il faudra ajouter des éléments <td> si plus d'un website
			for (var i = 0; i < items.length; i++) {
				/*
				if(donnees['Testing DNS filtering']['Web site'][ items[i] ]['Status'] == "green") {
					$("#ftp_website").html( items[i] + " is blocked" );
					$("#ftp_filtering").removeAttr("class").addClass( "green" );
				}	else {
					$("#ftp_website").html( items[i] + " is open" );
					$("#ftp_filtering").removeAttr("class").addClass( "red" );
				}*/
				if(donnees['Testing DNS filtering']['Web site'][ items[i] ]['locked'] == "green") {
					$("#ftp_website").html( items[i] + " ( bloqué )" );
					$("#ftp_filtering").html("<img src='$/icones/locked.png' title='bloqué' />");
				}	else {
					$("#ftp_website").html( items[i] + " ( accessible )" );
					$("#ftp_filtering").html("<img src='$/icones/danger.png' title='accessible' />");
				}
			}
			
		} else {
			$("#ftp_website").html( "Erreur d'accès DNS" );
		}

	updateGauges();
	}

// --------------------------------------------------------- 
function getInformations() {
	var demande = {
			url			: "ajax_visu_donnees_systeme.php",
			type		: "POST",
			dataType: 'text',
			success	: function(retour){
									afficherInformations(retour);
								},
			error		: function(retour){
									/* Je supprime cette alerte qui peut s'afficher fugitivement
										quand on quitte cette page pour une autre.
										Cela est certainement dû au rafraîchissement continu
										
										Il faudrait utiliser :

										var refreshIntervalId = setInterval(getInformations, raff_time*1000);
										puis quand on quitte la page :
										clearInterval(refreshIntervalId);
									*/
									//alert( alerte["erreur_SP"]+' SP_21.' );
								}
	};
	$.ajax(demande);		
}
// --------------------------------------------------------- 
createGauges();
getInformations();

if(rafraichir == 'oui') {
	setInterval(getInformations, raff_time*1000);
}
// --------------------------------------------------------- 

	$("#update_config, #update_system, #reboot_idefix, #halt_idefix, #infos_system").on("click", function() {
		
		var bouton = $(this).attr("id");
		var cmd = $(this).attr("cmd");
		cmd = cmd.replace(/_/g, " ");

		//alert(bouton);
		//alert(cmd);
		// cmd contient des espaces

		//console.log( JSON.stringify(cmd) );

		var demande = {
				url			: "ajax_commande_py.php",
				type		: "POST",
				dataType: 'html',
				data		: {	cmd			: cmd,
										bouton	:	bouton
									},
				success	: function(retour){
										$("#result").html(retour);
									},
				error		: function(retour){
										if( bouton == 'reboot_idefix') {
											$("#result").html("<p class='vert'>"+alerte["ready_nav8_1"]+"</p>");	// Redémarrage en cours
											alert( alerte["ready_nav8_1"] );	// Redémarrage en cours
										} else if( bouton == 'halt_idefix') {
											$("#result").html("<p class='vert'>"+alerte["ready_nav8_2"]+"</p>");	// Arrêt en cours
											alert( alerte["ready_nav8_2"] );	// Arrêt en cours
										} else {
											alert( alerte["erreur_SP"]+' SP_16.' );
										}
									}
		};
		$.ajax(demande);
	});

});
