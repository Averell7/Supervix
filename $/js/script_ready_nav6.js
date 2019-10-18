$(document).ready(function(){
	
  // --------------------------------
		var lg1_mdp = 5;		// Nb minimal
		var lg2_mdp = 15;		// Nb maximal
		var val_1, val_2, val_3;
  // --------------------------------
 
	$("#motdepasse1, #motdepasse2, #motdepasse3").on('keyup', modif_mdp);

	$("#motdepasse1, #motdepasse2")
		.attr("placeholder","entre "+lg1_mdp+" et "+lg2_mdp+" caractères")
		.attr("pattern",".{"+lg1_mdp+","+lg2_mdp+"}");
  $("#motdepasse1, #motdepasse2").val('').trigger('keyup');
	$("#motdepasse1").focus();
	
	
	/* ------------------------------------------------ *\
			Contrôles pour la modification du mot de passe
	\* ------------------------------------------------ */
	
	function modif_mdp() {
		val_1 = $("#motdepasse1").val();
		val_2 = $("#motdepasse2").val();
		val_3 = $("#motdepasse3").val();
		
		if( (val_1 == val_2) && val_1 != ''
												 && val_2 != ''
												 && val_1.length >= lg1_mdp
												 && val_1.length <= lg2_mdp
												 && trim(val_3) != ''
			) {

			// on active le bouton Valider
			$("#validation").removeAttr("disabled");
			$("#validation>img").css({"opacity":"1"});
		} else {
			// on désactive le bouton Valider
			$("#validation").attr("disabled","disabled");
			$("#validation>img").css({"opacity":"0.5"}); //alert('2');
		}
	}
	
	/* ------------------------------------------------ *\
			Envoi pour traitement
	\* ------------------------------------------------ */
	
	$("#validation").on('click', function() {
		var demande = {
					url			: "ajax_mdp_change.php",
					type		: "POST",
					dataType: 'html',
					data		: {	mdp_new	: val_1,
											mdp_old : val_3
										},
					success	: function(retour){
											$(".fieldset_Password")
												.html( retour);
												/*.css({"color":"blue","text-align":"center","font-size":"0.9rem"});*/
										},
					error		: function(retour){ alert('Erreur SP_04.'); }
					};
		$.ajax(demande);
	});

	/* --------------------------------------------------------- *\
			Fonction équivalente à trim() en PHP
	\* --------------------------------------------------------- */

	function trim (item) {
		return item.replace(/^\s+/g,'').replace(/\s+$/g,'');
	} 
	 
	// --------------------------------------------------------- 
	$("#deconnexion").on("click", function(e) {
		e.preventDefault();
		window.location="logout.php";
	});
	// --------------------------------------------------------- 

});
