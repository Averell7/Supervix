<?php
/* ------------------------------------------------------------------------------------------------- *\
	localization.php
\* ------------------------------------------------------------------------------------------------- */

error_reporting(E_ALL | E_STRICT);
include_once $_SERVER['DOCUMENT_ROOT']."/$/includes/init.php";

define('PROJECT_DIR', realpath('./'));
define('LOCALE_DIR', PROJECT_DIR .'/$/i18n/locale');
define('GETTEXT_DIR', PROJECT_DIR.'/$/i18n/lib/gettext');
require_once(GETTEXT_DIR.'/gettext.inc');

// On déclare ici la langue que l'on souhaite à afficher pour la page
// Via $_SESSION, $_COOKIE, $_GET, ...

//$gtLocale = 'fr_FR';
if(isset($_COOKIE["langue"]))
	$gtLocale = $_COOKIE["langue"];
else {
	if (file_exists(PATH_FILE_LANGUAGE)) {
		$fileopen = fopen(PATH_FILE_LANGUAGE,'r');
		$gtLocale = fgets($fileopen);
		fclose($fileopen);
	} else {
		$gtLocale = LANGUE_DEFAULT;
	}
}

$gtLocaleEnc = "$gtLocale.UTF-8";

putenv("LANGUAGE=" . $gtLocale); //First priority!
putenv("LANG=" . $gtLocale);
putenv("LC_ALL=" . $gtLocale);
putenv("LC_MESSAGES=" . $gtLocale);

T_setlocale(LC_ALL, $gtLocale);
T_setlocale(LC_MESSAGES, $gtLocale);

$gtDomain = "messages";
bindtextdomain($gtDomain, LOCALE_DIR);
if (function_exists('bind_textdomain_codeset')) 
	bind_textdomain_codeset($gtDomain, 'UTF-8');
textdomain($gtDomain);
?>
