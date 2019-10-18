/* --------------------------------------------------------- *\
		script_fonctions3.js
\* --------------------------------------------------------- */

	/* --------------------------------------------------------- *\
			Variables globales
	\* --------------------------------------------------------- */

	var nbFichiers;
  var nomFichier;
	// Exclusion du fichier en modifiaction
	var nomFichierExclu = path_home_rock64_idefix + 'firewall-ports.ini';

  var nomSection;
  var nomSectionSelection;
  var nomSectionCourtOrigine;
  var nbSections;
  var index_section_selectionne;
  var modeSection;

  var nbLignes;
  var flag_commentLigneCourtOrigine, ligne_commenteeLigneCourtOrigine;
  var CleLigneCourtOrigine, ValeurLigneCourtOrigine, CommentaireLigneCourtOrigine;
  var index_ligne_selectionnee;
  var modeLigne;

  var boutons = new Array();

 	// -------------- Couleurs

	// Fichiers
	var fichier_couleur			      = '#000000';
	var fichier_bg_couleur	      = '#e1e1e1';
	        // Sélection
	var fichier_couleur_select		= 'white';
  var fichier_bg_couleur_select	= '#606060';
  
	// Sections	
	var section_couleur			      = '#0000aa';
	var section_bg_couleur	      = 'white';
	        // Sélection
	var section_couleur_select		= 'white';
  var section_bg_couleur_select	= '#0000aa';
  
	// Lignes	
	var ligne_couleur				      = 'white';
	var ligne_bg_couleur		      = '#414141';
	        // Sélection
  var ligne_couleur_select		  = 'white';
  var ligne_bg_couleur_select		= 'green';

 	// -------------- Palettes de couleurs pour l'affichage des doublons

   var couleurs = { 
    0: { texte: 'bisque', backg: 'blue'},
    1: { texte: 'white', backg: '#AB2567'},
    2: { texte: 'white', backg: '#0C9106'},
    3: { texte: 'white', backg: '#915D12'},
    4: { texte: 'black', backg: '#E3A752'},
    5: { texte: 'white', backg: '#913750'},
    6: { texte: 'white', backg: '#C60000'},
    7: { texte: '#000000', backg: 'yellow'},
    8: { texte: '#000000', backg: '#F2DB0D'},
    9: { texte: '#000000', backg: '#DFD99F'},
    10: { texte: '#000000', backg: '#80FF00'},
    11: { texte: '#000000', backg: '#FFB800'},
    12: { texte: 'white', backg: '#0066FF'},
    13: { texte: '#000000', backg: '#00F0FF'},
    14: { texte: 'white', backg: '#FF0099'}
  }
  var nbPalette = numProps(couleurs);
        
	
	/* --------------------------------------------------------- *\
			Initialisation de l'interface
	\* --------------------------------------------------------- */
	
  function initialise_interface() {
		//$("#afficher_aide").trigger('change');
		$("#aide")           .css({"visibility":"visible", "display":"block"});
		$("#ligne_normale")  .css({"visibility":"visible", "display":"inline-block"});
		$("#ligne_commentee").css({"visibility":"collapse","display":"none"});
    
    boutons["section_nouvelle"]			= false;
    boutons["section_valider"]			= false;
    boutons["section_supprimer"]		= false;
    boutons["section_deplacer_haut"]= false;
    boutons["section_deplacer_bas"]	= false;
    boutons["section_doublons"]			= false;
    boutons["section_doublons"]			= false;
    $("#section").val('').attr({"disabled":"disabled"});
    nomSectionCourtOrigine = NaN;
    //Active_Boutons();

    boutons["entree_nouvelle"]			= false;
    boutons["entree_valider"]			  = false;
    boutons["entree_supprimer"]     = false;
    boutons["entree_deplacer_haut"] = false;
    boutons["entree_deplacer_bas"]	= false;
    boutons["entree_doublons"]      = false;
    $("#flag_comment").attr({"selected":"selected","disabled":"disabled"});                  
    $("#cle")         .val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
    Active_Boutons();

		nbFichiers = $("#fichiersINI li").length;
  }

	/* ======================================================================================================= *\
			Fichiers
	\* ======================================================================================================= */
	
	/* --------------------------------------------------------- *\
			Sélection d'un fichier
	\* --------------------------------------------------------- */

  function selectionner_fichier( fichier_clique ) {
    nomFichier = fichier_clique.html();
    nomFichier = path_home_rock64_idefix + nomFichier;
		
    //initialise_bloc_sections ();
    nbSections	= INI[ nomFichier ]['-'].length;
//    alert(nbSections);
    
    // Actualisation de l'affichage des Fichiers
    $("#fichiersINI li").css({"color":fichier_couleur, "background-color":fichier_bg_couleur});
    fichier_clique			.css({"color":fichier_couleur_select, "background-color":fichier_bg_couleur_select});
		
    // Actualisation de l'affichage des Sections
    var codeHTML_Sections = "";
    for (var k = 0; k < nbSections; k++) {
      codeHTML_Sections += "<li>" + INI[ nomFichier ]['-'][k] + "</li>";
    }
    $("#sections").html( codeHTML_Sections );
		$("#sections li").css({"color":section_couleur, "background-color":section_bg_couleur});

    // initialisation du bloc sections		
		$("#section").val('').attr({"disabled":"disabled"});
		
		//alert(nomFichier + " * ||| * "+ 'firewall-ports.ini');
    // Actualisation de l'affichage des boutons
		if(nomFichier == nomFichierExclu) {			// Fichier en lecture seule
//	if(nomFichier == 'firewall-ports.ini') {			// Fichier en lecture seule
			boutons["section_nouvelle"]			= false;
			boutons["section_doublons"]			= false;
			
			boutons["entree_nouvelle"]			= false;
			boutons["entree_valider"]			  = false;
			boutons["entree_supprimer"]     = false;
			boutons["entree_deplacer_haut"] = false;
			boutons["entree_deplacer_bas"]	= false;
			boutons["entree_doublons"]      = false;

		} else {
			boutons["section_nouvelle"]			= true;
			if(nbSections >= 2) {
				boutons["section_doublons"]			= true;
			} else {
				boutons["section_doublons"]			= false;
			}
		}
    boutons["section_valider"]			= false;
    boutons["section_supprimer"]		= false;
    boutons["section_deplacer_haut"]= false;
    boutons["section_deplacer_bas"]	= false;
    Active_Boutons( boutons );
    
		
    // initialisation du bloc lignes
    $("#ligne_normale")  .css({"visibility":"visible", "display":"inline-block"});
    $("#ligne_commentee").css({"visibility":"collapse","display":"none"});
    $("#vue")		      .html('');
    $("#index")				.html('');
		$("#flag_comment").attr({"disabled":"disabled"});
    $("#flag_comment option")
											.removeAttr("selected")
											.filter("[ value='' ]")
                      .attr({"selected":"selected","disabled":"disabled"});
    $("#cle")				 	.val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
		$("#ligne_commentee").attr({"disabled":"disabled"});
		nbLignes = 0;
    index_ligne_selectionnee = NaN;
    // Modification de l'affichage des inputs de saisie selon le type de fichier 
    modification_inputs_saisie_ligne ();
  }


	/* ======================================================================================================= *\
			Sections
	\* ======================================================================================================= */

	/* --------------------------------------------------------- *\
			Bouton : section_nouvelle
			Pour créer une nouvelle section du fichier sélectionné
	\* --------------------------------------------------------- */

  function nouvelle_section() {
    modeSection = "nouvelle";

		$("#sections li") .css({"color":section_couleur, "background-color":section_bg_couleur});
    $("#section")     .val('')
                      .removeAttr("disabled")
                      .focus();

    // Actualisation de l'affichage des boutons
    boutons["section_nouvelle"]			= false;
    boutons["section_valider"]			= false;
    boutons["section_supprimer"]		= false;
    boutons["section_deplacer_haut"]= false;
    boutons["section_deplacer_bas"]	= false;
    boutons["section_doublons"]     = false;
    $("#section").removeAttr("disabled");
    Active_Boutons( boutons );

    // initialisation du bloc lignes
    $("#vue")		      .html('');
    $("#index")				.html('');
    $("#flag_comment option")
											.removeAttr("selected")
											.filter("[ value='' ]")
                      .attr({"selected":"selected","disabled":"disabled"});
    $("#cle")				 	.val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
		nbLignes = 0;
    index_ligne_selectionnee = NaN;

    // Actualisation de l'affichage des boutons
    boutons["entree_nouvelle"]			= false;
    boutons["entree_valider"]			  = false;
    boutons["entree_supprimer"]     = false;
    boutons["entree_deplacer_haut"] = false;
    boutons["entree_deplacer_bas"]	= false;
    boutons["entree_doublons"]      = false;
    $("#flag_comment").attr({"selected":"selected","disabled":"disabled"});                  
    $("#cle").val('') .attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
    Active_Boutons( boutons );

	  index_section_selectionne = nbSections;
	  index_ligne_selectionnee = NaN;
	}
	
	/* --------------------------------------------------------- *\
			Bouton : saisie_section
			Saisie dans "#section"
	\* --------------------------------------------------------- */

  function saisie_section() {
    switch(modeSection) {
      case "nouvelle":
          if( $("#section").val() != '') {
            boutons["section_valider"]			= true;
          } else {
            boutons["section_valider"]			= false;
          }
          break;
      case "selection":
          if( $("#section").val() != nomSectionCourtOrigine) {
            boutons["section_nouvelle"]			= false;
            boutons["section_valider"]			= true;
          } else {
            boutons["section_nouvelle"]			= true;
            boutons["section_valider"]			= false;
          }
          break;
    }
    Active_Boutons( boutons );
  }
	
	/* --------------------------------------------------------- *\
			Bouton : section_valider
			Valide une nouvelle section
      ou
			Valide la modification d'une section sélectionnée
	\* --------------------------------------------------------- */

  function valider_section() {
    nomSection = "[" + $('#section').val() + "]";
		switch(modeSection) {
      case 'nouvelle' :
						$("#sections li").css({"color":section_couleur, "background-color":section_bg_couleur});
            $("#sections").append($("<li>").text( nomSection ));
            $("#sections li:eq(" + nbSections +")")
							.css({"color":section_couleur_select, "background-color":section_bg_couleur_select});
            index_section_selectionne = nbSections;
            nbSections++;
						INI[nomFichier]['-'].push(nomSection);
						INI[nomFichier][nomSection] = '';
            // Pour faire apparaître la nouvelle section qui est ajoutée à la fin
            // On scroll jusqu'en bas de la fenêtre : 4000(px) pour forcer, 300 ms d'animation
            $("#sections").animate({ scrollTop: 4000}, 300);
            $("#vue").html('');
            index_ligne_selectionnee = NaN;
            boutons["section_nouvelle"]			= true;
            boutons["section_valider"]			= false;
            boutons["section_supprimer"]		= true;
            boutons["section_deplacer_bas"]	= false;
            if(nbSections >= 2) {
              boutons["section_deplacer_haut"]= true;
              boutons["section_doublons"]			= true;
            } else {
              boutons["section_deplacer_haut"]= false;
              boutons["section_doublons"]			= false;
            }
            Active_Boutons( boutons );
						
            boutons["entree_nouvelle"]			= true;
            Active_Boutons( boutons );
						
            Appliquer("Sections");
            // On vérifie si on n'a pas un doublon, sinon Message
            break;
      case 'selection' :
            $("#sections li:eq(" + index_section_selectionne +")")
							.html(nomSection)
							.css({"color":section_couleur_select, "background-color":section_bg_couleur_select});
						// Modification du libellé dans le tableau INI	
						var str = JSON.stringify(INI);
						str = str.replace(new RegExp('\\'+ nomSectionSelection, 'g'), nomSection);
						INI = JSON.parse(str);
						
						nomSectionSelection = nomSection;

						boutons["section_nouvelle"]			= true;
						boutons["section_valider"]			= false;
						Active_Boutons();
						Appliquer("Sections");
            break;
    }

  }
	
	/* --------------------------------------------------------- *\
			Sélection d'une section
	\* --------------------------------------------------------- */

  function selectionner_section( section_cliquee ) {
    modeSection               = "selection";
    nomSection                = section_cliquee.html();
    index_section_selectionne = section_cliquee.index();
    // Conserver pour un éventuel changement de libellé
    nomSectionSelection       = nomSection;
    nbSections	= INI[ nomFichier ]['-'].length;
		
    $("#sections li").css({"color":section_couleur, "background-color":section_bg_couleur});
    section_cliquee	 .css({"color":section_couleur_select, "background-color":section_bg_couleur_select});
    
    nomSectionCourtOrigine = nomSection.substring(1, nomSection.length-1);
    $("#section") .val( nomSectionCourtOrigine )
                  .removeAttr("disabled");

		if(nomFichier == nomFichierExclu) {			// Fichier en lecture seule
//	if(nomFichier == 'firewall-ports.ini') {			// Fichier en lecture seule
			boutons["section_nouvelle"]			= false;
			boutons["section_valider"]			= false;
			boutons["section_supprimer"]		= false;
			boutons["section_deplacer_haut"]= false;
			boutons["section_deplacer_bas"]	= false;
			boutons["section_doublons"]			= false;
			boutons["section_doublons"]			= false;
		} else {
			boutons["section_nouvelle"]			= true;
			boutons["section_valider"]			= false;
			boutons["section_supprimer"]		= true;

			if(index_section_selectionne > 0 )
				boutons["section_deplacer_haut"] = true;
			else
				boutons["section_deplacer_haut"] = false;
			
			if(index_section_selectionne < nbSections - 1 )
				boutons["section_deplacer_bas"]	= true;
			else
				boutons["section_deplacer_bas"]	= false;
			
			if(nbSections >= 2) {
				boutons["section_doublons"]			= true;
			} else {
				boutons["section_doublons"]			= false;
			}
		}									
    Active_Boutons();

    // Remplissage de "#vue"
    nbLignes = INI[ nomFichier ][nomSection].length;
    var codeHTML_Lignes = "";
    for (var k = 0; k <nbLignes; k++) {
      codeHTML_Lignes += "<li>" + INI[ nomFichier ][nomSection][k] + "</li>";
    }

    // initialisation du bloc lignes
    $("#ligne_normale")  .css({"visibility":"visible", "display":"inline-block"});
    $("#ligne_commentee").css({"visibility":"collapse","display":"none"});
    $("#vue")					.html( codeHTML_Lignes );
    $("#index")				.html('');
		$("#flag_comment").attr({"disabled":"disabled"});
    $("#flag_comment option")
											.removeAttr("selected")
											.filter("[ value='' ]")
                      .attr({"selected":"selected","disabled":"disabled"});
    $("#cle")				 	.val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
		$("#ligne_commentee").attr({"disabled":"disabled"});
    index_ligne_selectionnee = NaN;
  
    // Actualisation de l'affichage des boutons
		if(nomFichier == nomFichierExclu) {			// Fichier en lecture seule
//	if(nomFichier == 'firewall-ports.ini') {			// Fichier en lecture seule
			boutons["entree_nouvelle"]			= false;
			boutons["entree_doublons"]      = false;
		} else {
			boutons["entree_nouvelle"]			= true;
			boutons["entree_doublons"]      = true;
		}									
    boutons["entree_valider"]			  = false;
    boutons["entree_supprimer"]     = false;
    boutons["entree_deplacer_haut"] = false;
    boutons["entree_deplacer_bas"]	= false;
    Active_Boutons();
  }

	/* --------------------------------------------------------- *\
			Bouton : section_supprimer
			Supprime une section ET toutes les lignes qu'elle contient
	\* --------------------------------------------------------- */

  function supprimer_section() {
    $("#sections li:eq("+ index_section_selectionne +")").remove();
		// Détruit toutes les entrées de cette section
		//delete INI[nomFichier][nomSection];	// Utile avec ce qu'il y a juste ci-dessous ?
    
		// Détruit maintenant la section
		for (var key in INI[nomFichier]['-']) {
			if(INI[nomFichier]['-'][key] == nomSection) {
				INI[nomFichier]['-'].splice( key, 1 );	// --> Supprime complètement
				break;
			}
    }
		nbSections--;
    $("#section").val('').attr({"disabled":"disabled"});
    index_section_selectionne = NaN;

    boutons["section_nouvelle"]			= true;
    boutons["section_valider"]			= false;
    boutons["section_supprimer"]		= false;
    boutons["section_deplacer_haut"]= false;
    boutons["section_deplacer_bas"]	= false;
    if(nbSections >= 2) {
      boutons["section_doublons"]			= true;
    } else {
      boutons["section_doublons"]			= false;
    }
    Active_Boutons( boutons );
    // initialisation du bloc lignes
    $("#ligne_normale")  .css({"visibility":"visible", "display":"inline-block"});
    $("#ligne_commentee").css({"visibility":"collapse","display":"none"});
    $("#vue")		      .html('');
    $("#index")				.html('');
		$("#flag_comment").attr({"disabled":"disabled"});
    $("#flag_comment option")
											.removeAttr("selected")
											.filter("[ value='' ]")
                      .attr({"selected":"selected","disabled":"disabled"});
    $("#cle")				 	.val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
		$("#ligne_commentee").attr({"disabled":"disabled"});
		nbLignes = 0;
    index_ligne_selectionnee = NaN;
    Appliquer("Sections");
  }

	/* --------------------------------------------------------- *\
			Bouton : deplacer_section_vers_haut
	\* --------------------------------------------------------- */

  function deplacer_section_vers_haut() {
		//alert('index_section_selectionne' + index_section_selectionne);
    if(index_section_selectionne > 0) {
      modeSection = "deplacement";
      var index_section_precedent = index_section_selectionne - 1;
      var contenuLI_select= $("#sections li:eq("+ index_section_selectionne +")").html();
      var contenuLI_prec  = $("#sections li:eq("+ index_section_precedent +")").html();

      $("#sections li")
        .eq( index_section_selectionne )
        .html(contenuLI_prec)
        .css({"color":section_couleur, "background-color":section_bg_couleur});
      $("#sections li")
        .eq( index_section_precedent )
        .html(contenuLI_select)
        .css({"color":section_couleur_select, "background-color":section_bg_couleur_select});
				
      var z1 = INI[nomFichier]['-'][index_section_selectionne];
      var z2 = INI[nomFichier]['-'][index_section_precedent];
      INI[nomFichier]['-'][index_section_precedent] = z1;
      INI[nomFichier]['-'][index_section_selectionne] = z2;

      index_section_selectionne = index_section_precedent;
      if(index_section_selectionne == 0) {
        boutons["section_deplacer_haut"] = false;
      } else {
        boutons["section_deplacer_haut"] = true;
      }
      boutons["section_deplacer_bas"]  = true;
      Active_Boutons( boutons );
      Appliquer("Sections");
    }
	}
	
	/* --------------------------------------------------------- *\
			Bouton : deplacer_section_vers_bas
	\* --------------------------------------------------------- */

  function deplacer_section_vers_bas() {
    if(index_section_selectionne < nbSections - 1) {
      modeSection = "deplacement";
      var index_section_suivant = index_section_selectionne + 1;
      var contenuLI_select  = $("#sections li:eq("+ index_section_selectionne +")").html();
      var contenuLI_suivant = $("#sections li:eq("+ index_section_suivant +")").html();

      $("#sections li")
        .eq( index_section_selectionne )
        .html(contenuLI_suivant)
        .css({"color":section_couleur, "background-color":section_bg_couleur});
      $("#sections li")
        .eq( index_section_suivant )
        .html(contenuLI_select)
        .css({"color":section_couleur_select, "background-color":section_bg_couleur_select});

        var z1 = INI[nomFichier]['-'][index_section_selectionne];
        var z2 = INI[nomFichier]['-'][index_section_suivant];
        INI[nomFichier]['-'][index_section_suivant] = z1;
        INI[nomFichier]['-'][index_section_selectionne] = z2;
  
        index_section_selectionne = index_section_suivant;
        if(index_section_selectionne == nbSections - 1) {
          boutons["section_deplacer_bas"] = false;
        } else {
          boutons["section_deplacer_bas"] = true;
        }
        boutons["section_deplacer_haut"] = true;
        Active_Boutons( boutons );
        Appliquer("Sections");
      }
    }
	
	/* --------------------------------------------------------- *\
			Bouton : section_doublons
      Recherche d'éventuels doublons sur les sections
      d'un fichier sélectionné
	\* --------------------------------------------------------- */

  function recherche_doublons_sections() {
	  var nomSection_k, nomSection_j;
		var nbDoublonsSections = 0;
		var p = 0;

    $("#sections li").css({"color":section_couleur, "background-color":section_bg_couleur});

    // initialisation du bloc sections		
		$("#section").val('').attr({"disabled":"disabled"});
    // Actualisation de l'affichage des boutons
    boutons["section_nouvelle"]			= true;
    boutons["section_valider"]			= false;
    boutons["section_supprimer"]		= false;
    boutons["section_deplacer_haut"]= false;
    boutons["section_deplacer_bas"]	= false;
    if(nbSections >= 2) {
      boutons["section_doublons"]			= true;
    } else {
      boutons["section_doublons"]			= false;
    }
    Active_Boutons( boutons );
    
    // initialisation du bloc lignes
    $("#ligne_normale")  .css({"visibility":"visible", "display":"inline-block"});
    $("#ligne_commentee").css({"visibility":"collapse","display":"none"});
    $("#vue")		      .html('');
    $("#index")				.html('');
		$("#flag_comment").attr({"disabled":"disabled"});
    $("#flag_comment option")
											.removeAttr("selected")
											.filter("[ value='' ]")
                      .attr({"selected":"selected","disabled":"disabled"});
    $("#cle")				 	.val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
		$("#ligne_commentee").attr({"disabled":"disabled"});
		nbLignes = 0;
    index_ligne_selectionnee = NaN;
    // Modification de l'affichage des inputs de saisie selon le type de fichier 
    modification_inputs_saisie_ligne ();
		
    for (var k = 0; k < nbSections; k++) {
      nomSection_k = $("#sections li:eq("+ k +")").html();
      for (var j = k+1; j < nbSections; j++) {
        nomSection_j = $("#sections li:eq("+ j +")").html();
        if(nomSection_j == nomSection_k) {
          nbDoublonsSections++;
					$("#sections li:eq("+ k +"), #sections li:eq("+ j +")")
						.css({"color": couleurs[p]['texte'],"background-color":couleurs[p]['backg']});
					p++;
        }
      }
    }
		
		if (nbDoublonsSections == 0)
			alert( alerte["fonctions3_1"] );	// Aucun doublon trouvé\nsur les sections.
		else
			alert( alerte["fonctions3_2"] );	// Doublons trouvés\nsur les sections.
	}   
  


	
	/* ======================================================================================================= *\
			Lignes
	\* ======================================================================================================= */
	
	/* --------------------------------------------------------- *\
			Sélection d'une ligne
	\* --------------------------------------------------------- */

  function selectionner_ligne( ligne_cliquee ) {
    modeLigne = "selection";

    $("#vue li").css({"color":ligne_couleur, "background-color":ligne_bg_couleur});
		ligne_cliquee.css({"color":ligne_couleur_select, "background-color":ligne_bg_couleur_select});
		var ligne = ligne_cliquee.html();
		index_ligne_selectionnee = ligne_cliquee.index();

		if(nomFichier == nomFichierExclu) {			// Fichier en lecture seule
//	if(nomFichier == 'firewall-ports.ini') {			// Fichier en lecture seule
			boutons["section_nouvelle"]			= false;
			boutons["section_valider"]			= false;
			boutons["section_supprimer"]		= false;
			boutons["section_deplacer_haut"]= false;
			boutons["section_deplacer_bas"]	= false;
			boutons["section_doublons"]			= false;
			boutons["section_doublons"]			= false;
		} else {
			boutons["entree_nouvelle"]			= true;
			boutons["entree_valider"]			  = false;
			boutons["entree_supprimer"]     = true;
			if(index_ligne_selectionnee > 0 )
				boutons["entree_deplacer_haut"] = true;
			else
				boutons["entree_deplacer_haut"] = false;
			
			if(index_ligne_selectionnee < nbLignes - 1 )
				boutons["entree_deplacer_bas"]	= true;
			else
				boutons["entree_deplacer_bas"]	= false;
			
			if(nbLignes >= 2) {
				boutons["entree_doublons"]			= true;
			} else {
				boutons["entree_doublons"]			= false;
			}
		}		
    Active_Boutons( boutons );

    $("#index").html( index_ligne_selectionnee );

    // Découpage de la ligne
		var decoup = exploseLigne (ligne);
//	if(nomFichier != 'firewall-ports.ini') {			// Fichier en lecture seule
		if(nomFichier != nomFichierExclu) {			// Fichier en lecture seule
			$("#flag_comment, #flag_comment option")
														.removeAttr("disabled");
			$("#flag_comment option[value='"+ decoup['flag_comment'] +"']")
														.prop("selected", true);
														/*.css({"selected":"selected"});*/
			$("#cle")             .val( decoup['cle'] )
														.removeAttr("disabled");
			$("#valeur")          .val( decoup['valeur'] )
														.removeAttr("disabled");
			$("#commentaire")     .val( decoup['commentaire'] )
														.removeAttr("disabled");
			$("#ligne_commentee") .removeAttr("disabled");
		}
		
		CleLigneCourtOrigine					= decoup['cle'];
		ValeurLigneCourtOrigine				= decoup['valeur'];
		CommentaireLigneCourtOrigine	= decoup['commentaire'];
		
		flag_commentLigneCourtOrigine = decoup['flag_comment'];
					var commentaire = CleLigneCourtOrigine;
					if( $("#valeur").val() != '' ) commentaire += " = " + ValeurLigneCourtOrigine;
					if( $("#commentaire").val() != '' ) commentaire += " # " + CommentaireLigneCourtOrigine;
					$("#ligne_comment").val( commentaire );
		ligne_commenteeLigneCourtOrigine = commentaire;
	
		var flag_1 = decoup['flag_comment'];
		switch(flag_1) {
			case '' : $("#ligne_normale").css({"visibility":"visible","display":"inline-block"});
								$("#ligne_commentee").css({"visibility":"collapse","display":"none"});

								$("#cle").val( decoup['cle'] );
								$("#valeur").val( decoup['valeur'] );
								$("#commentaire").val( decoup['commentaire'] );
								break;
								
			case '#': $("#ligne_commentee").css({"visibility":"visible","display":"inline-block"});
								$("#ligne_normale").css({"visibility":"collapse","display":"none"});

								$("#ligne_comment").val( decoup['commentaire'] );
								break;
			default:
		}
		
	}
		
	/* --------------------------------------------------------- *\
			Fonction qui éclate une ligne sélectionnée en 4 parties
	\* --------------------------------------------------------- */
	
  function exploseLigne (ligne) {
    //var debug = false;
    ligne = trim (ligne);
    var segment = new Array;

    if(ligne == '') {
      segment['flag_comment'] = '';
      segment['cle'] = '';
      segment['valeur'] = '';
      segment['commentaire'] = '';
      //if(debug) alert("A - Ligne vide");
    } else if(ligne.startsWith('#')) {
      segment['flag_comment'] = '#';
      segment['cle'] = '';
      segment['valeur'] = '';
      segment['commentaire'] = ligne.substring(1);
      //if(debug) alert("B - La ligne commence par #");

    } else {
      var zcle    = ligne.indexOf('=');
      var zvaleur = ligne.indexOf('#');

      if(zcle == -1) {           //  '=' non trouvé
        //if(debug) alert("1 * zcle (=) non trouvé : " + zcle);
      
        if(zvaleur == -1) {      //  '#' non trouvé
          //if(debug) alert("2 * zvaleur (#) non trouvé : " + zvaleur);
          segment['flag_comment'] = '';
          segment['cle'] = ligne;
          segment['valeur'] = '';
          segment['commentaire'] = '';
        } else {                //   '#' trouvé
          //if(debug) alert("3 *zvaleur (#) trouvé : " + zvaleur);
          segment['flag_comment'] = '';
          segment['cle'] = trim( ligne.slice(0, zvaleur) );
          segment['valeur'] = '';
          segment['commentaire'] = trim( ligne.slice(zvaleur+1) );
        }
      } else {                  //   '=' trouvé
        //if(debug) alert("4 * zcle (=) trouvé : " + zcle);
        segment['flag_comment'] = '';
        segment['cle'] = trim( ligne.slice(0, zcle) );
        if(zvaleur == -1) {      //  '#' non trouvé
        //if(debug) alert("5 * zvaleur (#) non trouvé : " + zvaleur);
          segment['valeur'] = trim( ligne.slice(zcle+1) );
          segment['commentaire'] = '';
        } else {
          //if(debug) alert("6 * zvaleur (#) trouvé : " + zvaleur);
          segment['valeur'] = trim( ligne.slice(zcle+1, zvaleur) );
          segment['commentaire'] = trim( ligne.slice(zvaleur+1) );
        }
      }

    }
    return segment;
  }	
	
	/* --------------------------------------------------------- *\
			(Dés)active boutons
	\* --------------------------------------------------------- */
		
	function Active_Boutons() {
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
			Fonction pour basculer en ligne normale ou commentée
	\* --------------------------------------------------------- */
	
	function change_flag_ligne( flag_change ) {
		var flag_1 = flag_change.val();
//		alert("Valeur flag : " + flag_1);
		
		// et si modeLigne = "nouvelle" ?
		
		switch(flag_1) {
			case '' : $("#ligne_normale").css({"visibility":"visible","display":"inline-block"});
								$("#ligne_commentee").css({"visibility":"collapse","display":"none"});
								var comment = $("#ligne_comment").val();
								var decoup = exploseLigne (comment);
//								var flag_1 = decoup['flag_comment'];
								$("#cle").val( decoup['cle'] );
								$("#valeur").val( decoup['valeur'] );
								$("#commentaire").val( decoup['commentaire'] );
								break;
								
			case '#': $("#ligne_commentee").css({"visibility":"visible","display":"inline-block"});
								$("#ligne_normale").css({"visibility":"collapse","display":"none"});
								var commentaire = $('#cle').val();
								if( $("#valeur").val() != '' ) commentaire += " = " + $("#valeur").val();
								if( $("#commentaire").val() != '' ) commentaire += " # " + $("#commentaire").val();
								$("#ligne_comment").val( commentaire );
								
// a faire
//								if(modeLigne == "nouvelle") {
//									boutons["entree_valider"]			  = true;
//									Active_Boutons( boutons );
//								}
							
								break;
			default:
		}

		activation_boutons_nouvelle_valider_ligne();
  }

	/* --------------------------------------------------------- *\
      Fonction qui modifie les inputs de saisie ligne selon
      le type de fichier sélectionné et donc le contenu attendu
	\* --------------------------------------------------------- */
	
  function modification_inputs_saisie_ligne () {
    var labelCle, defautCle, labelValeur;
		
		switch (nomFichier) {
			case "macaddress.ini"			: labelCle		= "Utilisateur";
                                  defautCle		= '';
																	labelValeur	= "Adresse Mac";
                                  break;
			case "proxy-groups.ini"		: labelCle		= "dest_domain";
                                  defautCle		= 'dest_domain';
																	labelValeur	= "Domaine";
                                  break;
//			case "firewall-ports.ini"	: labelCle		= "Port";
			case nomFichierExclu			: labelCle		= "Port";
                                  defautCle		= 'port';
																	labelValeur	= "Valeur";
                                  break;
      case "firewall-users.ini"	: 
			case "proxy-users.ini"		: 
			default										: labelCle		= "Clé";
																	defautCle		= '';
                                  labelValeur	= "Valeur";
    }

    $("#labelCle")		.html(labelCle);
		$("#cle")				 	.val(defautCle);
    $("#labelValeur")	.html(labelValeur);
  }


	/* --------------------------------------------------------- *\
			Déplacement d'une ligne sélectionnée vers le haut
	\* --------------------------------------------------------- */

  function deplacer_ligne_vers_haut() {
    modeLigne = "deplacement";
		
    var item_precedent = index_ligne_selectionnee - 1;

		if(index_ligne_selectionnee > 0) {
      var contenuLI = $("#vue li:eq("+ index_ligne_selectionnee +")").html();
      var contenuLI_prec = $("#vue li:eq("+ item_precedent +")").html();

      $("#vue li")
				.eq( index_ligne_selectionnee )
				.html(contenuLI_prec)
				.css({"color":"white", "background-color":"#414141"});
			$("#vue li")
				.eq( item_precedent )
				.html(contenuLI)
				.css({"color":"white", "background-color":"green"});
			
      var z1 = INI[nomFichier][nomSection][index_ligne_selectionnee];
      var z2 = INI[nomFichier][nomSection][item_precedent];
      INI[nomFichier][nomSection][item_precedent] = z1;
      INI[nomFichier][nomSection][index_ligne_selectionnee] = z2;
			
			index_ligne_selectionnee = item_precedent;
			// Rafraichissement de l'index dans les inputs
      $("#index").html( index_ligne_selectionnee );
      if(index_ligne_selectionnee == 0) {
        boutons["entree_deplacer_haut"] = false;
      } else {
        boutons["entree_deplacer_haut"] = true;
      }
      boutons["entree_deplacer_bas"]  = true;
      Active_Boutons( boutons );
      Appliquer("Lignes");
		}
		
  }

	/* --------------------------------------------------------- *\
			Déplacement d'une ligne sélectionnée vers le bas
	\* --------------------------------------------------------- */

  function deplacer_ligne_vers_bas() {
    modeLigne = "deplacement";
		var item_suivant = index_ligne_selectionnee + 1;

		if(item_suivant < nbLignes) {
      var contenuLI = $("#vue li:eq("+ index_ligne_selectionnee +")").html();
      var contenuLI_suivant = $("#vue li:eq("+ item_suivant +")").html();

      $("#vue li")
				.eq( index_ligne_selectionnee )
				.html(contenuLI_suivant)
				.css({"color":"white", "background-color":"#414141"});
			$("#vue li")
				.eq( item_suivant )
				.html(contenuLI)
				.css({"color":"white", "background-color":"green"});
			
			var z1 = INI[nomFichier][nomSection][index_ligne_selectionnee];
			var z2 = INI[nomFichier][nomSection][item_suivant];
			INI[nomFichier][nomSection][item_suivant] = z1;
			INI[nomFichier][nomSection][index_ligne_selectionnee] = z2;
			
			index_ligne_selectionnee = item_suivant;
			// Rafraichissement de l'index dans les inputs
			$("#index").html( index_ligne_selectionnee );
      if(index_ligne_selectionnee == nbLignes - 1) {
        boutons["entree_deplacer_bas"] = false;
      } else {
        boutons["entree_deplacer_bas"] = true;
      }
      boutons["entree_deplacer_haut"] = true;
      Active_Boutons( boutons );
      Appliquer("Lignes");
		}
	
	}
		
	/* --------------------------------------------------------- *\
			Bouton : entree_supprimer
			Supprime une ligne sélectionnée
	\* --------------------------------------------------------- */

  function supprimer_ligne() {
		for(var k = 0; k < nbLignes; k++) {
			if(k >= index_ligne_selectionnee) {
				INI[nomFichier][nomSection][k] = INI[nomFichier][nomSection][k + 1];
			}
		}

    INI[nomFichier][nomSection].pop();    // Supprime le dernier élément du tableau
		$("#vue li:eq("+ index_ligne_selectionnee +")").remove();
    nbLignes--;
		
    boutons["entree_nouvelle"]			= true;
    boutons["entree_valider"]				= false;
    boutons["entree_supprimer"]			= false;
    boutons["entree_deplacer_haut"]	= false;
    boutons["entree_deplacer_bas"]	= false;
    if(nbLignes >= 2) {
      boutons["entree_doublons"]			= true;
    } else {
      boutons["entree_doublons"]			= false;
    }
    Active_Boutons( boutons );
    // initialisation du bloc lignes
    $("#index")				.html('');
		$("#flag_comment").attr({"disabled":"disabled"});
    $("#flag_comment option")
											.removeAttr("selected")
											.filter("[ value='' ]")/*
                      .attr({"selected":"selected","disabled":"disabled"})*/;
    $("#cle")				 	.val('').attr({"disabled":"disabled"});
    $("#valeur")			.val('').attr({"disabled":"disabled"});
    $("#commentaire")	.val('').attr({"disabled":"disabled"});
		$("#ligne_commentee").attr({"disabled":"disabled"});

		index_ligne_selectionnee = NaN;
    Appliquer("Lignes");
	}
		
	/* --------------------------------------------------------- *\
			Bouton : entree_valider
      Valide la saise d'une nouvelle ligne
      ou
			Valide la modification d'une ligne sélectionnée
	\* --------------------------------------------------------- */

  function valider_ligne() {
    //alert('Mode : ' + modeLigne);
    // Mode : modeLigne = "selection"; ou modeLigne = "nouvelle";

    var flag_1 = $('#flag_comment option:selected').val();
    var ligne;
    switch(flag_1) {
      case '' : var b1 = $('#cle').val();
                var b2 = $("#valeur").val();
                var b3 = $("#commentaire").val();
                if(b1 != '' && b2 != '') {
                  ligne = b1 + " = " + b2;
                  if(b3 != '') ligne +=  " # " + b3;
                } else {
									alert( alerte["fonctions3_3"] );	// Compléter votre saisie.
                  return;
                }
                break;
      case '#': ligne = "# " + $("#ligne_comment").val();
                break;
    }

//alert('ligne = ' + ligne);

    switch(modeLigne) {
      case 'selection' :
            $("#vue li:eq(" + index_ligne_selectionnee +")").text(ligne);
            /*
						boutonsLigne = {	'entree_valider'	: false
                           };
            activerBoutonsLigne(boutonsLigne);*/
						boutons["entree_nouvelle"]			= true;
						boutons["entree_valider"]				= false;
						break;

      case 'nouvelle' :
						// Si c'est une nouvelle qui vient d'être créée...
						if (isNaN(index_ligne_selectionnee)) {
							INI[nomFichier][nomSection] = new Array();
						}						
						
            $("#vue").append($("<li>").text( ligne ));
						index_ligne_selectionnee = $("#vue li:last").index();
 //           nbLignes++;

            // Rafraichir l'index...
            $("#index").html(index_ligne_selectionnee);
            $("#vue li")
							.css({"color":ligne_couleur, "background-color":ligne_bg_couleur});
            $("#vue li:eq(" +index_ligne_selectionnee+")")
							.css({"color":ligne_couleur_select, "background-color":ligne_bg_couleur_select});
						
            // Pour faire apparaître la nouvelle ligne qui est ajoutée à la fin
            // On scroll jusqu'en bas de la fenêtre : 4000(px) pour forcer, 300 ms d'animation
            $("#vue").animate({ scrollTop: 4000}, 300);

						INI[nomFichier][nomSection].push(ligne);

						boutons["entree_nouvelle"]			= true;
						boutons["entree_valider"]				= false;
						boutons["entree_supprimer"]			= true;
						if(index_ligne_selectionnee > 0 )
							boutons["entree_deplacer_haut"] = true;
						else
							boutons["entree_deplacer_haut"] = false;
						
						if(index_ligne_selectionnee < nbLignes - 1 )
							boutons["entree_deplacer_bas"]	= true;
						else
							boutons["entree_deplacer_bas"]	= false;
						if(nbLignes >= 2) {
							boutons["entree_doublons"]			= true;
						} else {
							boutons["entree_doublons"]			= false;
						}
            break;

      case 'deplacement' :
						alert('XXXXXX');
						// ? boutons["entree_valider"]				= false;
						// ? Active_Boutons( boutons );
            return;
            break;
    }
		Active_Boutons( boutons );
		Appliquer("Lignes");
		
		// On re-"sélectionne" la ligne créée ou modifiée
		// pour une éventuelle modification immédiate
		modeLigne = 'selection';
		$("#vue").trigger('click');
	}

	/* --------------------------------------------------------- *\
			Bouton : entree_nouvelle
			Pour créer une nouvelle ligne
	\* --------------------------------------------------------- */

  function nouvelle_ligne() {
    modeLigne = "nouvelle";

    $("#vue li")          .css({"color":ligne_couleur, "background-color":ligne_bg_couleur});
		$("#index")           .html('');
		$("#flag_comment")    .removeAttr("disabled");
    $("#cle")             .val('')
                          .removeAttr("disabled")
                          .focus();
    $("#valeur")          .val('')
                          .removeAttr("disabled");
    $("#commentaire")     .val('')
                          .removeAttr("disabled");
//    $("#ligne_commentee") .val('')
    $("#ligne_comment") 	.val('')
                          .removeAttr("disabled");

    boutons["entree_nouvelle"]			= false;
    boutons["entree_valider"]				= false;
    boutons["entree_supprimer"]			= false;
    boutons["entree_deplacer_haut"] = false;
    boutons["entree_deplacer_bas"]	= false;
    boutons["entree_doublons"]			= false;
    Active_Boutons( boutons );
	}
	
	/* --------------------------------------------------------- *\
			Saisie dans "#cle, #valeur"
	\* --------------------------------------------------------- */

  function saisie_ligne() {
    switch(modeLigne) {
      case "nouvelle":
          if( $("#cle").val() != '' && $("#valeur").val() != '') {
						boutons["entree_valider"]				= true;
					} else {
						boutons["entree_valider"]				= false;
					}
					Active_Boutons( boutons );
					break;
      case "selection":
					activation_boutons_nouvelle_valider_ligne();
          break;
    }
  }
	
	/* --------------------------------------------------------- *\
			Saisie dans "#ligne_comment"
	\* --------------------------------------------------------- */

  function saisie_ligne_commentee() {
    switch(modeLigne) {
      case "nouvelle":
         if( $("#ligne_comment").val() != '') {
						boutons["entree_valider"]				= true;
					} else {
						boutons["entree_valider"]				= false;
					}
					Active_Boutons( boutons );
					break;
      case "selection":
					activation_boutons_nouvelle_valider_ligne();
          break;
    }
  }
	
	/* --------------------------------------------------------- *\
		Activation ligne Nouvelle-Valider
	\* --------------------------------------------------------- */
	function activation_boutons_nouvelle_valider_ligne() {
		if( 	 $("#cle")													.val() == CleLigneCourtOrigine
				&& $("#valeur")												.val() == ValeurLigneCourtOrigine
				&& $("#commentaire")									.val() == CommentaireLigneCourtOrigine
				&& $("#flag_comment option:selected")	.val() == flag_commentLigneCourtOrigine
				&& $("#ligne_comment")								.val() == ligne_commenteeLigneCourtOrigine
			) {
			boutons["entree_nouvelle"]			= true;
			boutons["entree_valider"]				= false;
		} else {
			boutons["entree_nouvelle"]			= false;
			boutons["entree_valider"]				= true;
		}
		Active_Boutons( boutons );
	}

	/* --------------------------------------------------------- *\
			Bouton : entree_doublons
			Recherche d'éventuels doublons des lignes de la section
			sélectionnée
			
			On exclut les lignes vides et les lignes commentées vides
			Tentative expérimentale d'affichage des doublons
			avec des couleurs différentes automatiques
			
			- Doublons sur les items d'une section
			- Doublons sur les URLs pour le fichier proxy-groups.ini
	\* --------------------------------------------------------- */

  function recherche_doublons_lignes() {
	
		var trouve = new Array;
		for(var k = 0; k < nbLignes; k++) {
			trouve[k] = false;
		}

		var p = 0;
		var item_k, item_j;
		var couleur;
		var nbDoublonsItems = 0;

		for (var k = 0; k < nbLignes; k++) {
			item_k = trim( $("#vue li:eq("+ k +")").html() );
			if(item_k == '' || item_k == '#') continue;

			for (var j = k+1; j < nbLignes; j++) {
				item_j = trim( $("#vue li:eq("+ j +")").html() );

				if(!trouve[k] && (item_j == item_k) ) {
					nbDoublonsItems++;
					trouve[j] = true;
					trouve[k] = true;
					$("#vue li:eq("+ k +"), #vue li:eq("+ j +")")
						.css({"color":couleurs[p]['texte'],"background-color":couleurs[p]['backg']});
					p++;
				}
			}
		}

		// ------- Cas perticulier du fichier proxy-groups.ini
		
		if(nomFichier == "proxy-groups.ini") {
			var nbDoublonsURLs = 0;
			var item_kk, item_jj;
			var trouve2 = new Array;
			for(var k = 0; k < nbLignes; k++) {
				trouve2[k] = false;
			}

			for (var k = 0; k < nbLignes; k++) {
				item_k = trim( $("#vue li:eq("+ k +")").html() );
				if(item_k == '' || item_k == '#') continue;
				
				var decoup = exploseLigne (item_k);
				item_kk = decoup['valeur'];
				if(item_kk == '') continue;
				
				for (var j = k+1; j < nbLignes; j++) {
					item_j = trim( $("#vue li:eq("+ j +")").html() );
					var decoup = exploseLigne (item_j);
					item_jj = decoup['valeur'];
					if(item_jj == '') continue;

					if(!trouve2[k] && (item_kk.includes(item_jj) || item_jj.includes(item_kk) ) ) {
						nbDoublonsURLs++;
						trouve2[j] = true;
						trouve2[k] = true;
					  $("#vue li:eq("+ k +"), #vue li:eq("+ j +")")
							.css({"color":couleurs[p]['texte'],"background-color":couleurs[p]['backg']});
						p++;
					}
				}
			}
		}

		var msg = alerte["fonctions3_4"]+" :\n\n";		// Recherche de doublons sur les lignes
		if (nbDoublonsItems == 0)
			msg += "\t- "+alerte["fonctions3_5"]+".\n";														// Aucun trouvé
		else
			msg += "\t- " + nbDoublonsItems + " "+alerte["fonctions3_6"]+".\n";		// trouvés

		if (nbDoublonsURLs > 0) {
			if(nbDoublonsURLs == 1) 
				msg += "\t- " + nbDoublonsURLs + " "+alerte["fonctions3_7"]+".\n";	// trouvé sur les URLs
			else
				msg += "\t- " + nbDoublonsURLs + " "+alerte["fonctions3_8"]+".\n";	// trouvés sur les URLs
		}
		alert(msg);
	}		

	
	/* ======================================================================================================= *\
			Aides
	\* ======================================================================================================= */
	
	/* --------------------------------------------------------- *\
			Activation - Désactivation de l'affichage de l'aide
	\* --------------------------------------------------------- */
	
  function afficher_aide( cocher_aide) {
    if(cocher_aide.checked) {
      $("#aide").css({"visibility":"visible","display":"block"});
    } else {
      $("#aide").css({"visibility":"collapse","display":"none"});
    }
	}
	
	/* --------------------------------------------------------- *\
		Contenu de l'aide contextuelle
		Le contenu de l'aide est stockée et traduite dans aide3.php
	\* --------------------------------------------------------- */
	
  function contenu_aide( event, aide_demandee) {
		var aide;
		if( event.type == 'mouseenter') {
			
			var objet = aide_demandee.attr("id");
			if ( typeof( aides[objet] ) == "undefined" ) {
				aide = "";
			} else {
				aide = aides[objet];
			}
			
		} else {					// mouseleave
			aide = "";
		}

		$("#aide").html(aide);
	}

	
	/* ======================================================================================================= *\
      Appliquer les changements :
      - Enregistre les modifications dans le tableau INI[][][]
      - Enregistre via Ajax les modifications sur le serveur
	\* ======================================================================================================= */
	
  function Appliquer(cible) {
    switch(cible) {
      case "Sections" : 
        for(var k = 0; k < nbSections; k++) {
            INI[nomFichier]['-'][k] = $("#sections li:eq("+ k +")").html();
          }
          break;

      case "Lignes" :
					if(modeLigne == "nouvelle") nbLignes++;      
          for(var k = 0; k < nbLignes; k++) {
            INI[nomFichier][nomSection][k] = $("#vue li:eq("+ k +")").html();
          }
          break;
    }

    var demande = {
      url			: "ajax_ecrire_modifications.php",
      type		: "POST",
      dataType: 'text',
      data		: {	INIs : JSON.stringify(INI),
                  nomFichier
                },
      success	: function(retour){
                  /*
                  alert(
                    "Enregistrement : " + retour
                  );*/
                },
      error		: function(retour){ alert( alerte["erreur_SP"]+' SP_01'); }
      };
    $.ajax(demande);
  }

/* --------------------------------------------------------- *\
    Fonction équivalente à trim() en PHP
\* --------------------------------------------------------- */

function trim (item) {
  return item.replace(/^\s+/g,'').replace(/\s+$/g,'');
} 
	
/* --------------------------------------------------------- *\
    Fonction pour trouver le nombre d'éléments de premier
    niveau dans un tableau associatif couleurs[][]
\* --------------------------------------------------------- */
	
function numProps(obj) {
  var c = 0;
  for (var key in obj) {
    if (obj.hasOwnProperty(key)) ++c;
  }
  return c;
}




	/* --------------------------------------------------------- *\
      Recherche globale des doublons
	\* --------------------------------------------------------- */
			
	var nbTotalDoublonsSections;	
	var nbTotalDoublonsLignes;	
	var nbTotalDoublonsURLs;	

  function recherche_globale_doublons() {
		nbTotalDoublonsSections = 0;	
		nbTotalDoublonsLignes = 0;	
		nbTotalDoublonsURLs = 0;
		
		var codeTable = getTR() + getFinTable();
		var total = getTRtotal();
		codeTable =	getDebutTable() + total + getTR() + getFinTable();

		$("#cache").css({"display":"block"});
		$("#resultats").html(codeTable);
	}
	
  function fermer_doublons_globaux() {	
		$("#cache").css({"display":"none"});
	}
		
	/* --------------------------------------------------------- *\
      Recherche de doublons sur les sections
	\* --------------------------------------------------------- */

  function recherche_doublons_sections_a(nomFichier, nbSections) {
		var trouve = new Array;
		for(var k = 0; k < nbSections; k++) {
			trouve[k] = false;
		}
	  var nomSection_k, nomSection_j;
		var nbDoublonsSections = 0;

    for (var k = 0; k < nbSections; k++) {
      nomSection_k = INI[ nomFichier ]['-'][k];
      for (var j = k+1; j < nbSections; j++) {
        nomSection_j = INI[ nomFichier ]['-'][j];
        if(nomSection_j == nomSection_k) {
          nbDoublonsSections++;
        }
      }
    }
		return nbDoublonsSections;
	}   

	/* --------------------------------------------------------- *\
			Recherche de doublons sur les lignes de la section
			
			On exclut les lignes vides et les lignes commentées vides
			Tentative expérimentale d'affichage des doublons
			avec des couleurs différentes automatiques
			
			- Doublons sur les items d'une section
			- Doublons sur les URLs pour le fichier proxy-groups.ini
	\* --------------------------------------------------------- */

  function recherche_doublons_lignes_a(nomFichier, nomSection, nbLignes) {
		var trouve = new Array;
		for(var k = 0; k < nbLignes; k++) {
			trouve[k] = false;
		}
		var ligne_k, ligne_j;
		var nbDoublonsLignes = 0;

		for (var k = 0; k < nbLignes; k++) {
			ligne_k = trim( INI[ nomFichier ][nomSection][k] );
			if(ligne_k == '' || ligne_k == '#') continue;

			for (var j = k+1; j < nbLignes; j++) {
				ligne_j = trim( INI[ nomFichier ][nomSection][j] );

				if(!trouve[k] && (ligne_j == ligne_k) ) {
					nbDoublonsLignes++;
					trouve[j] = true;
					trouve[k] = true;
				}
			}
		}
		return nbDoublonsLignes;
	}

	/* --------------------------------------------------------- *\
      Recherche de doublons sur les URLs :
      Cas particulier du fichier proxy-groups.ini
	\* --------------------------------------------------------- */
	
  function recherche_doublons_URLs (nomFichier, nomSection, nbLignes) {
			var nbDoublonsURLs = 0;
			var ligne_kk, ligne_jj;
			var trouve = new Array;
			for(var k = 0; k < nbLignes; k++) {
				trouve[k] = false;
			}

			for (var k = 0; k < nbLignes; k++) {
				item_k = trim( INI[ nomFichier ][nomSection][k] );
				if(item_k == '' || item_k == '#') continue;
				
				var decoup = exploseLigne (item_k);
				ligne_kk = decoup['valeur'];
				if(ligne_kk == '') continue;
				
				for (var j = k+1; j < nbLignes; j++) {
					item_j = trim( INI[ nomFichier ][nomSection][j] );
					var decoup = exploseLigne (item_j);
					ligne_jj = decoup['valeur'];
					if(ligne_jj == '') continue;

					if(!trouve[k] && (ligne_kk.includes(ligne_jj) || ligne_jj.includes(ligne_kk) ) ) {
						nbDoublonsURLs++;
						trouve[j] = true;
						trouve[k] = true;
					}
				}
			}
		return nbDoublonsURLs;
	}		

/* --------------------------------------------------------- *\
    getDebutTable
\* --------------------------------------------------------- */
		function getDebutTable() {
			var codeDebutTable = "";
			codeDebutTable += "<table class='bordure'><thead><tr>";
			codeDebutTable += "<th>Fichier</th>";
			codeDebutTable += "<th>Doublons sur<br />les sections</th>";
			codeDebutTable += "<th>Section</th>";
			codeDebutTable += "<th>Doublons sur<br />les lignes</th>";
			codeDebutTable += "<th>Doublons sur les noms<br />de domaine</th>";
			codeDebutTable += "</tr></thead><tbody>";
			return codeDebutTable;
		}
	
/* --------------------------------------------------------- *\
    getTRtotal
\* --------------------------------------------------------- */
		function getTRtotal() {
			var codeTotal = "";
			codeTotal += "<tr id='total'>";
			codeTotal += "<td>Total</td>";
			codeTotal += "<td>"+nbTotalDoublonsSections+"</td>";
			codeTotal += "<td>&nbsp;</td>";
			codeTotal += "<td>"+nbTotalDoublonsLignes+"</td>";
			codeTotal += "<td>"+nbTotalDoublonsURLs+"</td>";
			codeTotal += "</tr>";
			return codeTotal;
		}
/* --------------------------------------------------------- *\
    getTR
\* --------------------------------------------------------- */
		function getTR() {
			var nbDoublonsSections = 0;
			var nbDoublonsLignes = 0;
			var nbDoublonsURLs = 0;
			
			var nbFichiers = fichiersINI.length;
			var nomFichier;
			var nbLignes;

			var codeTable = "";
			var cc, aa;

			for(var k = 0; k < nbFichiers; k++) {					// ----- Fichiers
				nomFichier = fichiersINI[k];
				nbSections	= INI[ nomFichier ]['-'].length;

				nbDoublonsSections = recherche_doublons_sections_a(nomFichier, nbSections);
				nbTotalDoublonsSections += nbDoublonsSections;
				cc = k%2;
				for(var j = 0; j < nbSections; j++) {				// ----- Sections
					if(j == 0) {
						codeTable += "<tr class='c"+ cc +"'>";
						codeTable += "<td rowspan='"+ nbSections +"'>"+ nomFichier +"</td>";
						codeTable += "<td rowspan='"+ nbSections +"'>"+ nbDoublonsSections +"</td>";
					} else {
						codeTable += "<tr>";
					}
					nomSection = INI[ nomFichier ]['-'][j];
					nbLignes = INI[ nomFichier ][nomSection].length;
					aa = (j+1)%2;
					codeTable += "<td class='a"+ cc+aa +"'>"+ nomSection +"</td>";

					nbDoublonsLignes = recherche_doublons_lignes_a(nomFichier, nomSection, nbLignes);
					nbTotalDoublonsLignes += nbDoublonsLignes;
					if(nbDoublonsLignes == 0) nbDoublonsLignes = "-";
					codeTable += "<td class='a"+ cc+aa +"'>"+ nbDoublonsLignes +"</td>";
					
					if(nomFichier == "proxy-groups.ini") {
						nbDoublonsURLs = recherche_doublons_URLs(nomFichier, nomSection, nbLignes);
						nbTotalDoublonsURLs += nbDoublonsURLs;
						if(nbDoublonsURLs == 0) nbDoublonsURLs = "-";
						codeTable += "<td class='a"+ cc+aa +"'>"+ nbDoublonsURLs +"</td>";
					} else {
						codeTable += "<td class='a"+ cc+aa +"'>-</td>";
					}
					codeTable += "</tr>";					
				}
			}	
			return codeTable;
		}

/* --------------------------------------------------------- *\
    getFinTable
\* --------------------------------------------------------- */
		function getFinTable() {
			return "</tbody></table>";
		}

/* --------------------------------------------------------- *\
    
\* --------------------------------------------------------- */
