<?php
/* --------------------------------------------------------- *\
	aide3.php
	Contenu de l'aide contextuelle
	du script config-filtres.php via script_fonctions3.js
\* --------------------------------------------------------- */

$aide = array(
"section_nouvelle"						=> _("Crée une nouvelle section du fichier sélectionné."),
"section_valider"							=> _("Applique votre modification ou la création d'une nouvelle section."),
"section_supprimer"						=> _("Supprime une section ET toutes les lignes qu'elle contient."),
"section_deplacer_haut"				=> _("Déplace la section sélectionnée vers le haut."),
"section_deplacer_bas"				=> _("Déplace la section sélectionnée vers le bas."),
"section_doublons"						=> _("Recherche les doublons sur le nom des sections."),

"flag_comment"								=> _("Mettre ou non cette ligne complètement en commentaire."),
"cle"													=> _("Saisir ou modifier le contenu de la clé."),
"valeur"											=> _("Saisir ou modifier le contenu de la valeur."),
"commentaire"									=> _("Ajouter ou non un commentaire pour cette ligne."),
"entree_nouvelle"							=> _("Crée une nouvelle ligne."),
"entree_valider"							=> _("Applique votre modification ou la création d'une nouvelle ligne."),
"entree_supprimer"						=> _("Supprime la ligne sélectionnée."),
"entree_doublons"							=> _("Recherche les éventuels doublons sur les lignes de la section en cours."),
"entree_deplacer_haut"				=> _("Déplace la ligne sélectionnée vers le haut."),
"entree_deplacer_bas"					=> _("Déplace la ligne sélectionnée vers le bas."),
"recherche_globale_doublons"	=> _("Effectue une recherche globale de tous les doublons éventuels.&lt;br />&nbsp;&lt;br />Pour chaque section de chaque fichier.")
);

echo "var aides = ".json_encode($aide).";".PHP_EOL;

?>
