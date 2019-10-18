<?php
if( isset($_SESSION['lastActivity']) && time() < $_SESSION['lastActivity'] + TPSMAXSESSION ) {
	$_SESSION['lastActivity'] = time();		// Actualisation de la valeur stockée
} else {
	header('Location: /acces.php');		// Demande Mot de passe
	exit;
}
?>