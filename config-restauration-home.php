<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";

$dossiers_a_comparer = [
	PATH_HOME_ROCK64_IDEFIX
];
/*
$dossiers_a_comparer = [
	PATH_ETC,
	PATH_ETC_IDEFIX,
	PATH_ETC_SQUID,
	PATH_USR_LIB_IDEFIX
];
*/
// Exclusions de l'analyse
$fichiers_exclus = array(".", "..", "_idefix.conf");
$extensions_exclues = array( 'old', 'log', 'zip', 'txt', "1", "2", "3", "4", "5", "6");

//include_once "$/includes/fonctions11c.php";

require_once($_SERVER['DOCUMENT_ROOT']. "/$/classesphp/magiques.php");



/* --------------------------------------------------------- *\
	Ce script est conçu pour :
	
	- Récupérer l'identifiant et le mot de passe contenu dans :
		/etc/idefix/idefix.conf
	- accède au compte FTP
	- création automatique (s'il n'existe pas) du dossier temporaire 'var/tmp/' défini dans init.php
	- vide éventuelement tout le contenu présent dans ce dossier 'var/tmp/'
	- télécharge tous les backups journaliers dans ce fichier temporaire 'var/tmp/'
	- dézippe toutes les archives dans des dossiers portant leur nom
	- compare tous les fichiers contenus dans les dossiers :
					/home/rock64/idefix/*
					
					/etc/idefix/*
					/etc/squid/*
					/usr/lib/idefix/*
			avec ceux des archives
					var/tmp/idefix-backup-2019-01-20/home/rock64/idefix/*
					
					var/tmp/idefix-backup-2019-01-20/etc/idefix/*
					var/tmp/idefix-backup-2019-01-20/etc/squid/*
					var/tmp/idefix-backup-2019-01-20/usr/lib/idefix/*
		
	- Pour chaque sauvegarde, ne sont affichés que les fichiers qui ne sont pas identiques
	- et pour ces fichiers, on n'affiche que les lignes qui sont différentes
	
	- des fichiers peuvent être exclus dans le comparatif.
	- des extentions peuvent être exclues dans le comparatif.
\* --------------------------------------------------------- */

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo _("Restauration de configuration idéfix"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Accueil Idefix">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
		<?php 
      include_once "$/includes/alert.php";
		?>
  </script>
  <script src="$/js/jquery-3.3.1.min.js"></script>
	<script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<!-- <script src="$/js/script_ready_nav11.js"></script> -->
	
		<!-- à clarifier... : doublons ? -->
		
	  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" media="screen" />

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen11.css" media="screen" />
</head>

<body>
<div class="container">

		<?php //---------------------------------------------------- header ?>
		
		<div class="menu0" title="<?php echo _("Page d'accueil"); ?>">
			<span class="titre0">Idéfix</span><br />
			<span class="sous_titre0"><?php echo MAISON; ?></span>
		</div>
	 <div class="header0"></div>

		<div class="header">
			<p>
				<span class="titre"><?php echo _("Restauration de configuration"); ?></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 2 ?>

		<div class="colonne_2">	<!-- <div class="colonne_2" style="text-align:left;"> provisoirement -->
		<?php
				$backups_place		= BACKUP_PLACE;					// "locales" | "ftp"
				$nbBackupDesires	= NB_BACKUP_DESIRES;		// Nb de backups souhaités à analyser (défini dans init.php)
				
				//if( $backups_place == "locales" ) {	// Stockage /home/rock64/idefix/tmp/
					
						$d = new analyseBackups($dossiers_a_comparer, $fichiers_exclus,	$extensions_exclues, $backups_place);
						echo $d->afficheComparatif($nbBackupDesires);
						$b = $d->nb_onglets;
						$listeDates = $d->listeDates;
						//$b = 2;
						//echo "B - nb_onglets = ".$b."<br />";
						//echo $listeDates."<br />";
						//exit;
						$listeSauvegardes = "<p>Sauvegardes<br /><span style='color:yellow;'>".$backups_place."</span></p>".$listeDates;
/*
				}
				else {		// $backups_place = "ftp";
				
						$d = new analyseBackups($dossiers_a_comparer, $fichiers_exclus,	$extensions_exclues, "ftp");
						echo $d->afficheComparatif();
						$b = $d->nb_onglets;
						$listeDates = $d->listeDates;
						$listeSauvegardes = "<p>Sauvegardes<br /><span style='color:yellow;'>locales</span></p>".$listeDates;


						//$listeDates = "";
						$compteFTP = getCompteFTP();
						if( $compteFTP[0] ) {		// Sinon : Pas de compte FTP
							viderDossierTmp();
							downloadZIP($compteFTP);
							$dossiers_DEZIP = deZipper();
							afficheSourceBackups($compteFTP['ftp']);
							$listeDates = exeAnalyse($dossiers_DEZIP, $b, $_SESSION['referer']);
							
							$listeSauvegardes = "<p>Sauvegardes<br /><span style='color:yellow;'>FTP</span></p>".$listeDates;
						}

				}*/
				
				echo "<script>\n";
				echo "var b = ".$b.";\n";
				echo "var dossiers_a_comparer = ".json_encode($dossiers_a_comparer)."\n";
				echo "var fichiers_exclus = ".json_encode($fichiers_exclus)."\n";
				echo "var extensions_exclues = ".json_encode($extensions_exclues)."\n";
				echo "</script>\n";
		?>
		</div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

		<div class="colonne_1">
			<!-- <p>Sauvegardes</p> -->
			<?php echo $listeSauvegardes; ?>
		</div>
		
    <?php //---------------------------------------------------- footer ?>
             
    <div class="footer">
      <?php include "$/includes/version.php"; ?>
    </div>
    
    <div class="notes">
     <?php include "$/includes/deconnexion.php"; ?>
    </div>

    <?php //----------------------------------------------------------- ?>

</div>
</body>
	<script src="$/js/script_ready_nav11.js"></script>

</html>
