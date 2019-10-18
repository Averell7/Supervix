<?php session_start();
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";

$fichiersCONF = [PATH_ETC.'dhcp/dhcpd.conf', PATH_ETC.'network/interfaces.d/eth1', PATH_ETC.'ddclient.conf', PATH_ETC.'idefix/idefix.conf'];

$table  = "<table><tbody><tr>";
$k = 0;
foreach($fichiersCONF as $filename) {
	$table .= "<td><h3>".$fichiersCONF[$k]."</h3><div><pre>";
	$k++;
	if(file_exists($filename)) {
		$contenuFichier = file_get_contents ( $filename );
		$contenuFichier = str_replace(CRLF, LF, $contenuFichier);
		$contenuFichier = str_replace(CR, LF, $contenuFichier);
		$contenuFichier = str_replace(LF, "<br />", $contenuFichier);
		$table .= $contenuFichier;
	} else {
		$table .= _("Ce fichier n'a pas été trouvé.");
	}
	$table .= "</pre></div></td>";
	if($k == 2) $table .= "</tr><tr>";
}
$table .= "</tr></tbody></table>";
		
echo $table;
?>
