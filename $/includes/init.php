<?php

/* ------------------------------------------------------------------------- *\
		Paramètres
\* ------------------------------------------------------------------------- */

if (!defined('MAISON'))
	define('MAISON', gethostname());

if (!defined('TPSMAXSESSION'))
	define('TPSMAXSESSION', 600);	/* Durée de vie en secondes de la session */

/* ------------------------------------------------------------------------- *\
		Localisation du fichier idefix.conf
\* ------------------------------------------------------------------------- */

if (!defined('PATH_IDEFIX_CONF')) {
	if(PHP_OS == 'Linux')
		define('PATH_IDEFIX_CONF', "/etc/idefix/idefix.conf");
	else if(PHP_OS == 'WINNT')
    define('PATH_IDEFIX_CONF', "etc/idefix/idefix.conf");
}

/* ------------------------------------------------------------------------- *\
		Localisation du dossier home/rock64/idefix/ : fichiers INI et CONF
\* ------------------------------------------------------------------------- */

if (!defined('PATH_ETC')) {
	if(PHP_OS == 'Linux')
		define('PATH_ETC', "/etc/");
	else if(PHP_OS == 'WINNT')
    define('PATH_ETC', "etc/");
}

if (!defined('PATH_ETC_IDEFIX')) {
	if(PHP_OS == 'Linux')
		define('PATH_ETC_IDEFIX', "/etc/idefix/");
	else if(PHP_OS == 'WINNT')
    define('PATH_ETC_IDEFIX', "etc/idefix/");
}

if (!defined('PATH_ETC_SQUID')) {
	if(PHP_OS == 'Linux')
		define('PATH_ETC_SQUID', "/etc/squid/");
	else if(PHP_OS == 'WINNT')
    define('PATH_ETC_SQUID', "etc/squid/");
}

if (!defined('PATH_USR_LIB_IDEFIX')) {
	if(PHP_OS == 'Linux')
		define('PATH_USR_LIB_IDEFIX', "/usr/lib/idefix/");
	else if(PHP_OS == 'WINNT')
    define('PATH_USR_LIB_IDEFIX', "usr/lib/idefix/");
}

if (!defined('PATH_HOME_ROCK64_IDEFIX')) {
	if(PHP_OS == 'Linux')
		define('PATH_HOME_ROCK64_IDEFIX', "/home/rock64/idefix/");
	else if(PHP_OS == 'WINNT')
    define('PATH_HOME_ROCK64_IDEFIX', "home/rock64/idefix/");
}

/* ------------------------------------------------------------------------- *\
		Restauration de backups
\* ------------------------------------------------------------------------- */

if (!defined('BACKUP_PLACE'))
	define('BACKUP_PLACE', "locales");		// "locales" | "ftp"

if (!defined('NB_BACKUP_DESIRES'))
	define('NB_BACKUP_DESIRES', 5);		// Nb de backups souhaités à analyser

/* ------------------------------------------------------------------------- *\
		Dossier temporaire de téléchargement des backups ZIP
\* ------------------------------------------------------------------------- */

if (!defined('DIR_TEMP')) {
	if(PHP_OS == 'Linux')
		define('DIR_TEMP', "/tmp/");
	else if(PHP_OS == 'WINNT')
    define('DIR_TEMP', "tmp/");
}

/* ------------------------------------------------------------------------- *\
		Dossier contenant les backups automatiques sur Idéfix
\* ------------------------------------------------------------------------- */

if (!defined('DIR_BACKUP_LOCAL')) {
	if(PHP_OS == 'Linux')
		define('DIR_BACKUP_LOCAL', "/var/spool/idefix/backup/");
	else if(PHP_OS == 'WINNT')
    define('DIR_BACKUP_LOCAL', "var/spool/idefix/backup/");
}

/* ------------------------------------------------------------------------- *\
		Localisation des rapports Bandwidthd
\* ------------------------------------------------------------------------- */

if (!defined('PATH_RACINE_BANDWIDTHD')) {
	if(PHP_OS == 'Linux')
		define('PATH_RACINE_BANDWIDTHD', "/bandwidthd/");
	else if(PHP_OS == 'WINNT')
    define('PATH_RACINE_BANDWIDTHD', "bandwidthd/");
}

/* ------------------------------------------------------------------------- *\
		Localisation des rapports Munin
\* ------------------------------------------------------------------------- */

if (!defined('PATH_MUNIN')) {
	if(PHP_OS == 'Linux')
		define('PATH_MUNIN', "http://192.168.1.184:10443/munin/localdomain/localhost.localdomain/");
	else if(PHP_OS == 'WINNT')
    define('PATH_MUNIN', "-----");
}

/* ------------------------------------------------------------------------- *\
		Affichage de l'état du système
\* ------------------------------------------------------------------------- */

$rafraichissement_etat_systeme = "oui";	// $raff_time | non
$raff_time = 3;/* Délai du Rafraîchissement de l'affichage en secondes */

/* ------------------------------------------------------------------------- *\
		Retours de ligne
\* ------------------------------------------------------------------------- */

if (!defined('CR'))
	define('CR', "\r");          // Carriage Return: Mac
if (!defined('LF'))
	define('LF', "\n");          // Line Feed: Unix
if (!defined('CRLF'))
	define('CRLF', "\r\n");      // Carriage Return and Line Feed: Windows

/* ------------------------------------------------------------------------- *\
		Internationalisation
\* ------------------------------------------------------------------------- */

// Chemin des locales

define('PATH_RACINE_LOCALE', "$/i18n/locale");
define('LANGUE_DEFAULT', "fr_FR");

// Déclaration des libellés de langue
// Si omis, la langue n'apparaîtra pas dans le select
// Note : on ne peut mettre ce tableau en define que sous PHP 7.
// http://www.lingoes.net/en/translator/langcode.htm

$langues['de_DE'] = "Deutsch";
$langues['en_US'] = "English";
$langues['es_ES'] = "Español";
$langues['fr_FR'] = "Français";
$langues['it_IT'] = "Italiano";
$langues['ko_KR']	= "한국어";
$langues['pt_PT'] = "Português";
$langues['sl_SI'] = "Slovenščina";
$langues['la_LA'] = "Latin";

// Sauvegarde aussi de la langue dans un fichier

if (!defined('PATH_FILE_LANGUAGE'))
  define('PATH_FILE_LANGUAGE', "$/settings/langue.lg");

/* ------------------------------------------------------------------------- *\
		Cacher ou non les inputs ddclient_server et ddclient_web
		dans la page config-internet.php
		true : affiche | false : n'affiche pas
\* ------------------------------------------------------------------------- */

$ddclient_server_web = false;
if($ddclient_server_web) {
	$style = " style='display:block;'";
} else {
	$style = " style='display:none;'";
}

/* ------------------------------------------------------------------------- *\
		Pré-saisie du mot de passe sur la page de connexion :
		index.php - Bienvenue sur Idéfix
\* ------------------------------------------------------------------------- */

$pre_saisie_mdp = false;

if($pre_saisie_mdp) {
	$password = '';
} else {
	$password = '';
}

/* ========================================================================= *\

\* ========================================================================= */

//PATH_HOME_ROCK64_IDEFIX."tmp/"

				/*
				$temp = substr(DIR_TEMP, 0, -1);	// 'temp'
				echo $temp.'<br />';
				$temp = substr(DIR_TEMP, 0, strlen(DIR_TEMP) -1);
				echo $temp.'<br />';
				exit;
				*/
?>
