<?php session_start();
include_once "$/i18n/localization.php";

if (!isset($_POST['mdp_old'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}

/* ----------------------------- *\
		Changement du  mot de passe
\* ----------------------------- */

$mdp_new = $_POST["mdp_new"];
$mdp_old = $_POST["mdp_old"];

$fname = "$/settings/dbpsw.avm";
$fnum = fopen($fname,"r");
$password = fgets($fnum);
fclose($fnum);
$fname2 = "$/settings/vsftpd_login.txt";

if (password_verify($mdp_old, $password)) {
	$options = [
		'cost' => 11
	];
	$password = password_hash($mdp_new, PASSWORD_BCRYPT, $options);
	$fnum = fopen($fname,"w");
	fwrite($fnum, $password);
	fclose($fnum);

	// modify ftp password on Idefix
	$fnum = fopen($fname2,"w");
	fwrite($fnum, "confix\n".$mdp_new."\n");
	fclose($fnum);
        shell_exec("db_load -T -t hash -f /var/www/idefix/$/settings/vsftpd_login.txt /home/rock64/idefix/vsftpd_login.db");
	shell_exec("/usr/sbin/service vsftpd restart");
	// delete the text of the file because the password is in clear in it
	$fnum = fopen($fname2,"w");
	fwrite($fnum, "");
	fclose($fnum);

	//$_SESSION['Password'] = $password;
	$_SESSION['lastActivity'] = time();
	echo _("Le nouveau mot de passe a bien été enregistré.");
} else {
	echo _("Modification refusée.<br />Votre ancien mot de passe est invalide.");
}
?>

