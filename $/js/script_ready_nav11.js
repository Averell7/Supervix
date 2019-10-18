$(document).ready(function(){
	//alert("b = " + b);
	//var b = 2;
	for(var t=1; t <= b; t++)
    $( "#tabs_" + t ).tabs();

	
	var cssurvol;
//	var url;
	
	$(".colonne_1 p.btn_date").on("click", function() {
		var dir_backup = $(this).attr('id');
	
		$(".colonne_1 p.btn_date").css({"background-color":"#ffffff"});
		$(this).css({"background-color":"#FFF0C0"});
		cssurvol = "#FFF0C0";
		
		$(".cacher").css({'display':'none'});
		$("#aff_"+dir_backup).css({'display':'block'});
	});
	
	$(".colonne_1 p.btn_date").mouseover(function() {
		cssurvol = $(this).css("background-color");
		$(this).css("background-color","#E0F0FF");
	}).mouseout(function() {
		$(this).css("background-color",cssurvol);
	});

	
	$(".colonne_1 p.btn_date:nth-child(2)").trigger('click');
	
	$(".restore").on("click", function() {
		var dir_backup = $(this).attr('id');
		url = $(this).attr('url');

		//var str = "123456-backup-2019-01-18";
		var str = dir_backup.substring(dir_backup.length - 10, dir_backup.length);
		//  var str = "2019-02-32";
				str = str.split("-");
		var date = str[2]+"-"+str[1]+"-"+str[0];

		//		if(confirm("Vous demandez la restauration depuis\n\nla sauvegarde du " + date + "\n\nVous confirmer ?")) {
		if(confirm(alerte["ready_nav11_1"]+"\n\n"+alerte["ready_nav11_2"]+" " + date + "\n\n"+alerte["ready_nav11_3"])) {
		
				var demande = {
						url			: "ajax_config_restauration.php",
						type		: "POST",
						data		: {	dir_backup					: dir_backup,
												dossiers_a_comparer	: dossiers_a_comparer,
												fichiers_exclus			: fichiers_exclus,
												extensions_exclues	: extensions_exclues,
												url									: url
											},
						success	: function(retour){
												$(".colonne_1").html("");
												$(".colonne_2").html("<div id='result' class='message'>"+retour+"</div>");
											},
						error		: function(retour){ alert( alerte["erreur_SP"]+' SP_18.' ); }
						};
				$.ajax(demande);
		}
	});
	
function lance_ajax_commande_py(bouton, cmd) {
		var demande = {
				url			: "ajax_commande_py.php",
				type		: "POST",
				dataType: 'html',
				data		: {	bouton	:	bouton,
										cmd			: cmd
									},
				success	: function(retour){
										$("#result").html(retour);
									},
				error		: function(retour){
										if( bouton == 'reboot_idefix') {	// Redémarrage en cours
											$("#result").html("<p class='vert'>"+alerte["ready_nav8_1"]+"</p>");
											alert( alerte["ready_nav8_1"] );
										} else {
											alert( alerte["erreur_SP"]+' SP_160.' );
										}
									}
		};
		$.ajax(demande);
}	
	
	
	$(".colonne_2").on("click", "#update_config", function(e) {
    e.preventDefault();
		var bouton = $(this).attr('id');
		var cmd = 'sudo /usr/lib/idefix/idefix.py -f 2>&1';
		lance_ajax_commande_py(bouton, cmd);
  });	
	
	 $(".colonne_2").on("click", "#reboot_idefix", function(e) {
    e.preventDefault();
		var bouton = $(this).attr('id');
		var cmd = 'sudo /sbin/shutdown -r now';
		lance_ajax_commande_py(bouton, cmd);
/*		
		switch(bouton) {
			case 'config-restauration-etc-usr.php'	:	//var bouton = 'reboot_idefix';
																								var cmd = 'sudo /sbin/shutdown -r now';
																								break;
			case 'config-restauration-home.php'			: 
			default																	:	//var bouton = 'update_config';
																								var cmd = 'sudo /usr/lib/idefix/idefix.py -f 2>&1';
																								break;
		}
*/		
/*
		var demande = {
				url			: "ajax_commande_py.php",
				type		: "POST",
				dataType: 'html',
				data		: {	bouton	:	bouton,
										cmd			: cmd
									},
				success	: function(retour){alert(retour);
										$("#result").html(retour);
									},
				error		: function(retour){
										if( bouton == 'reboot_idefix') {
											$("#result").html("<p class='vert'>"+alerte["ready_nav8_1"]+"</p>");	// Redémarrage en cours
											alert( alerte["ready_nav8_1"] );	// Redémarrage en cours
										} else {
											alert( alerte["erreur_SP"]+' SP_160.' );
										}
									}
		};
		$.ajax(demande);
		*/
  });	
	
// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
// --------------------------------------------------------- 

});

