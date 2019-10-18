<?php session_start();

if (!isset($_POST['dir_backup'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";

$dir_backup						= $_POST['dir_backup'];
$dossiers_a_comparer	= $_POST['dossiers_a_comparer'];
$fichiers_exclus			= $_POST['fichiers_exclus'];
$extensions_exclues		= $_POST['extensions_exclues'];
$url									= $_POST['url'];


$debug = false;
//echo $url; exit;
if($debug)
	echo "<p><b>Mode debug !</b></p><p>Fichiers qui seront restaurés :</p>";
else
	echo "<p>Fichiers restaurés :</p>";
	
foreach($dossiers_a_comparer as $dossier) {
	$dir_source = DIR_TEMP.$dir_backup."/".$dossier;
	$dir_dest = $dossier;
	copyDossier($dir_source, $dir_dest);
}

if($debug) {
	echo "<p><b>Restauration non effectuée :</b> mode debug</p>";
}
else {
	echo _("Restauration effectuée.");
}

switch($url) {
	case 'config-restauration-etc-usr.php'	:
				echo "<p><input type='button' id='reboot_idefix' value=\"Redémarrage d'Idéfix nécessaire\" /></p>";
				break;
	case 'config-restauration-home.php'			: 
				echo "<p><input type='button' id='update_config' value=\"Mettre à jour la configuration d'Idéfix\" /></p>";
				break;
}


function fichierRetenu($d, $fichier) {
	if( !is_dir( $d."/".$fichier ) ) {
			if( !in_array($fichier, $GLOBALS['fichiers_exclus'] ) ) {
				$fileinfo = pathinfo($fichier);
				$ext = strtolower($fileinfo["extension"]);
				
				if (!in_array($ext, $GLOBALS['extensions_exclues'])) {
					return true;
				}
			}
	}
	return false;
}

function copyDossier($dir_source, $dir_dest) {
	global $debug;
	$fichiers = scandir($dir_source, 1);
	
	foreach($fichiers as $ff) {
		if( fichierRetenu($dir_source, $ff) ) {
				$f_s = $dir_source.$ff;							
				$f_d = $dir_dest.$ff;
				if($debug)
					echo "<p class='left'>".$f_d."</p>\n";
				else
					copy( $f_s , $f_d );
				
		}
	}
}
?>
