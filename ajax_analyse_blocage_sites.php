<?php session_start();

if (!isset($_POST['ip'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
/**/
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";

/* -------------------------- *\
	Appel depuis :
		analyse_blocage_sites.php
\* -------------------------- */

$titres = ["URL", "DENIED", "ALLOWED", "Total"];
/*	
	$time_start = $_POST['time_start'];
	echo $time_start."<br />";
	$ip 				= $_POST['ip'];
	$cmd = 'sudo /usr/lib/idefix/squid-log.py --ip='.$ip. ' --time_start='.$time_start;
	echo $cmd."<br />";
	exit;
*/	
if(PHP_OS == 'Linux') {
	$locale = 'fr_FR.UTF-8';
	setlocale(LC_ALL, $locale);
	putenv('LC_ALL='.$locale);
	
	$ip 				= $_POST['ip'];
	$time_start = $_POST['time_start'];
	//$time_end 	= $_POST['time_end'];
	//$proxy			= $_POST['proxy'];
	//$firewall 	= $_POST['firewall'];

	$cmd = 'sudo /usr/lib/idefix/unbound-log.py --ip='.$ip. ' --time_start='.$time_start;
	$sites = json_decode( shell_exec($cmd) );
	
	/*
	$sites = [["pki.goog", "", "9", "9"], ["ownpage.fr", "", "7", "7"], ["bing.com", "", "6", "6"], ["askubuntu.com", "", "1", "1"]];
	for ( $pos=0; $pos < strlen($sites); $pos ++ ) {
	 $byte = substr($sites, $pos);
	 echo 'Octet ' . $pos . ' de $sites a comme valeur ' . ord($byte)."<br />" . PHP_EOL;
	}
	//$sites = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n")," ",$retour);
	*/
} else {
	// Test pour Windows :

	$sites = [["pki.goog", "", "9", "9"],
	["ownpage.fr", "", "7", "7"],
	["bing.com", "", "6", "6"],
	["squid-cache.org", "", "6", "6"],
	["gstatic.com", "", "6", "6"],
	["trafficmanager.net", "", "5", "5"],
	["sstatic.net", "", "4", "4"],
	["gravatar.com", "", "4", "4"],
	["skype.com", "", "4", "4"],
	["live.com", "", "4", "4"],
	["tagcommander.com", "", "31", "31"],
	["mozilla.net", "", "3", "3"],
	["msn.com", "", "3", "3"],
	["usertrust.com", "", "3", "3"],
	["localhost.localhost", "", "24", "24"],
	["amazontrust.com", "", "2", "2"],
	["krxd.net", "", "2", "2"],
	["stackoverflow.com", "", "2", "2"],
	["digicert.com", "", "18", "18"],
	["la-croix.com", "", "16", "16"],
	["mozilla.com", "", "15", "15"],
	["krxd.net", "", "2", "2"],
	["stackoverflow.com", "", "2", "2"],
	["digicert.com", "", "18", "18"],
	["la-croix.com", "", "16", "16"],
	["mozilla.com", "", "15", "15"],
	["firefox.com", "", "13", "13"],
	["doubleclick.net", "", "1", "1"],
	["letsencrypt.org", "", "1", "1"],
	["google-analytics.com", "", "1", "1"],
	["askubuntu.com", "", "1", "1"]];
}
	
// ------ Mise en forme des résultats
	
$table = "<table>\n";
$tr = "<tr>";
foreach($titres as $titre) {
	$tr .= "<th>".$titre."</th>";
}
$table .= $tr."</tr>\n";

if(count($sites) == 0 ) {
	$table .= "<tr class='aucunresultat'><td colspan='".count($titres)."'>--- Aucun résultat ---</td></tr>\n";
} else {
	for($i = 0; $i < count($sites); $i++) {
		$tr = "<tr>";
		foreach($sites[$i] as $site) {
			$tr .= "<td>".$site."</td>";
		}
		$table .= $tr."</tr>\n";
	}
}

$table .= "</table>\n";
echo $table;
?>

















