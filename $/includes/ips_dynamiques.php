<?php
/* Bloc Filtrage DNS */

/* Les champs des serveurs DNS n'apparaissent pas - Mais on n'enregistre PAS les noms des serveurs DNS */
$filtreDNS['automatique']['ddclient_server']	= "";
$filtreDNS['automatique']['ddclient_web']     = "";
$filtreDNS['automatique']['optionValue']      = "auto";
$filtreDNS['automatique']['libelle']          = _("Automatique");

/* Les champs des serveurs DNS n'apparaissent pas - Mais on enregistre les noms des serveurs DNS appropriés */
$filtreDNS['SafeDNS']['ddclient_server']  = "www.safedns.com";
$filtreDNS['SafeDNS']['ddclient_web']     = "http://www.safedns.com/nic/myip";
$filtreDNS['SafeDNS']['optionValue']      = "SafeDNS";
$filtreDNS['SafeDNS']['libelle']          = "SafeDNS";

/* Les champs des serveurs DNS n'apparaissent pas - Mais on enregistre les noms des serveurs DNS appropriés */
$filtreDNS['OpenDNS']['ddclient_server']  = "updates.opendns.com";
$filtreDNS['OpenDNS']['ddclient_web']     = "myip.dnsomatic.com";
$filtreDNS['OpenDNS']['optionValue']      = "OpenDNS";
$filtreDNS['OpenDNS']['libelle']          = "OpenDNS";

/* Les champs des serveurs DNS apparaissent - Les champs sont éditables - On enregistre les noms des serveurs DNS */
$filtreDNS['autre']['ddclient_server']  	= "";
$filtreDNS['autre']['ddclient_web']     	= "";
$filtreDNS['autre']['optionValue']      	= "other";
$filtreDNS['autre']['libelle']          	= _("Autre");

/* Les champs des serveurs DNS n'apparaissent pas */
$filtreDNS['aucun']['ddclient_server']  	= "";
$filtreDNS['aucun']['ddclient_web']     	= "";
$filtreDNS['aucun']['optionValue']      	= "None";
$filtreDNS['aucun']['libelle']          	= _("Aucun");

/* Bloc IP dynamique */

$choixDNS['automatique']['ddclient_server']  = "";
$choixDNS['automatique']['ddclient_web']     = "";
$choixDNS['automatique']['optionValue']      = "auto";
$choixDNS['automatique']['libelle']          = _("Automatique");

$choixDNS['noip']['ddclient_server']    		= "dynupdate.no-ip.com";
$choixDNS['noip']['ddclient_web']       		= "checkip.dyndns.com/, web-skip='IP Address'";
$choixDNS['noip']['optionValue']        		= "noip";
$choixDNS['noip']['libelle']            		= "Noip";

$choixDNS['SafeDNS']['ddclient_server']  		= "www.safedns.com";
$choixDNS['SafeDNS']['ddclient_web']    		= "http://www.safedns.com/nic/myip";
$choixDNS['SafeDNS']['optionValue']     		= "SafeDNS";
$choixDNS['SafeDNS']['libelle']          		= "SafeDNS";

$choixDNS['OpenDNS']['ddclient_server']  		= "updates.opendns.com";
$choixDNS['OpenDNS']['ddclient_web']     		= "myip.dnsomatic.com";
$choixDNS['OpenDNS']['optionValue']      		= "OpenDNS";
$choixDNS['OpenDNS']['libelle']          		= "OpenDNS";

$choixDNS['aucun']['ddclient_server']  		= "";
$choixDNS['aucun']['ddclient_web']     		= "";
$choixDNS['aucun']['optionValue']      		= "None";
$choixDNS['aucun']['libelle']          		= _("Aucun");
/* Implémentation future ?
$choixDNS['DynDNS']['ddclient_server']  = "";
$choixDNS['DynDNS']['ddclient_web']     = "";
$choixDNS['DynDNS']['optionValue']      = "DynDNS";
$choixDNS['DynDNS']['libelle']          = "DynDNS";
*/
//echo json_encode($choixDNS);
?>
