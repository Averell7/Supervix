<?php session_start();

if (!isset($_POST['fichiersZip'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/fonctions12.php";

$fichiersZip = $_POST['fichiersZip'];
/*
echo "<pre>";
echo print_r($fichiersZip);
echo "</pre>";
exit;
*/

switch(BACKUP_PLACE) {
	case "locales":
							deleteBackupsZip_local($fichiersZip);
							break;

	case "ftp":
							$compteFTP = getCompteFTP();
							$conn_id = @ftp_connect($compteFTP['ftp'], 21, 4) or die (_("Impossible de se connecter au serveur")." ".$compteFTP['ftp']);
							// Identification avec un nom d'utilisateur et un mot de passe
							$login_result = ftp_login($conn_id, $compteFTP['ftp_user_name'], $compteFTP['ftp_user_pass']);

							// Vérification de la connexion
							if ((!$conn_id) || (!$login_result)) {
								echo "<p>"._("La connexion FTP a échoué.")."</p>";
								echo "<p>"._("Tentative de connexion au serveur")." <b>".$compteFTP['ftp']."</b></p>";
								echo "<p>"._("pour l'utilisateur")." <b>".$compteFTP['ftp_user_name']."</b></p>";
								return;
							}

							ftp_pasv($conn_id, true);

							deleteBackupsZip_ftp($conn_id, $fichiersZip);

							// Fermeture du flux FTP
							ftp_close($conn_id);
							break;
}
	

/* --------------------------------------------------------- *\
		Effacement des backups
\* --------------------------------------------------------- */

function deleteBackupsZip_local($fichiersZip) {
	echo "<p>"._("Fichiers de backup supprimés")." :</p>";
	foreach ($fichiersZip as $ficZip) {
		if(PHP_OS == 'Linux') {
			$commande = 'sudo /bin/rm -f '.DIR_BACKUP_LOCAL.$ficZip.' 2>&1';			
			shell_exec($commande);
		}
		else {
			//echo DIR_BACKUP_LOCAL.$ficZip ."<br />";	
			unlink( DIR_BACKUP_LOCAL.$ficZip );	
		}
		echo $ficZip."<br />";
	}
	echo "<p style='margin:1.5rem 0 0 0;'><input type='button' id='reload' value='"._("Rafraîchir")."' /></p>";
}
	
function deleteBackupsZip_ftp($conn_id, $fichiersZip) {
	echo "<p>"._("Fichiers de backup supprimés")." :</p>";
	foreach ($fichiersZip as $ficZip) {
		ftp_delete($conn_id, "backup/".$ficZip);
		echo $ficZip."<br />";
	}
	echo "<p style='margin:1.5rem 0 0 0;'><input type='button' id='reload' value='"._("Rafraîchir")."' /></p>";
}
?>
