<?php
/* --------------------------------------------------------- *\
	alert.php
	Contenu des alert()
	Scripts : script_fonctions3.js
						script_ready_nav3.js
						script_ready_nav4.js
						script_ready_nav7.js
						script_ready_nav8.js
						script_ready_nav11.js
\* --------------------------------------------------------- */

$alert = array(
"erreur_SP"								=> _("Erreur"),
/* script_fonctions3.js */
"fonctions3_1"						=> _("Aucun doublon trouvé\nsur les sections"),
"fonctions3_2"						=> _("Doublons trouvés\nsur les sections"),
"fonctions3_3"						=> _("Compléter votre saisie."),
"fonctions3_4"						=> _("Recherche de doublons sur les lignes"),
"fonctions3_5"						=> _("Aucun trouvé"),
"fonctions3_6"						=> _("trouvés"),
"fonctions3_7"						=> _("trouvé sur les URLs"),
"fonctions3_8"						=> _("trouvés sur les URLs"),
/* script_ready_nav3.js */
"ready_nav3_1"						=> _("Caractère interdit."),
/* script_ready_nav4.js */
"ready_nav4_1"						=> _("Veuillez compléter les champs suivants"),
"ready_nav4_2"						=> _("Le port doit être inférieur à 65535 !"),
"ready_nav4_3"						=> _("Veuillez corriger l'adresse IP"),
/* script_ready_nav7.js */
"ready_nav7_1"						=> _("Erreur : choix non implémenté."),
"ready_nav7_2"						=> _("Veuillez compléter les champs suivants"),

"ready_nav7_3"						=> _("Attention"),
"ready_nav7_4"						=> _("La modification des paramètres FTP est critique"),
"ready_nav7_5"						=> _("Elle va entraîner l'effacement total"),
"ready_nav7_6"						=> _("de la configuration actuelle"),
"ready_nav7_7"						=> _("Continuer ?"),

/* script_ready_nav8.js */
"ready_nav8_1"						=> _("Redémarrage en cours"),
"ready_nav8_2"						=> _("Arrêt en cours"),
/* script_ready_nav11.js */
"ready_nav11_1"						=> _("Vous demandez la restauration depuis"),
"ready_nav11_2"						=> _("la sauvegarde du"),
"ready_nav11_3"						=> _("Vous confirmer ?")
);

echo "var alerte = ".json_encode($alert).";".PHP_EOL;

?>
