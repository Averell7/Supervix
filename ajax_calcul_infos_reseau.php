<?php
/*----------------------------------------------------------*\
  ajax_calcul_infos_reseau.php
  Calcul les infos réseau déductibles depuis une adresse IP
  Appel en Ajax depuis /config-reaseau.php

  Source https://www.frameip.com/masque/
\*----------------------------------------------------------*/

session_start();
include_once "$/i18n/localization.php";

if (!isset($_POST['ipaddress'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
$calcul_adresse_ip	= $_POST['ipaddress'];
$calcul_mask		    = $_POST['lan_netmask_cidr'];

// **********************************************
// Récupération de l'IP cliente
// **********************************************
$ip_client=getenv("REMOTE_ADDR");
																																	//echo "IP cliente : ".$ip_client."<br />";
// **********************************************
// Récupération du Ptr de l'IP cliente
// **********************************************
$ptr=gethostbyaddr("$ip_client");
if ($ptr==$ip_client)
$ptr="Pas de ptr";

// **********************************************
// Récupération du port TCP source
// **********************************************
$port_source=getenv("REMOTE_PORT");
																																	//echo "port TCP source : ".$$port_source."<br />";
// **********************************************
// Récupération de l'IP du browser
// **********************************************
$ip_browser=getenv("HTTP_X_FORWARDED_FOR");
																																	//echo "IP du browser : ".$$ip_browser."<br />";
// ********************************************
// Validation du champs IP
// ********************************************
$calcul_inetaddr=ip2long($calcul_adresse_ip);
$calcul_adresse_ip=long2ip($calcul_inetaddr);

// ********************************************
// Vérification de la saisie
// ********************************************
$erreur=0; // Initialisation
if (($calcul_inetaddr==0)||($calcul_inetaddr==-1))
    masque_erreur(1);
if (($calcul_mask<1)||($calcul_mask>32))
    masque_erreur(2);

// ********************************************
// Conversion du masque
// ********************************************
// Optimisation fournit par Pascal de Serveurperso.com
$calcul_chaine_mask = (string) long2ip(256*256*256*256 - pow(2, 32 - $calcul_mask)); 

// ********************************************
// Calcul du nombre de HOST
// ********************************************
if ($calcul_mask==32)
    $calcul_host=1;
else
    $calcul_host=pow(2,32-$calcul_mask)-2;

// ********************************************
// Calcul de la route
// ********************************************
$calcul_route=$calcul_inetaddr&ip2long($calcul_chaine_mask); // Ajoute l'IP et le masque en binaire
$calcul_route=long2ip($calcul_route); // Convertit l'adresse inetaddr en IP

// ********************************************
// Calcul de la premiere adresse
// ********************************************
if ($calcul_mask==32)
    $offset=0;
else
    $offset=1;

if ($calcul_mask==31)
    $calcul_premiere_ip="N/A";
else
    {
    $calcul_premiere_ip=ip2long($calcul_route)+$offset;
    $calcul_premiere_ip=long2ip($calcul_premiere_ip);
    }

// ********************************************
// Calcul de la dernière adresse
// ********************************************
if ($calcul_mask==32)
    $offset=-1;
else
    $offset=0;

if ($calcul_mask==31)
    $calcul_derniere_ip="N/A";
else
    {
    $calcul_derniere_ip=ip2long($calcul_route)+$calcul_host+$offset;
    $calcul_derniere_ip=long2ip($calcul_derniere_ip);
    }

$limiter_plage_DHCP = true;
if($limiter_plage_DHCP) {
  // On limite si possible les adresses de début et de fin, de 200 à 253
  $ip_deb = explode(".", $calcul_premiere_ip);
  $ip_fin = explode(".", $calcul_derniere_ip);

  if( $ip_deb[3] < 200 && $ip_fin[3] == 254 ) {
      $ip_deb[3] = 200;
      $calcul_premiere_ip = implode ( "." , $ip_deb );
      $ip_fin[2] = $ip_deb[2];
      $ip_fin[3] = 253;
      $calcul_derniere_ip = implode ( "." , $ip_fin );
  }

}

// ********************************************
// Calcul du broadcast
// ********************************************
if ($calcul_mask==32)
    $offset=0;
else
    $offset=1;
$calcul_broadcast=ip2long($calcul_route)+$calcul_host+$offset;
$calcul_broadcast=long2ip($calcul_broadcast);


// ********************************************
// Retour des infos
// ********************************************

$infos = array();

// Les saisies

$infos['Adresse IP']            = $calcul_adresse_ip;
$infos['Supernet']              = $calcul_mask;

// Résultats

/*
$infos["Masque de sous réseau"] = $calcul_chaine_mask;
$infos["Nombre maximum d'hôte"] = $calcul_host;
$infos["L'adresse de réseau (La route)"] = $calcul_route;
$infos["Première adresse d'hôte"] = $calcul_premiere_ip;
$infos["Dernière adresse d'hôte"] = $calcul_derniere_ip;
$infos["Adresse de broadcast"] = $calcul_broadcast;
*/
$infos["systeme_lan_netmask"]		= $calcul_chaine_mask;
$infos["Nombre maximum d'hôte"]	= $calcul_host;
$infos["systeme_lan_subnet"]    = $calcul_route;
$infos["dhcp_begin"]            = $calcul_premiere_ip;
$infos["dhcp_end"]              = $calcul_derniere_ip;
$infos["dhcp_lan_broadcast"]    = $calcul_broadcast;

//$infos["hostname"] = gethostname();

echo json_encode($infos);


// ********************************************
// Fonction d'affichage de l'erreur de saisie
// ********************************************
function masque_erreur($erreur) // $erreur représente le numéro d'erreur.
    {
    // ********************************************
    // Affichage de titre d'erreur
    // ********************************************
    echo _("Erreur")."<br />"; 

    // ********************************************
    // Message personnalisé
    // ********************************************
    switch ($erreur) {
        case 1: echo _("Le calcul ne peux pas avoir lieu car le champ IP est vide ou non valide.");
								break;
        case 2: echo _("Le calcul ne peux pas avoir lieu car le champ MASK est vide ou non valide.");
								break;
        }
    }

?>
