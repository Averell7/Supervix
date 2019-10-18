<?php
/* --------------------------------------------------------- *\
	chaines7.php
	Contenu de certaines de caractères dans les javascripts
\* --------------------------------------------------------- */

$chaines = array(
"Identifiant"						=> _("Identifiant"),
"Mot_de_passe"					=> _("Mot de passe")
);

echo "var chaine = ".json_encode($chaines).";".PHP_EOL;
?>
