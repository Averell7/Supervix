$(document).ready(function(){
	
// --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
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
