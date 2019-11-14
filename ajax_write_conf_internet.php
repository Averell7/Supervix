<?php session_start();

if (!isset($_POST["datasConfigNetwork"])) { // Pour bloquer l'accès direct à ce script
	header("Location: index.php");
	exit;
}
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";

$datasConfigNetwork = $_POST["datasConfigNetwork"];
//echo "<pre>";
//echo print_r($datasConfigNetwork);
//echo "</pre>";


if($datasConfigNetwork["effacement"] == 'true') {
	// Effacement complet du contenu de /home/rock64/idefix
	// suite à la demande de modification des paramètres FTP
	//echo "___ effacement ___".$datasConfigNetwork["effacement"]."<br />\n";
	if(PHP_OS == 'Linux') {
		$commande = 'sudo rm '.PATH_HOME_ROCK64_IDEFIX.'*';
		shell_exec($commande);
	}
	else {					// 'WINNT'
		$files = glob(PATH_HOME_ROCK64_IDEFIX.'*'); // get all file names
		foreach($files as $file){ // iterate files
			if(is_file($file))
				unlink($file); // delete file
		}
	}
}


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
				if($key != 'effacement') {
					if(is_array($elem)) 
					{ 
							for($i=0;$i<count($elem);$i++) 
							{ 
									$content .= $key."[] = \"".$elem[$i]."\"\n";
							} 
					} 
					else if($elem=="") $content .= $key." = \n"; 
					else {
						if($key == "password" || $key == "ddclient_password") $elem = chiffrer($elem);
						$content .= $key." = ".$elem."\n";
					}
				}
			} 
	} 

	if (!$handle = fopen($idefix_conf, 'w')) { 
			return false; 
	}

	$success = fwrite($handle, $content);
	fclose($handle); 
	if(!$success) {
		echo "Erreur d'écriture SP_09";
		return;
	}

	/* ----------------------------------------------------------------- *\
			Lecture du fichier $idefix_conf ( etc/idefix/idefix.conf )
			On lit ce fichier que l'on vient d'écrire
			Seul le premier '"' détermine la 'clé'
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

	if($conf["dns_filtering"] == "auto") {
		$conf1 = file("/home/rock64/idefix/idefix_auto.conf");
		$conf2 = array();
		foreach($conf1 as $line) {
			$line1 = explode("=", $line, 2);
			$conf2[trim($line1[0])] = trim($line1[1]);
		$conf["dns_nameserver1"] = $conf2["dns_nameserver1"];
		$conf["dns_nameserver2"] = $conf2["dns_nameserver2"];
		$conf["protocol"] = $conf2["protocol"];
		}
	}

	/* ----------------------------------------------------------------- *\
			ddclient
			--------
			Création ou remplacement du fichier etc/ddclient.conf
	\* ----------------------------------------------------------------- */

	if(PHP_OS == 'Linux') {

		$retour = shell_exec("sudo /usr/lib/idefix/write_ddclient.py 2>&1");
		$retour = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br />",$retour);
		//$retour = "Test";
		echo "<p class='vert'>".$retour."</p>";
	} else {
		echo "<p class='rouge'>"._("L'enregistrement ne peut pas être effectué car vous n'êtes pas sous Linux.")."</p>";
	}

	$keys = array("{lan_network}", "{lan_netmask}", "{lan_broadcast}", "{lan_ip}", "{dhcp_begin}", "{dhcp_end}", "{lan_subnet}", "{dns_nameserver1}");
	$values = array($conf["lan_network"], $conf["lan_netmask"], $conf["lan_broadcast"], $conf["lan_ip"], $conf["dhcp_begin"], $conf["dhcp_end"], $conf["lan_subnet"], $conf["dns_nameserver1"]);

	/* ----------------------------------------------------------------- *\
			Unbound
			----
			Création ou remplacement du fichier etc/idefix/unbound-forward.conf
	\* ----------------------------------------------------------------- */
		
	// Lecture du modèle 
	$handle1 = fopen(PATH_ETC . "idefix/unbound-forward.model", "r"); 
	$data1 = fread($handle1, 10000);
	fclose($handle1);
	
	// Écriture
	$data2 = str_replace($keys, $values, $data1);
	$handle1 = fopen(PATH_ETC."idefix/unbound-forward.conf", "w"); 
	$success = fwrite($handle1, $data2);
	fclose($handle1);
	if(!$success) {
		echo "Erreur d'écriture SP_24";
		return;
	}
}

/* ----------------------------------------------------------------- *\
    Obtenir des caractères aléatoires
\* ----------------------------------------------------------------- */

function genererChaineAleatoire($longueur = 10) {
  //$caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ|][@";
  $caracteres = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ|][@!#$&*+-?";
  $longueurMax = strlen($caracteres);
  $chaineAleatoire = '';
  for ($i = 0; $i < $longueur; $i++) {
    $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
  }
  return $chaineAleatoire;
}

/* ----------------------------------------------------------------- *\
    Chiffrage d'un mot de passe
\* ----------------------------------------------------------------- */

function chiffrer($chaine) {
  $lg = strlen( $chaine );
  $chaineAleatoire = genererChaineAleatoire($lg);

  $mdpchiffre = '%';
  for ($i=0; $i<$lg; $i++) {
    $c = substr ($chaineAleatoire , $i , 1 );
    $mdpchiffre .= substr ($chaine , $i , 1 ).$c;
  }
  return $mdpchiffre;
}

/* ----------------------------------------------------------------- *\
    Déchiffrage d'un mot de passe chiffré
		
		substr ($chaine , 0 , 1) != '%') :
		Mot de passe considéré comme non chiffré
\* ----------------------------------------------------------------- */

function dechiffrer($chaine) {
  $chaine = trim($chaine);
	$lg = strlen($chaine);
  if($lg == 0  || substr ($chaine , 0 , 1) != '%') return $chaine;
  $mdp = '';
  for ($i=1; $i<=$lg; $i += 2) {
    $mdp .= substr ($chaine , $i , 1);
  }
  return $mdp;
}

?>
