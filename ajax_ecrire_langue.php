<?php session_start();
include_once "$/includes/init.php";

if (!isset($_POST['langue'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}

/* ----------------------------------- *\
		Sauvegarde de la langue souhaitée
\* ----------------------------------- */

$langue = $_POST["langue"];
$fileopen = fopen(PATH_FILE_LANGUAGE,'w');
fwrite($fileopen, $langue.chr(10));	// Forcer fin de ligne LF
fclose($fileopen);
?>

