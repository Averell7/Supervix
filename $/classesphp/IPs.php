<?php
/** @defgroup ClassesPHP Idéfix
    On trouve ici les classes pour créer et paramétrer
@{ */

/*!
 * @file      getIPs.php
 * @brief     Classe pour obtenir les différentes adresses IP :\n
 *						du client\n
 *						du serveur Idéfix\n
 *
 * @author     Idéfix - SP
 * @version    1.0
 * @date       2019-03-01
 *
 * @warning		
 *
 * @note			dossiers\n
 *						[Source inspirante]:\n
 *						http://www.zone-webmasters.net/codes-sources/php/56-fonction-qui-affiche-l-ip-du-visiteur.html\n
 *						https://www.ipify.org/ : incontournable !
 *
 ***********************************************************************************/

class IPs
	{
	/*-------------------------------------------------------------- Propriétés */

	/*---------------------------------------------------------- FIN Propriétés */

	/*------------------------------------------------------------ Constructeur */
  /*! @fn              public function __construct()
   *  @brief                Constructeur de la classe
   *
   *  @param
   */
 	public function __construct() {
	}
	/*-------------------------------------------------------- FIN Constructeur */

	/*------------------------------------------------------------- Destructeur */
	/*! @fn    Public function __destruct()
	 *  @brief Destructeur de la classe\n
	 */
	public function __destruct() {
	}
	/*--------------------------------------------------------- FIN Destructeur */
		
	/*------------------------------------------------------------------ getIPs */
	/*! @fn     public function getIPs()
	 *  @brief  Retourne les différentes IPs
	 *  @note		\n
   */
	public function getIPs() {
				
			$IPs = array();
			$IPs['name_idefix'] = getHostName();
		
			if(PHP_OS == 'WINNT') {
				
				// IP de la machine consultante : client
				$IPs['IPv4_client'] = getHostByName(getHostName());
				
			} else if(PHP_OS == 'Linux') {
				
				// nom de l'Idéfix
				$IPs['name_idefix'] = getHostName();

				// --- eth0
				// IP d'Idéfix sur le réseau local connecté directement sur le modem
				$command = "ip addr show eth0 | grep 'inet\b' | awk '{print $2}' | cut -d/ -f1";
				$IPv4 = exec($command);
				$IPs['eth0_IPv4_idefix'] = $IPv4;
				// broadcast : IP d'Idéfix sur le réseau local connecté directement sur le modem
				$command = "ip addr show eth0 | grep 'inet\b' | awk '{print $4}' | cut -d/ -f1";
				$broadcast = exec($command);
				$IPs['eth0_IPv4_idefix_broadcast'] = $broadcast;
				// IP d'Idéfix sur le réseau local connecté directement sur le modem
				$command = "ip addr show eth0 | grep 'inet6\b' | awk '{print $2}' | cut -d/ -f1";
				$IPv6 = exec($command);
				$IPs['eth0_IPv6_idefix'] = $IPv6;
				
				// --- eth1
				// IP d'Idéfix sur le réseau local connecté directement sur le modem
				$command = "ip addr show eth1 | grep 'inet\b' | awk '{print $2}' | cut -d/ -f1";
				$IPv4 = exec($command);
				$IPs['eth1_IPv4_idefix'] = $IPv4;
				// broadcast : IP d'Idéfix sur le réseau local connecté directement sur le modem
				$command = "ip addr show eth1 | grep 'inet\b' | awk '{print $4}' | cut -d/ -f1";
				$broadcast = exec($command);
				$IPs['eth1_IPv4_idefix_broadcast'] = $broadcast;
				// IP d'Idéfix sur le réseau local connecté directement sur le modem
				$command = "ip addr show eth1 | grep 'inet6\b' | awk '{print $2}' | cut -d/ -f1";
				$IPv6 = exec($command);
				$IPs['eth1_IPv6_idefix'] = $IPv6;
				
				// IP de la machine consultante : client
				$IPs['IPv4_client'] = $this->chkip();
				
				// IP publique du modem accordée par le FAI
				$IPs['IPv4_publique'] = file_get_contents('http://api.ipify.org');
		
			}
			return $IPs;
	}
	
	/*-------------------------------------------------------------- FIN getIPs */
	
	/*------------------------------------------------------------------- chkip */
	/*! @fn    private function chkip()
	 *  @brief 
	 *	
	 *	@source		http://www.zone-webmasters.net/codes-sources/php/56-fonction-qui-affiche-l-ip-du-visiteur.html\n
	 */
	private function chkip() {
			$ip = "";
			$proxy = "";
			if (isset($_SERVER)) {
				if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
					$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
					$proxy = $_SERVER["REMOTE_ADDR"];
				} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
					$ip = $_SERVER["HTTP_CLIENT_IP"];
				} else {
					$ip = $_SERVER["REMOTE_ADDR"];
				}
			} else {
				if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
					$ip = getenv( 'HTTP_X_FORWARDED_FOR');
					$proxy = $_SERVER["REMOTE_ADDR"];
				} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
					$ip = getenv( 'HTTP_CLIENT_IP' );
				} else {
					$ip = getenv( 'REMOTE_ADDR' );
				}
			}
			if (strstr($ip, ',')) {
				$ips = explode(',', $ip);
				$ip = $ips[0];
			}
			/*
			if ($proxy != '') {
				$ip = $ip . '(Proxy: '.$proxy.')';
			}*/
			return $ip;
	}
	
	/*--------------------------------------------------------------- FIN chkip */
	
	}// Fin de la classe
	
/** @} */
?>
