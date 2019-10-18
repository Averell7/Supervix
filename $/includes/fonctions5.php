<?php
switch (PHP_OS) {
	case 'Linux': $IP = $_SERVER['SERVER_ADDR'];
								break;
	case 'WINNT': $IP = "192.168.1.184";
								/* Pour test sous Windows
								On lit les données sur http://idefix_dev/0.3/alpha-4/bandwidthd/
								qui sont une copie (temporaire) de http://idefix.chartreux.org/bandwidthd/
								Mais pour obtenir les bonnes URLs des stats du sous-réseau
								et calculer $subnet : 192.168.1.0
								*/
								//echo $IP;
								break;
	default			: exit;
}

$b = explode(".", $IP);
$subnet = $b[0].".".$b[1].".".$b[2].".0";

/* ------------------------------------------------------ *\
		Fonctions de reconfiguration des rapports Bandwidthd
\* ------------------------------------------------------ */

/*
PATH_RACINE_BANDWIDTHD

$urlBase = "http://idefix_dev/0.3/alpha-4/bandwidthd/";

$url = $urlBase."index.html";
*/

/* -------------------------------------------------- *\
		Obtenir le nouveau code de la table nettoyée
\* -------------------------------------------------- */

//function getTable($url, $urlBase = "http://idefix.chartreux.org/bandwidthd/") {
function getTable($url) {
	//$urlBase = "http://idefix_dev/0.3/alpha-4/".PATH_RACINE_BANDWIDTHD;
	$urlBase = getBaseUrl().PATH_RACINE_BANDWIDTHD;
	
	//echo "urlBase = ".$urlBase."<br />\n";exit;

	$urlBandwidthd = $urlBase.$url;
//	echo "Localisation actuelle des données Bandwidthd= ".$urlBandwidthd."<br />\n";
//echo "urlBandwidthd = ".$urlBandwidthd."<br />\n";exit;
	
	$result = getCodeSource($urlBandwidthd);
	return nettoyerCodeSourceTable($result);
}

/* -------------------------------------------------- *\
		Retourne le code source de la page
\* -------------------------------------------------- */

function getCodeSource($url) {
	// Initialiser cURL
	$curl = curl_init();
	// Définir l'adresse à ouvrir
	curl_setopt($curl, CURLOPT_URL, $url);
	// Suivre les redirections s'il y en a
	@curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	//curl_setopt($curl, CURLOPT_HEADER, 0);

	// Pour ne pas afficher le résultat dans la page
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	// Exécuter
	$result = curl_exec($curl);
	// Fermer pour libérer des ressources systèmes
	curl_close($curl);
	//echo htmlentities($result);
	return $result;
}

/* -------------------------------------------------- *\
		'Nettoyage' du code source de la <table>
\* -------------------------------------------------- */

function nettoyerCodeSourceTable($result) {
	// Tout mettre en minuscules :
	$resultTable = strtolower ( $result );

	// Recherche de la position de début de table
	$findme   = '<table';
	$pos = strpos($resultTable, $findme);

	$resultTable = substr($resultTable, $pos);

	$findme   = '</table>';
	$pos = strpos($resultTable, $findme);
	$resultTable = substr($resultTable, 0, $pos + 8);

	$healthy = array(' width="100%" border=1 cellspacing=0',
									 ' bgcolor=lightblue',
									 ' align=center',
									 ' align="right"',
									 '<tt>',
									 '</tt>',
									 '&nbsp;',
									 '<tr><td>ip and name<td>total<td>total sent<td>total received<td>ftp<td>http<td>mail<td>p2p<td>tcp<td>udp<td>icmp',
									 '</table>',
									 'k<',
									 'm<',
									 'g<',
									 '<a href="#total-1">total</a>'
	 );
	$yummy   = array('', '', '', '', '', '', '', '<thead><tr><th>Ip and Name</th><th>Total</th><th>Total sent</th><th>Total received</th><th>FTP</th><th class="http">HTTP</th><th>MAIL</th><th class="p2p">P2P</th><th class="tcp">TCP</th><th class="udp">UDP</th><th class="icmp">ICMP</th></tr></thead><tbody>', '</tbody></table>', ' Ko<', ' Mo<', ' Go<', '<a href="#Total-1">Total</a>');
	$resultTable = str_replace($healthy, $yummy, $resultTable);
	return $resultTable;
}

/* -------------------------------------------------- *\
		Obtenir la base de l'url
		Exemple :
		http://idefix_dev/0.3/alpha-4/bandwidthd_jour.php
		retourne
		http://idefix_dev/0.3/alpha-4/
\* -------------------------------------------------- */

function getBaseUrl() {
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$path_parts = pathinfo($actual_link);
	return $path_parts['dirname']."/";
}

?>
