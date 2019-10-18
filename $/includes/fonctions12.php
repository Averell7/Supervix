<?php
/* -------------------------------------------------- *\
		Obtenir la liste des sauvegardes
		afin de pouvoir les sélectionner pour les supprimer
\* -------------------------------------------------- */

/* --------------------------------------------------------- *\
		Obtention du compte FTP
\* --------------------------------------------------------- */

function getCompteFTP() {
	if(!file_exists(PATH_IDEFIX_CONF)) {
		echo "<div class='message'><p>"._("Impossible d'accéder aux sauvegardes")."</p><p>"._("Le fichier")."</p><p><b>".PATH_IDEFIX_CONF."</b></p><p>"._("est inexistant")."</p></div>";
		$compteFTP[0] = false;
	} else {
		$fichier_lecture = file(PATH_IDEFIX_CONF);

		foreach($fichier_lecture as $ligne) {
			$ligne = trim($ligne);    // Pour supprimer les éventuels blancs après le nom de section
			// Retirer un éventuel BOM en début de ligne
			$ligne = str_replace("\xEF\xBB\xBF",'',$ligne);
			$pieces = explode("=", $ligne);
			//if( trim($pieces[0]) == 'idefix_id' ) $compteFTP['ftp_id']				= trim($pieces[1]);
			if( trim($pieces[0]) == 'ftp' ) 			$compteFTP['ftp']				= trim($pieces[1]);
			if( trim($pieces[0]) == 'login' ) 		$compteFTP['ftp_user_name']	= trim($pieces[1]);
			if( trim($pieces[0]) == 'password' ) 	$compteFTP['ftp_user_pass']	= trim($pieces[1]);
		}
	
//		if($compteFTP['ftp_id'] == '' || $compteFTP['ftp_user_name'] == '' || $compteFTP['ftp_user_pass'] == '') {
		if($compteFTP['ftp'] == '' || $compteFTP['ftp_user_name'] == '' || $compteFTP['ftp_user_pass'] == '') {
			echo "<div class='message'><p>"._("Impossible d'accéder aux sauvegardes")."</p><p>"._("Pas de compte FTP disponible dans le fichier")."</p><p><b>".PATH_IDEFIX_CONF."</b></p></div>";
			$compteFTP[0] = false;
		} else {
			$compteFTP[0] = true;
			$compteFTP['ftp_user_pass'] = dechiffrer( $compteFTP['ftp_user_pass']	);
		}
	}
	return $compteFTP;
}

/* --------------------------------------------------------- *\
		Obtention de la liste des sauvegardes
\* --------------------------------------------------------- */

function getListeBackupsZIP($compteFTP) {
	if(!$compteFTP[0]) return;		// Pas de compte FTP
/*
echo "<pre>";
echo print_r($compteFTP);
echo "</pre>";
exit;
*/
//	echo BACKUP_PLACE; exit;
	$backupzips = array();

	switch(BACKUP_PLACE) {
		case "locales":
								foreach (new DirectoryIterator(DIR_BACKUP_LOCAL) as $fileInfo) {
									if( !( $fileInfo->isDot() || $fileInfo->isDir() ) ) {
										$ff = $fileInfo->getFilename();
										// On ne retient que les backups qui ont "idefix-backup" dans leur nom
										$pos = strrpos($ff, "idefix-backup");
										if ($pos === false) { // non trouvé ...
										} else {
											$backupzips[] = $ff;
										}
										
									}
								}
								// Tri des fichiers par "date" décroissante
								arsort($backupzips);
		
								break;

		case "ftp":
								$conn_id = @ftp_connect($compteFTP['ftp'], 21, 4) or die (_("Impossible de se connecter au serveur")." ".$compteFTP['ftp']);
								// Identification avec un nom d'utilisateur et un mot de passe
								$login_result = ftp_login($conn_id, $compteFTP['ftp_user_name'], $compteFTP['ftp_user_pass']);

								// Vérification de la connexion
								if ((!$conn_id) || (!$login_result)) {
									echo "<p>"._("La connexion FTP a échoué.")."</p>";
									echo "<p>"._("Tentative de connexion au serveur")." <b>".$compteFTP['ftp']."</b></p>";
									echo "<p>"._("pour l'utilisateur")." <b>".$compteFTP['ftp_user_name']."</b></p>";
									return;
								}

								ftp_pasv($conn_id, true);

								// Récupération du contenu d'un dossier
								$backupzips = ftp_nlist($conn_id, "backup");
								// Fermeture du flux FTP
								ftp_close($conn_id);
								// Supprimer '.' et '..'
								$backupzips = array_diff($backupzips, [".", ".."]);
								// On ne retient que les backups qui ont "idefix-backup" dans leur nom
								foreach ($backupzips as $key => $ff) {
										$pos = strrpos($ff, "idefix-backup");
										if ($pos === false) { // non trouvé ...
											unset($backupzips[$key]); 
										}
								}	

								// Tri des fichiers par "date" décroissante
								arsort($backupzips);
								// Réindexation (à cause de la suppression de [".", ".."]
								$backupzips = array_values($backupzips);
								break;
	}


	// Affichage de la liste des archives
/*	
	echo "<pre>";
	echo print_r($backupzips);
	echo "</pre>";
	exit;
*/

	foreach ($backupzips as $key => $filezip) {
		//echo "$key => $filezip<br />\n";
		$date = substr( substr($filezip, 0, -4), 14);
		echo "<input type='checkbox' id='checkbox$key' name='checkbox$key' value='$filezip' /> ".formateDateSP($date)."<br />\n";
	}

}


/* ----------------------------------------------------------------- *\
    Déchiffrage d'un mot de passe chiffré
		
		substr ($chaine , 0 , 1) != '%') :
		Mot de passe considéré comme non chiffré
\* ----------------------------------------------------------------- */

function dechiffrer($chaine) {
  $chaine = trim($chaine);
	$lg = strlen($chaine);
  if($lg == 0  || substr ($chaine , 0 , 1) != '%') return $chaine;
  $mdp = '';
  for ($i=1; $i<=$lg; $i += 2) {
    $mdp .= substr ($chaine , $i , 1);
  }
  return $mdp;
}

/* -------------------------------------------------- *\
		Formatage de l'affichage des dates
		$date : entrée au format : aaaa-mm-jj
\* -------------------------------------------------- */

function formateDateSP($date, $format='fr_1') {
	$pieces = explode("-", $date);
	switch($format) {
		case 'fr_1'	:	// jj-mm-aaaa
		default			: return $pieces[2]."-".$pieces[1]."-".$pieces[0];
	}
}



