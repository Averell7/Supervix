<?php
session_start();
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";

/* Code erreur envoyé par le fichier .htaccess */
$errorCodeHttp = $_GET['err'];

$msg  = "<div id='erreur'>\n";
//$msg .= "<p class='gras center'>Page inaccessible</p>";
$msg .= "<p style='color:red;background-color:white;padding: 1px 2px;'>http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."</p>";

// http://www.codeshttp.com/
switch ($errorCodeHttp) {
	case '403' :	$msgcourt = "<span style='font-size:1em;color:yellow;'>Interdit</span>";
								$msg .= "<p><span style='font-size:1em;font-weight:bold;'>"._("Le serveur HTTP a compris la requête, mais refuse de la traiter.")."</span><br />";
								$msg .= _("Ce code est généralement utilisé lorsqu'un serveur ne souhaite pas indiquer pourquoi la requête a été rejetée, ou lorsque aucune autre réponse ne correspond (par exemple le serveur est un Intranet et seules les machines du réseau local sont autorisées à se connecter au serveur).")."</p>";
								break;
	
	case '404' :	$msgcourt = "<span style='font-size:1em;font-weight:bold;color:yellow;'>"._("Non trouvé")."</span>";
								$msg .= "<p><span style='font-size:1em;font-weight:bold;'>"._("Le serveur n'a rien trouvé qui corresponde à l'adresse (URI) demandée ( non trouvé ).")."</span><br />";
								$msg .= _("Cela signifie que l'URL que vous avez tapée ou cliquée est mauvaise ou obsolète et ne correspond à aucun document existant sur le serveur (vous pouvez essayez de supprimer progressivement les composants de l'URL en partant de la fin pour éventuellement retrouver un chemin d'accès existant).")."</p>";
								break;
	default		 :	$msgcourt = "";
								$msg .= "";
								break;
}



if (isset($_SESSION['referer'])) {
  $msg .= "<p>"._("Retour à la")." <a href='".$_SESSION['referer']."'>"._("page précédente")."</a></p>";
}
$msg .= "<p>"._("Retour à la")." <a href='index.php'>"._("page d'accueil")."</a></p>";
$msg .= "</div>";
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Page inaccessible"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Page inaccessible">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

  <script src=<?php echo "$/js/jquery-3.3.1.min.js"; ?>></script>
	<script src=<?php echo "$/js/jquery-ui.min.js"; ?>></script>
	<script src=<?php echo "$/js/js.cookie.js"; ?>></script>
	<script src=<?php echo "$/js/script_accordion.js"; ?>></script>
	<script src=<?php echo "$/js/script_ready_nav1.js"; ?>></script>

	<link rel="stylesheet" type="text/css" href=<?php echo "$/css/commun.css"; ?> media="screen" />
	<link rel="stylesheet" type="text/css" href=<?php echo "$/css/jquery-ui.css"; ?> media="screen" />
	<link rel="stylesheet" type="text/css" href=<?php echo "$/css/menu.css"; ?> media="screen" />
	<link rel="stylesheet" type="text/css" href=<?php echo "$/css/screen1.css"; ?> media="screen" />
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
				<span class="titre"><?php echo _("Page inaccessible"); ?></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

		<div class="colonne_1">
			<fieldset style="width:60%;text-align:left;margin:auto;">
				<legend><?php echo _("Erreur"); ?> <?php echo $errorCodeHttp; ?> : <?php echo $msgcourt; ?> </legend>
					<?php echo $msg; ?>
			</fieldset>
		</div>
		
    <?php //---------------------------------------------------- footer ?>
             
    <div class="footer">
      <?php include "$/includes/version.php"; ?>
    </div>

    <?php //----------------------------------------------------------- ?>

</div>
</body>
</html>



