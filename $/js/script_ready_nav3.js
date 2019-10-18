$(document).ready(function(){
	
// --------------------------------------------------------- Initialisation interface

	initialise_interface();

// --------------------------------------------------------- Sélection d'un fichier

  $("#fichiersINI").on('click', "li", function(e) {
    e.preventDefault();
    selectionner_fichier( $(this) );
  });

// --------------------------------------------------------- Sélection d'une section

  $("#sections").on('click', 'li', function() {
    selectionner_section( $(this) );
  });

// -------------------- Boutons

	$("#section_nouvelle")
		.on('click', nouvelle_section )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });
	
	$("#section_valider")
		.on('click', valider_section )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });
		
	$("#section_supprimer")
		.on('click', supprimer_section )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#section_deplacer_haut")
		.on('click', deplacer_section_vers_haut )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#section_deplacer_bas")
		.on('click', deplacer_section_vers_bas )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#section_doublons")
		.on('click', recherche_doublons_sections )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });
		
  // -------------------------
  $("#section")
    .on('keyup', saisie_section );
    
// --------------------------------------------------------- Sélection d'une ligne

	$("#vue").on('click', 'li', function() {
		selectionner_ligne( $(this) );
	});
	
	// ---------------------------- flag_comment changé

	$("#flag_comment")
		.on('change', function() {	change_flag_ligne( $(this) ); })
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	// ------------------------- keyup sur #cle ou #valeur
	$("#cle, #valeur, #commentaire")
		.on('keypress', function(e) {
			/* On interdit les caractères '#' et ';'*/
			if(e.which == 35 || e.which == 59) {
				//alert("Caractère interdit.");
				alert( alerte["ready_nav3_1"] );
				return false;
			}
		});
		
	$("#cle, #valeur, #commentaire")
		.on('keyup', saisie_ligne )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });
		
	// ------------------------- keyup sur #ligne_comment
	$("#ligne_comment")
		.on('keyup', saisie_ligne_commentee );
		
  // --------------------------------------------------------- Aide

	$("#afficher_aide")
		.on('change', function() {	afficher_aide( this ); });

	// -------------------- Boutons

	$("#entree_nouvelle")
		.on('click', nouvelle_ligne )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#entree_valider")
		.on('click', valider_ligne )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#entree_supprimer")
		.on('click', supprimer_ligne )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#entree_deplacer_haut")
		.on('click', deplacer_ligne_vers_haut )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#entree_deplacer_bas")
		.on('click', deplacer_ligne_vers_bas )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });

	$("#entree_doublons")
		.on('click', recherche_doublons_lignes )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });
		
  // --------------------------------------------------------- recherche_globale_doublons

	$("#recherche_globale_doublons")
		.on('click', recherche_globale_doublons )
		.on('mouseenter mouseleave', function(e) {	contenu_aide( e, $(this) ); });
					
	$("#fermer_doublons_globaux")
		.on('click', fermer_doublons_globaux );	

  // --------------------------------------------------------- 

  // --------------------------------------------------------- 
  $("#deconnexion").on("click", function(e) {
    e.preventDefault();
    window.location="logout.php";
  });
  // --------------------------------------------------------- 

});
