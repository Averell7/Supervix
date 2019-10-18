<?php
if (!isset($_POST['domaine'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
$domaine = $_POST['domaine'];

/*
$domaine = "https://www.stackoverflow.com";
$domaine = "https://www.youtube.xx";

// Nettoyage éventuel
$domaine  = parse_url($domaine, PHP_URL_HOST);							// Retire https:// , etc.

// Rajouter à la datalist les noms de domaine qui sont stockés dans $/settings/datalist_domains.txt
$handle = @fopen("$/settings/datalist_domains.txt", "a+");

$trouve = false;
if ($handle) {
	// Vérifier au prélable qu'il ne soit pas déjà stocké et qu'il existe
	while (($d = fgets($handle)) !== false) {
		if(rtrim($d) === $domaine) {
			//echo "--- ".$d." ---";
			$trouve = true;
			continue;
		}
	}
	if(!$trouve) fputs ($handle, $domaine."\n");					// "\n" et non '\n' !
} else {
	echo "Erreur SP_11";		// Fichier non trouvé
	exit;
}

rewind ($handle);
$optionsDatalist = "";
while (($d = fgets($handle)) !== false) {
	$optionsDatalist .= "<option value='".rtrim($d)."'>\n";
}
echo $optionsDatalist;

fclose ($handle);
*/

echo json_encode( verifierBlocageDNS($domaine) );
//echo  verifierBlocageDNS($domaine);

/* ------------------------------------------------------------- *\
		Vérifie l'accès à un nom de domaine
\* ------------------------------------------------------------- */
function verifierBlocageDNS($domaine) {
	$ch = curl_init();
	if (!$ch) {
		return "Erreur SP_12";			// impossible d'initialiser un handle cURL.
		exit;
	}
  $ret = curl_setopt($ch, CURLOPT_URL, $domaine);
  $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$ret = curl_exec($ch);
	$numErreur = curl_errno($ch);
	$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$msg = "Site<br /><b>".$domaine."</b><br />";
	switch ($numErreur) {
		case 0	: $msg .= "non bloqué.";
							/*[ HTTP Error : '.$responseCode.']<br />'; */
							$optionsDatalist = stockerDomaine($domaine);
							break;
		case 6	: $msg .= "inconnu.";
							$optionsDatalist = "";
							break;											/* CURLE_COULDNT_RESOLVE_HOST */
		case 9	: $msg = "bloqué.<br />[ HTTP Error : ".$responseCode." ]"; 
							$optionsDatalist = stockerDomaine($domaine);
							break;											/* CURLE_REMOTE_ACCESS_DENIED */
		default : $msg = "[ HTTP Error : ".$responseCode." ]"; 
							$optionsDatalist = "";
							break;
	}
	$retour[0] = $msg;
	$retour[1] = $optionsDatalist;
	return $retour;
}

/* ------------------------------------------------------------- *\
		On stocke le nom de domaine si il est nouveau
		dans : $/settings/datalist_domains.txt
		Et dans ce cas on retourne le datalist qui sera réactualisé
\* ------------------------------------------------------------- */

function stockerDomaine($domaine) {
	$handle = @fopen("$/settings/datalist_domains.txt", "a+");
	if ($handle) {
		$trouve = false;

		// Vérifier au prélable qu'il ne soit pas déjà stocké
		while (($d = fgets($handle)) !== false) {
			if(rtrim($d) === $domaine) {
				//echo "--- ".$d." ---";
				$trouve = true;
				continue;
			}
		}
		if(!$trouve) fputs ($handle, $domaine."\n");					// "\n" et non '\n' !
		
		rewind ($handle);
		$optionsDatalist = "";
		while (($d = fgets($handle)) !== false) {
			$optionsDatalist .= "<option value='".rtrim($d)."'>\n";
		}
		fclose ($handle);
		return $optionsDatalist;
		
	} else {
		echo "Erreur SP_11";		// Fichier non trouvé
		exit;
	}
	
}
?>














