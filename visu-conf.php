<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";

$fichiersCONF = [
  PATH_ETC.'dhcp/dhcpd.conf',
  PATH_ETC.'network/interfaces.d/eth1',
  PATH_ETC.'idefix/nftables_init.conf',
  PATH_ETC.'idefix/idefix.conf',
  PATH_ETC.'idefix/unbound-network.conf',
  PATH_ETC.'idefix/unbound-forward.conf',
  PATH_ETC.'ddclient.conf',
];

$table  = "<table><tbody><tr>";
$k = 0;
foreach($fichiersCONF as $filename) {
	//$filename = "../../".$filename;
	$table .= "<td><h3>".$fichiersCONF[$k]."</h3><div><pre>";
	$k++;
	if(file_exists($filename)) {
		$contenuFichier = file_get_contents ( $filename );
		$contenuFichier = str_replace(CRLF, LF, $contenuFichier);
		$contenuFichier = str_replace(CR, LF, $contenuFichier);
		$contenuFichier = str_replace(LF, "<br />", $contenuFichier);
		$table .= $contenuFichier;
	} else {
		$table .= _("Ce fichier n'a pas été trouvé.");
	}
	$table .= "</pre></div></td>";
	if($k % 2 == 0) $table .= "</tr><tr>";
}
$table .= "</tr></tbody></table>";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Visualisation des fichiers"); ?> *.conf</title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Visualisation des fichiers *.conf">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

  <script src="$/js/jquery-3.3.1.min.js"></script>
  <script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
  <script src="$/js/script_ready_nav9.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen9.css" media="screen" />
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
        <span class="titre"><?php echo _("Visualisation des fichiers"); ?> *.conf</span><br />
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">

			<fieldset>
         
            <?php echo $table; ?>
          
			</fieldset>
							
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
</html>



