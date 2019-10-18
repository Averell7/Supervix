<?php
function __autoload($Classe_a_charger)
  {
  switch ($Classe_a_charger)
    {
    case "elimineZipContenusIdentiques" :
    		require_once ("elimineZipContenusIdentiques.php");
    		break;
				
    case "analyseBackups" :
    		require_once ("analyseBackups.php");
    		break;
				
    case "IPs" :
    		require_once ("IPs.php");
    		break;

    default :
      echo "<br />L'application a rencontrée un problème :<br />Classe <b>".$Classe_a_charger."</b> inexistante.";
      exit();
    }
  }
?>
