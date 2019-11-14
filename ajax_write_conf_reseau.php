<?php session_start();

if (!isset($_POST['datasConfigNetwork'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";

$datasConfigNetwork = $_POST['datasConfigNetwork'];


write_ini_file($datasConfigNetwork, false);


function write_ini_file($datas, $has_sections=FALSE) {

	$idefix_conf = PATH_ETC_IDEFIX."idefix.conf";

	/* ----------------------------------------------------------------- *\
			Écriture du fichier $idefix_conf ( etc/idefix/idefix.conf )
	\* ----------------------------------------------------------------- */
		
	$content = ""; 
	if ($has_sections) {
			foreach ($datas as $key=>$elem) { 
					$content .= "[".$key."]\n"; 
					foreach ($elem as $key2=>$elem2) { 
							if(is_array($elem2)) 
							{ 
									for($i=0;$i<count($elem2);$i++) 
									{ 
											$content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
									} 
							} 
							else if($elem2=="") $content .= $key2." = \n"; 
							else $content .= $key2." = ".$elem2."\n"; 
					} 
			} 
	} 
	else {
			foreach ($datas as $key=>$elem) {
					if(is_array($elem)) 
					{ 
							for($i=0;$i<count($elem);$i++) 
							{ 																					
									$content .= $key."[] = \"".$elem[$i]."\"\n"; 
							} 
					} 
					else if($elem=="") $content .= $key." = \n"; 
					else $content .= $key." = ".$elem."\n"; 
			} 
	} 

	if (!$handle = fopen($idefix_conf, 'w')) { 
			return false; 
	}

	$success = fwrite($handle, $content);
	fclose($handle);
	if(!$success) {
		echo "Erreur d'écriture SP_06";
		return;
	}

	/* ----------------------------------------------------------------- *\
			Lecture du fichier $idefix_conf ( etc/idefix/idefix.conf )
			On lit ce fichier que l'on vient d'écrire
	\* ----------------------------------------------------------------- */

	$conf1 = file($idefix_conf);
	$conf = array();
	foreach($conf1 as $line) {
		$line1 = explode("=", $line);
		$lg = count($line1);
		$conf[trim($line1[0])] = trim($line1[1]);
		for($i=2; $i<$lg; $i++) {
			$conf[trim($line1[0])] .= "=".trim($line1[$i]);
		}
	}

	$keys = array("{lan_network}", "{lan_netmask}", "{lan_broadcast}", "{lan_ip}", "{dhcp_begin}", "{dhcp_end}", "{lan_subnet}");
	$values = array($conf["lan_network"], $conf["lan_netmask"], $conf["lan_broadcast"], $conf["lan_ip"], $conf["dhcp_begin"], $conf["dhcp_end"], $conf["lan_subnet"]);

	/* ----------------------------------------------------------------- *\
			DHCP
			----
			Création ou remplacement du fichier etc/dhcp/dhcpd.conf
	\* ----------------------------------------------------------------- */
		
	// Lecture du modèle 
	$handle1 = fopen(PATH_ETC . "idefix/dhcpd.model", "r"); 
	$data1 = fread($handle1, 10000);
	fclose($handle1);
	
	// Écriture
	$data2 = str_replace($keys, $values, $data1);
	$handle1 = fopen(PATH_ETC."dhcp/dhcpd.conf", "w"); 
	$success = fwrite($handle1, $data2);
	fclose($handle1);
	if(!$success) {
		echo "Erreur d'écriture SP_07";
		return;
	}
		
	/* ----------------------------------------------------------------- *\
			Réseau
			------
			Création ou remplacement du fichier etc/network/interfaces.d/eth1
	\* ----------------------------------------------------------------- */
		
	// Lecture du modèle 
	$handle1 = fopen(PATH_ETC . "idefix/eth1.model", "r"); 
	$data1 = fread($handle1, 10000);
	fclose($handle1);
	
	// Écriture
	$data2 = str_replace($keys, $values, $data1);
	$handle1 = fopen(PATH_ETC."network/interfaces.d/eth1", "w"); 
	$success = fwrite($handle1, $data2);
	fclose($handle1);
	if(!$success) {
		echo "Erreur d'écriture SP_08";
		return;
	}
		
	/* ----------------------------------------------------------------- *\
			bandwidthd
			------
			Création ou remplacement du fichier etc/bandwidthd/bandwidthd.conf 
	\* ----------------------------------------------------------------- */
	
	// Lecture du modèle 
	$handle1 = fopen(PATH_ETC_IDEFIX."bandwidthd.model", "r"); 
	$data1 = fread($handle1, 10000);
	fclose($handle1);
	
	// Écriture
	$data2 = str_replace($keys, $values, $data1);
	$handle1 = fopen(PATH_ETC."bandwidthd/bandwidthd.conf", "w"); 
	$success = fwrite($handle1, $data2);
	fclose($handle1);
	if(!$success) {
		echo "Erreur d'écriture SP_20";
		return;
	}
	/* ----------------------------------------------------------------- *\
			Unbound
			----
			Création ou remplacement du fichier etc/idefix/unbound-idefix.conf
	\* ----------------------------------------------------------------- */
		
	// Lecture du modèle 
	$handle1 = fopen(PATH_ETC . "idefix/unbound-network.model", "r"); 
	$data1 = fread($handle1, 10000);
	fclose($handle1);
	
	// Écriture
	$data2 = str_replace($keys, $values, $data1);
	$handle1 = fopen(PATH_ETC."idefix/unbound-network.conf", "w"); 
	$success = fwrite($handle1, $data2);
	fclose($handle1);
	if(!$success) {
		echo "Erreur d'écriture SP_21";
		return;
	}

	/* ----------------------------------------------------------------- *\
			Apache2
			----
			Création ou remplacement du fichier etc/apache2/sites-available/
	\* ----------------------------------------------------------------- */
		
	// Lecture du modèle 
	$handle1 = fopen(PATH_ETC . "idefix/virtual-host.model", "r"); 
	$data1 = fread($handle1, 10000);
	fclose($handle1);
	
	// Écriture
	$data2 = str_replace($keys, $values, $data1);
	$handle1 = fopen(PATH_ETC."apache2/sites-available/000-default.conf", "w"); 
	$success = fwrite($handle1, $data2);
	fclose($handle1);
	if(!$success) {
		echo "Erreur d'écriture SP_21 (apache2)";
		return;
	}

	/* ----------------------------------------------------------------- *\
	\* ----------------------------------------------------------------- */

	echo _("Enregistrement effectué.");
}
?>
