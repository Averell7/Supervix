_<?php session_start();
include_once "$/i18n/localization.php";

if (!isset($_POST['cmd'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}

/* -------------------- *\
	Appel depuis :
		update-config.php
		update-system.php
		reboot-idefix.php
		halt_idefix.php
		infos_system
		config-restauration-etc-usr.php
		config-restauration-home.php
\* -------------------- */

$cmd		= $_POST['cmd'];
$bouton = $_POST['bouton'];
if($cmd == '' || $bouton == '') {
	echo "<p class='rouge'>"._("À implémenter.")."</p>";
	exit;
}
//echo "\$cmd ".$cmd; exit;

if(PHP_OS == 'Linux') {
	$locale = 'fr_FR.UTF-8';
	setlocale(LC_ALL, $locale);
	putenv('LC_ALL='.$locale);
	if($bouton == 'reboot_idefix' || $bouton == 'halt_idefix' ) {
		/* echo "<p class='vert'>Commande effectuée.</p>"; */
		shell_exec($cmd);
		exit;
	} else {
		$retour = shell_exec($cmd);
		$retour = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br />",$retour);
		echo "<p class='vert'>".$retour."</p>";
	}
} else {
	echo "<p class='rouge'>"._("Cette commande ne peut pas être exécutée car vous n'êtes pas sous Linux.")."</p>";
}
?>

