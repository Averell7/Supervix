<?php
/*------------------------------------------*\
  Collecte des données sur l'état du système
	------------
	Copyright © 2019
\*------------------------------------------*/

$debug = false;
include_once "$/includes/fonctions8.php";
$locale = 'fr_FR.UTF-8';
setlocale(LC_ALL, $locale);
putenv('LC_ALL='.$locale);

// ----------------------------------------------------

if(PHP_OS == 'Linux') {
	$retour = shell_exec('LC_TIME=fr_FR date');
} else {	// Pour map sous Windows
	$retour = "mardi 2 avril 2019, 11:57:29 (UTC+0200)";
}
$data = explode ( "," , $retour );
$out["hardware"]["date"] = $data[0];

// ----------------------------------------------------

if(PHP_OS == 'Linux') {
	$retour = shell_exec('uptime');
} else {	// Pour map sous Windows
	$retour = " 18:39:23 up 6 days,  2:06 	,  1 user,  load average: 0,00, 0,00, 0,00";
	$retour = " 21:08:24 up 12:07 					,  1 user,  load average: 0,64, 0,58, 0,52";
	$retour = " 08:11:22 up 146 days, 34 min, 3 users, load average: 0.28, 0.45, 0.38";
}
$data = explode ( "up" , $retour );

//	if($debug) echo trim($data[0])."<br />";
$out["hardware"]["hour"] = trim($data[0]);
//	if($debug) echo "hour = ".$out["hardware"]["hour"]."<br />";

$data2 = explode ( "user" , $data[1] );
//	if($debug) echo $data2[0]."<br />";

$pos = strrpos ($data2[0], ',');
//	if($debug) echo $pos."<br />";

$rest = substr($data2[0], 0, $pos);
$out["hardware"]["up"]["up"] = trim($rest);
//	if($debug) echo "up = ".$out["hardware"]["up"]["up"]."<br />";

$rest = substr($data2[0], $pos+1); 
$out["hardware"]["up"]["user"] = intval($rest);
//	if($debug) echo "nb users = ".$out["hardware"]["up"]["user"] ."<br />";

// ----------------------------------------------------

if(PHP_OS == 'Linux') {
	$retour = shell_exec('cat /proc/loadavg');
} else {	// Pour map sous Windows
	$retour = "0.125 0.07 0.10 1/167 26204";
}
$data = explode ( " " , $retour );
//	if($debug) echo floatval($data[0])."<br />";
$out["hardware"]["CPU load"]["1mn"] = (float)$data[0];
//	if($debug) echo floatval($data[1])."<br />";
$out["hardware"]["CPU load"]["5mn"] = (float)$data[1];
//	if($debug) echo floatval($data[2])."<br />";
$out["hardware"]["CPU load"]["15mn"] = (float)$data[2];//number_format( (float)$data[2], 2);

// ----------------------------------------------------

if(PHP_OS == 'Linux') {
	$retour = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
} else {	// Pour map sous Windows
	$retour = "44545";
}
$temperature = intval($retour) / 1000;
//	if($debug) echo $temperature."<br />";
$out["hardware"]["CPU temperature"] = (float)$temperature;

// ----------------------------------------------------
function getInfosHD() {
	$retour = shell_exec('ls /dev');
	$data = explode ( "\n" , $retour );
	for($i = 0; $i < count($data); $i++) {
		if (strpos($data[$i], "mmcblk") !== false) {
			$p = substr($data[$i], 0, 7)."p1";
			return shell_exec('df -h /dev/'.$p);
		}
	}
}
// ----------------------------------------------------

if(PHP_OS == 'Linux') {
	$retour = getInfosHD();
} else {	// Pour map sous Windows
	$retour = "Sys. de fichiers Taille Utilisé Dispo Uti% Monté sur\n/dev/mmcblk1p7      30G    1,1G   27G   4%";
}
$data = explode ( "\n" , $retour );
//	if($debug) echo $data[1]."<br />";
$retour = trimSpaces($data[1], true);

$data2 = explode ( " " , $retour );
$out["hardware"]["disk size"]["size"] = substr($data2[1], 0, -1);
$out["hardware"]["disk size"]["unit"] = substr($data2[1], -1)."o";
$out["hardware"]["disk occup"]["size"] = substr($data2[2], 0, -1);
$out["hardware"]["disk occup"]["unit"] = substr($data2[2], -1)."o";
$out["hardware"]["disk libre"]["size"] = substr($data2[3], 0, -1);
$out["hardware"]["disk libre"]["unit"] = substr($data2[3], -1)."o";
$out["hardware"]["disk used"]["size"] = substr($data2[4], 0, -1);
$out["hardware"]["disk used"]["unit"] = substr($data2[4], -1);

// ----------------------------------------------------

if(PHP_OS == 'Linux') {
	$retour = shell_exec('cat /proc/meminfo');
} else {	// Pour map sous Windows
	$retour = "MemTotal:        4018412 kB\nMemFree:         3590028 kB\nMemAvailable:    3785748 kB\nBuffers:           45608 kB\nCached:           202888 kB";
}
$data = explode ( "\n" , $retour );
for($i = 0; $i < 3; $i++) {
	//echo $data[$i]."<br />";
	$r = trimSpaces($data[$i], true);
//	if($debug) echo $r."<br />";
	$r = explode ( " " , $r );
//	if($debug) echo substr($r[0], 0, -1)." - ".$r[1]." - ".$r[2]."<br />";
	$out["hardware"][substr($r[0], 0, -1)]["size"] = (int)($r[1]/1000);
	$out["hardware"][substr($r[0], 0, -1)]["unit"] = "Go";
}

// ----------------------------------------------------

// =========   Testing config  ============

/* La collecte de ces informations ne peut être effectuée que par un script python */

if(PHP_OS == 'Linux') {
	$retour = shell_exec("sudo /usr/lib/idefix/info3a.py");
} else {	// Pour map sous Windows
	$retour = '{"Testing config": {"Nftables": "OK", "Proxy": "OK"}}';
}
$z = json_decode($retour, true, 512);
//	if($debug) echo $z["Testing config"]["Nftables"]."<br />";
//	if($debug) echo $z["Testing config"]["Proxy"]."<br />";

// Injection dans le tableau $out[]

if( $z["Testing config"]["Nftables"] == "OK" )
	$out["Testing config"]["Nftables"] = "green";
else	/* Invalid */
	$out["Testing config"]["Nftables"] = "red";

if( $z["Testing config"]["Proxy"] == "OK" )
	$out["Testing config"]["Proxy"] = "green";
else	/* Invalid */
	$out["Testing config"]["Proxy"] = "red";

// ----------------------------------------------------

// =========   Testing Services  ============

$Services = array("squid"						=> "Squid",
									"isc-dhcp-server" => "DHCP server",
//									"dhcpcd"					=> "DHCP client",
// Pour rajouter ce param�tre, il faut aussi corriger script_ready_nav2
									"ddclient"				=> "ddclient",
									"bandwidthd"			=> "Bandwidthd",
									"vsftpd"					=> "FTP server");
foreach($Services as $key => $servicename) {
//	if($debug) echo "key : $key === value : $servicename<br />";
	if(PHP_OS == 'Linux') {
		$retour = shell_exec("/usr/sbin/service ".$key." status");
	} else {	// Pour map sous Windows
		switch($servicename) {
			case "Squid" :
				$retour = "● squid.service - LSB: Squid HTTP Proxy version 3.x
					 Loaded: loaded (/etc/init.d/squid; generated; vendor preset: enabled)
					 Active: active (running) since Mon 2019-04-01 21:17:05 CEST; 14h ago
						 Docs: man:systemd-sysv-generator(8)";
				break;
			case "DHCP server":
				$retour = "● isc-dhcp-server.service - LSB: DHCP server
					 Loaded: loaded (/etc/init.d/isc-dhcp-server; generated; vendor preset: enabled)
					 Active: active (running) since Mon 2019-04-01 21:17:07 CEST; 20h ago
						 Docs: man:systemd-sysv-generator(8)";
				break;
			case "DHCP client":
				$retour = "● dhcpcd.service - LSB: IPv4 DHCP client with IPv4LL support
					 Loaded: loaded (/etc/init.d/dhcpcd; generated; vendor preset: enabled)
					 Active: active (running) since Mon 2019-04-01 21:17:07 CEST; 20h ago
						 Docs: man:systemd-sysv-generator(8)";
				break;
			case "ddclient":
				$retour = "● ddclient.service - LSB: Update dynamic domain name service entries
					 Loaded: loaded (/etc/init.d/ddclient; generated; vendor preset: enabled)
					 Active: active (running) since Mon 2019-04-01 21:17:05 CEST; 20h ago
						 Docs: man:systemd-sysv-generator(8)";
				break;
			case "Bandwidthd":
				$retour = "● bandwidthd.service - LSB: starts and stops the BandwidthD daemon.
					 Loaded: loaded (/etc/init.d/bandwidthd; generated; vendor preset: enabled)
					 Active: active (running) since Mon 2019-04-01 21:17:03 CEST; 20h ago
						 Docs: man:systemd-sysv-generator(8)";
				break;
			case "FTP server":
				$retour = "● vsftpd.service - vsftpd FTP server
					 Loaded: loaded (/lib/systemd/system/vsftpd.service; enabled; vendor preset: enabled)
					 Active: active (running) since Mon 2019-04-01 21:17:03 CEST; 20h ago
					Process: 806 ExecStartPre=/bin/mkdir -p /var/run/vsftpd/empty (code=exited, status=0/SUCCESS)";
				break;
		}
	}
	$data = explode ( "\n" , $retour );
	for($i = 0; $i < count($data); $i++) {
		if (strpos($data[$i], 'Active') !== false) {
			//    Active: active (running) since Mon 2019-04-01 21:17:05 CEST; 14h ago
			$data2 = explode ( "since" , $data[$i] );
			if (strpos($data[$i], 'running') !== false) {
				 $status = "green";
			} elseif (strpos($data[$i], 'exited') !== false) {
				 $status = "orange";
			} else {
				$status = "red";
			}
			$data3 = explode ( ":" , $data2[0] );
			//	if($debug) echo "Status Text = ".$data3[1]."<br />";
			$data4 = explode ( ";" , $data[$i] );
			//	if($debug) echo "XXXXXX = ".$data4[1]."<br />";
			$data5 = explode ( "ago" , $data4[1] );
			//	if($debug) echo "XXXXXX = ".$data5[0]."<br />";
			
			$out["Testing services"][$servicename]["Status"] = $status;
			$out["Testing services"][$servicename]["Status text"] = trim($data3[1]);
			$out["Testing services"][$servicename]["since"] = trim($data5[0]);
			break;
		}
	}

}

// ----------------------------------------------------

// =========   Testing FTP connexion  ============
/*
$ftp = ftp_connect("ftp.online.net");
if ($ftp) {
	//	if($debug) echo "red";
	$out["Testing FTP connexion"]["Status"] = "red";
} else {
	//	if($debug) echo "green";
  ftp_close($ftp);
  $out["Testing FTP connexion"]["Status"] = "green";
}
*/
if(PHP_OS == 'Linux') {
	$retour = shell_exec("sudo /usr/lib/idefix/info3b.py");
} else {	// Pour map sous Windows
	$retour = '{"Testing FTP connexion": {"Status": "green"}}';
}
$z = json_decode($retour, true, 512);
// Injection dans le tableau $out[]
//if($debug) echo $z["Testing FTP connexion"]["Status"]."<br />";
$out["Testing FTP connexion"]["Status"] = $z["Testing FTP connexion"]["Status"];

// ----------------------------------------------------

// =========   Testing DNS filtering  ============

if(PHP_OS == 'Linux') {
	$retour = shell_exec("sudo /usr/lib/idefix/info3c.py");
} else {	// Pour map sous Windows
	$retour = '{"Testing DNS filtering": {"Web site": {"jeux.fr": {}}, "Status": "ERROR when contacting jeux.fr"}}';
}
$z = json_decode($retour, true, 512);

// Injection dans le tableau $out[]
if( isset($z["Testing DNS filtering"]["Status"]) ) {
	//if($debug) echo $z["Testing DNS filtering"]["Status"]."<br />";
	$out["Testing DNS filtering"]["Status"] = "error";
} else {
	//if($debug) echo $z["Testing DNS filtering"]["Web site"]["jeux.fr"]["locked"]."<br />";
	//if($debug) echo $z["Testing DNS filtering"]["Web site"]["jeux.fr"]["Status"]."<br />";
	$out["Testing DNS filtering"]["Web site"]["jeux.fr"]["locked"] = "green";
	$out["Testing DNS filtering"]["Web site"]["jeux.fr"]["Status"] = 403;
}

// ===============================================

echo json_encode($out);

if($debug) {
	echo "<pre>";
	var_dump($out);
	//print_r($out);
	echo "</pre>";
}

?>