<?php
/** @defgroup ClassesPHP Idéfix
    On trouve ici les classes pour créer et paramétrer
@{ */

/*!
 * @file      analyseBackups.php
 * @brief     Classe pour l'analyse les backups disponibles :\n
 *						soit en local : DIR_TEMP : /tmp/\n
 *						soit sur ftp : ftp.online.net\n
 *
 * @author     Idéfix
 * @version    1.0
 * @date       2019-02-19
 *
 * @warning		
 *
 * @note	public			peut être appelé de partout\n
 *				private			ne peut être appelé que dans la classe\n
 *				protected 	ne peut être appelé que dans la classe et les classes qui en hérite\n
 *
 * @note	
 *
 ***********************************************************************************/

class analyseBackups
	{
	/*-------------------------------------------------------------- Propriétés */
	// Rappel des constantes définies dans init.php
	
	// DIR_BACKUP_LOCAL : /var/spool/idefix/backup/
	// DIR_TEMP					: /tmp/
	
	
	private $depot; 			// local ou ftp
	private $dirTemp;			// Dossier temporaire de traitement des Zips
	
	private $dossiers_a_comparer;
	// Exclusions de fichiers
	private $fichiers_exclus = array();
	private $extensions_exclues = array();

	public $nb_onglets = 0;
	public $listeDates = '';
	/*
	private $dossiers_DEZIP;
	private $invalidFiles = array();

	private $bool;
	private $backupRetenu = array();
*/

	private $compteFTP = array();

	private $bool;
	private $backupRetenu = array();
	
	private $debug = false;
	/*---------------------------------------------------------- FIN Propriétés */

	/*------------------------------------------------------------ Constructeur */	
 	public function __construct($dossiers_a_comparer, $fichiers_exclus,	$extensions_exclues, $depot) {
		$this->dossiers_a_comparer	= $dossiers_a_comparer;
		$this->fichiers_exclus			= $fichiers_exclus;
		$this->extensions_exclues		= $extensions_exclues;
		$this->depot								= $depot;
	}
	/*-------------------------------------------------------- FIN Constructeur */

	/*------------------------------------------------------------- Destructeur */
  /*! @fn    Public function __destruct()
	 *  @brief Destructeur de la classe\n
	 */
	public function __destruct()
		{
		//	echo "A - nb_onglets = ".$this->nb_onglets."<br />";
		//	echo "A - listeDates = ".$this->listeDates."<br />";
			

			//exit;
		}
	/*--------------------------------------------------------- FIN Destructeur */

	/*------------------------------------------------------- afficheComparatif */
  /*! @fn				Public function afficheComparatif($nbBackupDesires)
	 *  @brief 
   *  @param		$nbBackupDesires		Nombre limite de backups à traiter
	 */
	public function afficheComparatif($nbBackupDesires)
	{
			switch($this->depot) {
				case "locales"	: $this->dirTemp = DIR_TEMP;
													$this->viderDossierTmp();
													$this->downloadZIP_local($nbBackupDesires);
													$dossiers_DEZIP = $this->deZipper();
/*
$dossiers_DEZIP[] = "idefix-backup-2019-02-07";
$dossiers_DEZIP[] = "idefix-backup-2019-02-08";
$dossiers_DEZIP[] = "idefix-backup-2019-02-09";		// diff 1
$dossiers_DEZIP[] = "idefix-backup-2019-02-31";
$dossiers_DEZIP[] = "idefix-backup-2019-02-32";
$dossiers_DEZIP[] = "idefix-backup-2019-02-33";		// diff 2
$dossiers_DEZIP[] = "idefix-backup-2019-02-34";
$dossiers_DEZIP[] = "idefix-backup-2019-02-35";
$dossiers_DEZIP[] = "idefix-backup-2019-02-36";		// diff 1
$dossiers_DEZIP[] = "idefix-backup-2019-02-37";		// diff 2
$dossiers_DEZIP[] = "idefix-backup-2019-02-38";
rsort($dossiers_DEZIP);

		echo "<pre>";
		print_r($this->compteFTP);
		echo "</pre>";
		exit;

*/												
												$d = new elimineZipContenusIdentiques($this->dirTemp,
																															$dossiers_DEZIP,
																															$this->dossiers_a_comparer,
																															$this->fichiers_exclus,
																															$this->extensions_exclues
																															);
												$ZIP_retenus = $d->getBackupsZipDifferents();
												if( count($ZIP_retenus) > 0 )
													$this->exeAnalyse($ZIP_retenus, $_SESSION['referer']);
												else
													echo "<div class='message'>Tous les backups disponibles n'ont aucune différence avec les fichiers actuels.</div>";
												break;
												
				case "ftp"		: $this->getCompteFTP();
												if( !$this->compteFTP[0] ) return "";	// Compte FTP non trouvé
												
												$this->dirTemp = DIR_TEMP;
												$this->viderDossierTmp();
//												exit;
												if( $this->downloadZIP_ftp($nbBackupDesires) ) {
														$dossiers_DEZIP = $this->deZipper();
														$d = new elimineZipContenusIdentiques($this->dirTemp,
																																	$dossiers_DEZIP,
																																	$this->dossiers_a_comparer,
																																	$this->fichiers_exclus,
																																	$this->extensions_exclues
																																	);
														$ZIP_retenus = $d->getBackupsZipDifferents();
														if( count($ZIP_retenus) > 0 )
															$this->exeAnalyse($ZIP_retenus, $_SESSION['referer']);
														else
															echo "<div class='message'>Tous les backups disponibles n'ont aucune différence avec les fichiers actuels.</div>";
												}
												break;
												
				default				: echo "Paramètre <b>".$this->depot."</b> non valide.<br />";

			}	
			
	}
	/*--------------------------------------------------- FIN afficheComparatif */
	
	/*-------------------------------------------------------------- exeAnalyse */
  /*! @fn				private function exeAnalyse()
	 *  @brief 
   *  @param		$ZIP_retenus		Array des backups retenus
   *  @param		$script					?
	 */
	private function exeAnalyse($ZIP_retenus, $script="")
	{
						/*echo "<pre>";
						print_r($ZIP_retenus);
						echo "</pre>";
						//exit;
						*/

		$kidtotal = 0;
		//$this->nb_onglets = 0;	// Pour compter le nombre de sauvegardes afin de générer autant d'onglets (script_ready_nav11.js)

		foreach ($ZIP_retenus as $d_DEZIP) {		// Pour chaque dossier dézippé
			$this->nb_onglets++;
			
			echo "\n<div id='aff_$d_DEZIP' class='cacher bloc'>";

			$date = substr($d_DEZIP, 14);
//echo "4 - ".$date;			exit;
			echo "<p class='titre_sauvegarde'>Sauvegarde du ".$this->formateDateSP($date)."</p>\n";
			echo "<div id='tabs_".$this->nb_onglets."' class='tab'>\n";

			// Constitution des onglets
			$onglets = "<ul>\n";
			foreach($this->dossiers_a_comparer as $key => $dossier) {
				$onglets .= "<li><a href='#".$date."_".$key."'>".$dossier."</a></li>\n";
			}
			$onglets .= "<li><a href='#infos' style='color:white;background-color:#30C0FF;'>Informations !</a></li>\n";
			$onglets .= "</ul>\n";
			echo $onglets;
			
			foreach($this->dossiers_a_comparer as $key => $dossier) {
				echo "<div id='".$date."_".$key."'>\n";
					$id_debut = "id_".$date."_".$key."_";
					
					// Fichiers actuels dans ce dossier : $dossier
					$fichiers_actuels = scandir($dossier, 1);		// 
					
					$d_backup = $this->dirTemp.$d_DEZIP."/".$dossier;
					//echo "******************* d_backup = ".$d_backup."<br />";
					// Fichiers correspondants dans le backup
					$fichiers_backup = scandir($d_backup, 1);
					//exit;
					$ancres = $this->getAncres($id_debut, $d_DEZIP, $dossier, $d_backup, $fichiers_actuels, $fichiers_backup, $kid, $fichiers_visites, $ancres3, $ancres4);
					$kidtotal += $kid;
			
					if($ancres  != "") echo $ancres;
					if($ancres3 != "") echo $ancres3;
					if($ancres4 != "") echo $ancres4;
					if($ancres  != "") {
						$this->entete_fixe_table($dossier, $date);		// Affiche l'entête fixe de la table
						//echo "<p>Affichage du comparatif des fichiers.</p>\n";
						$this->getDifferences($id_debut, $d_DEZIP, $dossier, $d_backup, $fichiers_actuels, $fichiers_backup, $fichiers_visites);
					}
					else {
						echo "<div class='aucune_difference'>"._("Aucune différence détectée sur les fichiers de ce dossier.")."</div>";
					}
				echo "</div>\n";
			}
			echo "<div id='infos'>\n";
				echo $this->affCartouche();
			echo "</div>\n";
			
			$this->listeDates .= "<p id='".$d_DEZIP."' class='btn_date'>".$this->formateDateSP($date)."</p>";
			echo "</div>\n";
			
			echo "<div style='text-align:center;margin:0.6rem auto 0 auto;'>";
				//echo "<input type='button' value='Restaurer en date du ".formateDateSP($date)."' class='restore' id='$backup' />";
				echo "<input type='button' value='"._("Restaurer en date du")." ".$this->formateDateSP($date)."' class='restore' id='$d_DEZIP' url='$script' />";
			echo "</div>";
		
		echo "</div>\n";
		
		
			
	//$listeDates = $this->exeAnalyse($ZIP_retenus, $b, $_SESSION['referer']);
	
		} 
		//return $this->listeDates;	
	}
	/*---------------------------------------------------------- FIN exeAnalyse */

	/*-------------------------------------------------------------- deZipper */
  /*! @fn    private function deZipper()
	 *  @brief 
							Fonction qui permet de n'extraire d'une archive ZIP
							QUE certains dossiers définis dans le tableau
							$this->dossiers_a_comparer
							On ne traite que les fichiers ZIP commençant par "idefix-backup-" (sécurité)
							Source : http://php.net/manual/fr/ziparchive.extractto.php#94921
	 */
//	private function extractDir($zipfile, $path) {
	private function deZipper() {
			$filesBackups = scandir($this->dirTemp, 1);
			$dossiers_DEZIP = array();
			$zip = new ZipArchive;
		
			foreach ($filesBackups as $filezip) {
				$files = array();
				
				if( !in_array($filezip, array(".", "..")) && !is_dir($filezip)) {
					if ( substr($filezip, 0, 14) == "idefix-backup-" && substr($filezip, 25, 3) == "zip") {
				
						// ------------- Début de l'extraction du Zip dans un dossier à son nom
						$DEZIP = substr($filezip, 0, -4);		// forme : idefix-backup-2019-01-20
						// Création du dossier
						@mkdir ( $this->dirTemp.$DEZIP, 0777, true );
						// Dézippage dans ce dossier
						if ($zip->open($this->dirTemp.$filezip) === TRUE) {
								for($i = 0; $i < $zip->numFiles; $i++) {
									$entry = $zip->getNameIndex($i);
									//Use strpos() to check if the entry name contains the directory we want to extract
									foreach ($this->dossiers_a_comparer as $ddd) {
										if (strpos("/".$entry, $ddd) !== false ) {
											//Add the entry to our array if it in in our desired directory
											$files[] = $entry;
										}
									}
								}
								$zip->extractTo($this->dirTemp.$DEZIP.'/', $files);
								$zip->close();
						}
						
						// ----------------------------------------- Fin de l'extraction du Zip
						$dossiers_DEZIP[] = $DEZIP;
					}
				}
			}
			if($this->debug) echo "Dézippage terminé.<br />";
			return $dossiers_DEZIP;
	}

	/*---------------------------------------------------------- FIN deZipper */

	/*--------------------------------------------------------- downloadZIP_local */
  /*! @fn    private function downloadZIP_local()
	 *  @brief 
							Téléchargement des fichiers ZIP de backup depuis Idéfix
							Source du script : http://memo-web.fr/categorie-php-195.php
	 */
	private function downloadZIP_local($nbBackupDesires = 57)
	{
			$backupzips = array();
			// Liste des backups.zip disponibles
			foreach (new DirectoryIterator(DIR_BACKUP_LOCAL) as $fileInfo) {
				if( !( $fileInfo->isDot() || $fileInfo->isDir() ) ) {
					$ff = $fileInfo->getFilename();
					$backupzips[] = $ff;
				}
			}
			// Tri des fichiers par "date" décroissante
			arsort($backupzips);
						/*
						echo "<pre>";
						print_r($backupzips);
						echo "</pre>";
						//exit;
						*/
			// Copie dans un dossier temporaire de ces archives
			$k = 1;
			foreach ($backupzips as $backupzip) {
				if($k <= $nbBackupDesires) {					
                    copy ( DIR_BACKUP_LOCAL.$backupzip , DIR_TEMP.$backupzip);
					$k++;
				} else {
					break;
				}
			}

	}
	
	/*--------------------------------------------------- FIN downloadZIP_local */

	/*--------------------------------------------------------- downloadZIP_ftp */
  /*! @fn    private function downloadZIP_ftp()
	 *  @brief 
							Téléchargement des fichiers ZIP de backup depuis le serveur FTP
	 */
	private function downloadZIP_ftp($nbBackupDesires = 57) {
	
		$conn_id = @ftp_connect($this->compteFTP['ftp'], 21, 4) or die (_("Impossible de se connecter au serveur")." ".$this->compteFTP['ftp']);
		// Identification avec un nom d'utilisateur et un mot de passe
		$login_result = @ftp_login($conn_id, $this->compteFTP['ftp_user_name'], $this->compteFTP['ftp_user_pass']);

		// Vérification de la connexion
		if ((!$conn_id) || (!$login_result)) {
			echo "<p>"._("La connexion FTP a échoué.")."</p>";
			echo "<p>"._("Tentative de connexion au serveur")." <b>".$this->compteFTP['ftp']."</b></p>";
			echo "<p>"._("pour l'utilisateur")." <b>".$this->compteFTP['ftp_user_name']."</b></p>";
			return false;
		}
		
		// Activation du mode passif
		//ftp_pasv() active ou non le mode passif. En mode passif, les données de connexion sont initiées par le client, plutôt que par le serveur.
		//Ce mode peut être nécessaire lorsque le client est derrière un pare-feu. 

		ftp_pasv($conn_id, true);

		// Récupération de la liste des fichiers contenus dans le dossier backup
		$contents = ftp_nlist($conn_id, "backup");

		// Supprimer '.' et '..'
		$contents = array_diff($contents, [".", ".."]);

		// Tri des fichiers par "date" décroissante
		arsort($contents);

		// Téléchargement dans un dossier temporaire de ces archives
		$k = 1;
		foreach ($contents as $backupzip) {
			if($k <= $nbBackupDesires) {
				//echo DIR_TEMP.$backupzip;
				//ftp_get ( $ftp_stream , $local_file , $remote_file , $mode = FTP_BINARY );
				ftp_get($conn_id, $this->dirTemp.$backupzip,'backup/'.$backupzip, FTP_BINARY);
				$k++;
			} else {
				break;
			}
		}

		ftp_close($conn_id);
		return true;
	}

	/*--------------------------------------------------- FIN downloadZIP_local */

	/*--------------------------------------------------------- viderDossierTmp */
  /*! @fn    Public function viderDossierTmp()
	 *  @brief 
	 */
	private function viderDossierTmp()
		{
			//if($this->debug) echo "Purge du dossier temporaire : ".$this->dirTemp."<br />";
//echo $this->dirTemp;
//exit;
			if(PHP_OS == 'Linux') {
				$commande = "sudo rm -rf ".$this->dirTemp."*";
				// echo $commande ; exit;
				shell_exec($commande);
			}
			else {
				switch($this->EtatDuRepertoire()) {
					case 0	: mkdir($this->dirTemp, 0777, true);
										if($this->debug) echo "Le dossier temporaire <b>".$this->dirTemp."</b> a été créé.</br />";
										break;
					case 1	: if($this->debug) echo "Le dossier temporaire <b>".$this->dirTemp."</b> est vide.</br />";
										break;
					case 2	: $dir_iterator = new RecursiveDirectoryIterator($this->dirTemp);
										$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::CHILD_FIRST);
										// On supprime chaque dossier et chaque fichier	du dossier cible
										foreach($iterator as $fichier){
											 $fichier->isDir() ? @rmdir($fichier) : unlink($fichier);
										}
										if($this->debug) echo "Purge du dossier temporaire <b>".$this->dirTemp."</b></br />";
				}
			}
		
		}
		
	/*----------------------------------------------------- FIN viderDossierTmp */

	/*-------------------------------------------------------- EtatDuRepertoire */
  /*! @fn    private function EtatDuRepertoire()
	 *  @brief 
						 La fonction PHP suivante détermine l'état d'un répertoire :
								- 0 : répertoire inexistant
								- 1 : répertoire existe mais il est vide
								- 2 : répertoire existe et contient des fichiers
						Source : http://memo-web.fr/categorie-php-155.php
	 */
	private function EtatDuRepertoire()
		{
			if($this->debug) echo "Analyse de l'état du répertoire temporaire : ".$this->dirTemp."<br />";
			$fichierTrouve=0;
			if (is_dir($this->dirTemp)) {
				if ($dh = opendir($this->dirTemp)) {
					while (($file = readdir($dh)) !== false && $fichierTrouve==0) {
						if ($file!="." && $file!=".." ) $fichierTrouve = 1;
					}
					closedir($dh);
				}
			}
			else {
				//echo ("Le répertoire n'existe pas");
				return 0;
			}
			if( $fichierTrouve==0) {
				//echo ("Le répertoire existe mais il est vide");
				return 1;
			}
			else {
				//echo ("Le répertoire contient des fichiers");
				return 2;
			}
		
		}
	/*---------------------------------------------------- FIN EtatDuRepertoire */



/* -------------------------------------------------- *\
		Formatage de l'affichage des dates
		$date : entrée au format : aaaa-mm-jj
\* -------------------------------------------------- */

private function formateDateSP($date, $format='fr_1') {
	$pieces = explode("-", $date);
	switch($format) {
		case 'fr_1'	:	// jj-mm-aaaa
		default			: return $pieces[2]."-".$pieces[1]."-".$pieces[0];
	}
}


/* ----------------------------------------------------------------- *\
    Affiche l'entête fixe de la table
\* ----------------------------------------------------------------- */

private function entete_fixe_table($dossier, $date) {
	echo "<div class='detail_entete'>";
	echo "<table>";
	echo "<tr>";
	echo "<th class='th1'>Fichiers actuels sur Idéfix : <span style='color:yellow'>".$dossier."</span></th><th>"._("ligne")."</th><th class='th3'>Fichiers de la "._("Sauvegarde du")." ".$this->formateDateSP($date)."</th><th class='th4'></th>";
	echo "</tr>";
	echo "</table>";
	echo "</div>\n\n";
}

/* --------------------------------------------------------- *\
		Affichage du début de l'affichage
\* --------------------------------------------------------- */
	
private function setTableDebut($date) {	
	echo "<div class='detail'>";
	echo "<table>";
	echo "<tbody>";
}
	
/* --------------------------------------------------------- *\
		Affichage de la fin de l'affichage
\* --------------------------------------------------------- */
	
private function setTableFin($backup) {	
	$date = substr($backup, 14);

	echo "</tbody>";
	echo "</table>";
	echo "</div>";
	/*
	echo "<div style='text-align:center;margin:0.6rem auto 0 auto;'>";
		//echo "<input type='button' value='Restaurer en date du ".formateDateSP($date)."' class='restore' id='$backup' />";
		echo "<input type='button' value='"._("Restaurer en date du")." ".formateDateSP($date)."' class='restore' id='$backup' />";
	echo "</div>";
	*/
}	

/* ----------------------------------------------------------------- *\
    Affiche la source des backups
\* ----------------------------------------------------------------- */

private function afficheSourceBackups($sourceBackups) {
	echo "Source des backups : ";
	echo "<input type='radio' id='ftp_backup' name='source_backup' value='ftp_backup' checked />";
	echo "<label for='ftp_backup' >".$sourceBackups."</label>";
	
	echo "<input type='radio' id='local_backup' name='source_backup' value='local_backup' disabled />";
	echo "<label for='local_backup' >Idéfix</label>";
}

/* -------------------------------------------------- *\
		Cartouche
\* -------------------------------------------------- */

private function affCartouche() {
	//$span = "<span>&bull;</span> &nbsp;";
	
	$cartouche  = "<div class='infos'>\n";
	
	$cartouche .= "<p><b>"._("Ne sont pas affichés")." :</b>\n";
	$cartouche .= "<ul class='ul'>\n";
	$cartouche .= "<li>"._("les sauvegardes qui sont identiques entre elles")."</li>\n";
	$cartouche .= "<li>"._("les fichiers actuels qui sont identiques avec ceux de la sauvegarde")."</li>\n";
	$cartouche .= "<li>"._("les lignes qui sont identiques pour les fichiers finalement retenus")."</li>\n";
	$cartouche .= "</ul></p>\n";
/*
	$cartouche .= "<p>"._("Ne sont affichés")." :<br />\n";
	$cartouche .= $span._("que les sauvegardes qui ne sont pas identiques avec la sauvegarde")."<br />\n";
	$cartouche .= $span._("que les fichiers qui diffèrent avec la sauvegarde")."<br />\n";
	$cartouche .= $span._("que les lignes non identiques de ces mêmes fichiers")."</p>\n";
*/	
	$cartouche .= "<p><b>"._("Attention !")."</b>\n";
	$cartouche .= "<ul class='ul'>\n";
	$cartouche .= "<li>"._("La restauration porte sur <u>l'ensemble des fichiers</u> contenus dans l'archive ZIP")."</li>\n";
	//$cartouche .= "<li>"._("La restauration porte sur <u>l'ensemble des fichiers</u> contenus dans l'archive ZIP")."</li>\n";
	$cartouche .= "</ul></p>\n";

	
	$cartouche .= "<p><b>"._("Fichiers exclus")." :</b>\n";
	$cartouche .= "<ul class='ul'>\n";
	foreach($this->fichiers_exclus as $ff) {
		if( !in_array($ff, array(".", "..")) ) {
			//$cartouche .= $span.$ff."<br />\n";
			$cartouche .= "<li>".$ff."</li>\n";
		}
	}
	$cartouche .= "</ul></p>\n";
	
	$cartouche .= "<p><b>"._("Extensions exclues")." :</b>\n";
	$cartouche .= "<ul class='ul'>\n";
	foreach($this->extensions_exclues as $ext) {
		//$cartouche .= $span.$ext."<br />\n";
		$cartouche .= "<li>".$ext."</li>\n";
	}
	$cartouche .= "</ul></p>\n";

	$cartouche .= "</div>\n";
	return $cartouche;
}

/* ------------------------------------------------------------------ *\
		Obtention des ancres pour chaque fichier analysé de chaque archive ZIP
		- 1ère passe : depuis les fichiers trouvés dans l'achive ZIP
\* ------------------------------------------------------------------ */

private function getAncres($id_debut, $d_DEZIP, $DOSSIER, $DOSSIER_backup, $fichiers_actuels, $fichiers_backup, &$kid, &$fichiers_visites, &$ancres3, &$ancres4) {
	$fichiers_visites = array();
	$kid = 0;
	$kid3 = 0;
	$kid4 = 0;
	$ancres = "<div class='ancres2'><div>Fichiers différents : </div><div>"; // Fichiers différents
	$ancres3 = "<div class='ancres3'><div>Fichiers identiques : </div><div>"; // Fichiers identiques
	$ancres4 = "<div class='ancres4'><div>Fichiers exclus : </div><div>"; // Fichiers exclus
	
	// 1ère passe
	foreach($fichiers_backup as $ff) {							// Base de recherche : les dossiers ZIP
		//if( !in_array($ff, array(".", "..", "cron.log")) ) {
		//if( !in_array($ff, $GLOBALS['fichiers_exclus'] ) ) {
			//echo $ff;
			//if(is_dir($ff)) echo "------- Dossier -------";	
			//exit;
		if( $this->fichierRetenu($DOSSIER_backup, $ff) ) {
				$fichiers_visites[] = $ff;
				//if( !is_dir( $DOSSIER_backup.$ff ) ) {
					$fic1 = $DOSSIER.$ff;
					$fic2 = $DOSSIER_backup.$ff;
//					echo "\$fic1 = $fic1 --- \$fic2 = $fic2<br />";
					if( !$this->compareFichiers($fic1, $fic2) ) {
						$kid++;
						//$id = "id_".$kid."_".substr($d_DEZIP, 14);
						//$ancres .= "<a href='#$id' class='ancre'>".$ff."</a> ";
						$id = $id_debut.$kid;
						//$ancres .= "<a href='#$id' class='ancre'>".$ff."</a> ";
						//$ancres .= "<span class='ancre2'><input type='checkbox' id='' />";
						//$ancres .= "<a href='#$id' class='ancre_'>".$ff."</a></span>";
						$ancres .= "<a href='#$id' class='ancre2'>".$ff."</a>";
					} else {
						$ancres3 .= "<span class='ancre3'>".$ff."</span>";
						$kid3++;
					}
				//}
		}
		else {
			if( !is_dir( $DOSSIER_backup.$ff ) ) {
				$ancres4 .= "<span class='ancre4'>".$ff."</span>";
				$kid4++;
			}
			
		}
	}
	
	$ancres .= "</div></div>\n";
	$ancres3 .= "</div></div>\n";
	$ancres4 .= "</div></div>\n";
	if($kid3 == 0 ) $ancres3 = "";
	if($kid4 == 0 ) $ancres4 = "";

	if($kid > 0 ) return $ancres; else return "";
}

/* ------------------------------------------------------------------ *\

\* ------------------------------------------------------------------ */

private function getDifferences($id_debut, $d_DEZIP, $DOSSIER, $DOSSIER_backup, $fichiers_actuels, $fichiers_backup, $fichiers_visites) {

	$kuid = 1;
	$date = substr($d_DEZIP, 14);
	//$id = "id_".$kuid."_".$date;
	$id = $id_debut.$kuid;

	$this->setTableDebut($date);
	// 1ère passe
	foreach($fichiers_backup as $ff) {				// Base de recherche : les dossiers ZIP
//	echo "\$ff = ".$ff."<br />";
	
		//if( !in_array($ff, array(".", "..", "cron.log")) ) {
		//if( !in_array($ff, $GLOBALS['fichiers_exclus'] ) ) {
		if( $this->fichierRetenu($DOSSIER_backup, $ff) ) {
			
//				echo "\$DOSSIER_backup.\$ff = ".$DOSSIER_backup.$ff."<br />";
			//if( !is_dir( $DOSSIER_backup.$ff ) ) {
				if( $this->getDifflignes($DOSSIER, $d_DEZIP, $ff, $id) ) {
					$kuid++;
					//$id = "id_".$kuid."_".$date;
					$id = $id_debut.$kuid;
/*				
	echo "\$kuid = ".$kuid."<br />";
	echo "\$id = ".$id."<br />";exit;
*/
				}
			//}
			
		}
	}
	/*
	// 2ème passe
	foreach($fichiers_actuels as $ff) {			// Base de recherche : les dossiers actuels
		//if( !in_array($ff, $GLOBALS['fichiers_exclus'] ) && !in_array($ff, $fichiers_visites) ) {
		if( $this->fichierRetenu($DOSSIER, $ff, $fichiers_visites) ) {

			//if( !is_dir( $DOSSIER.$ff ) ) {
				if( $this->getDifflignes($DOSSIER, $d_DEZIP, $ff, $id) ) {
					$kuid++;
					//$id = "id_".$kuid."_".$date;
					$id = $id_debut.$kuid;
				}
			//}
		}
	}
		*/					
	$this->setTableFin( $d_DEZIP );
}

/* ------------------------------------------------------------------ *\

\* ------------------------------------------------------------------ */

private function getDifflignes($DOSSIER, $backup, $fichier, $id) {
	//$fic1 = "home/rock64/idefix/".$fichier;
	//$fic1 = PATH_HOME_ROCK64_IDEFIX.$fichier;
	$fic1 = $DOSSIER.$fichier;
	if(file_exists($fic1)) {
		$fic1_lecture = file($fic1);
		foreach($fic1_lecture as $ligne) {
			$ligne = trim($ligne);
			$Lignes1[] = $ligne;
		}
		$nb1 = count($Lignes1);
		$fichier1 = $fichier;
	} else {
		$nb1 = 0;
		$fichier1 = "<span style='color:blue;display:inline-block;'>".$fichier." : <span style='color:red;'>"._("absent")."</span></span>";
	}
	
	//$fic2 = "temp/".$backup."/home/rock64/idefix/".$fichier;
	//$fic2 = DIR_TEMP.$backup."/".PATH_HOME_ROCK64_IDEFIX.$fichier;
	$fic2 = $this->dirTemp.$backup."/".$DOSSIER.$fichier;
	if(file_exists($fic2)) {
		$fic2_lecture = file($fic2);
		foreach($fic2_lecture as $ligne) {
			$ligne = trim($ligne);
			$Lignes2[] = $ligne;
		}
		$nb2 = count($Lignes2);
		$fichier2 = $fichier;
	} else {
		$nb2 = 0;
		$fichier2 = "<span style='color:blue;display:inline-block;'>".$fichier." : <span style='color:red;'>"._("absent")."</span></span>";
	}
	
	$nb = max($nb1, $nb2);
	$compare = array();
	
	for($k = 0; $k < $nb; $k++) {
		if(isset($Lignes1[$k]) && isset($Lignes2[$k]) ) {
			// Note : The function levenshtein in PHP works on strings with maximum length 255.
			//$z = levenshtein($Lignes1[$k], $Lignes2[$k]);
			//echo "ligne $k : ", levenshtein($Lignes1[$k], $Lignes2[$k])."<br />";
			
			if( sha1($Lignes1[$k]) == sha1($Lignes2[$k]) ) $z = 0; else $z = 1;
			
			if($z != 0 ) {
				$compare[0][] = $k + 1;	//$k;			// Numéro de ligne
				$compare[1][] = $Lignes1[$k];
				$compare[2][] = $Lignes2[$k];
			}
		}
		else {
			$compare[0][] = $k + 1;	//$k;			// Numéro de ligne
			if(isset($Lignes1[$k])) {
				$compare[1][] = $Lignes1[$k];
				$compare[2][] = "";
			}
			if(isset($Lignes2[$k])) {
				$compare[1][] = "";
				$compare[2][] = $Lignes2[$k];
			}
		}
	}

//$date = substr($backup, 14);
//echo $date;
	
	if( isset($compare[0])) {	// On n'affiche pas les fichiers identiques
		echo "<tr id='$id'><th>".$fichier1."</th><th>&nbsp;</th><th>".$fichier2."</th></tr>";
		for($k = 0; $k < count($compare[0]); $k++) {
			echo "<tr><td>".$compare[1][$k]."</td><td>".$compare[0][$k]."</td><td>".$compare[2][$k]."</td></tr>";
		}
		return true;
	} else {
		return false;	// on n'incrémente pas $kid car il n'a pas été utilisé
	}
}

/* -------------------------------------------------- *\
		Vérifie que le fichier n'est pas dans :
		- une liste de fichiers exclus
		- une liste d'extensions exclues
		- une liste de fichiers déjà visités (optionnel)
\* -------------------------------------------------- */

private function fichierRetenu($d, $fichier, $fichiers_visites='') {
	/*
if($fichier == 'users.ini') {	
	echo 	$fichier; exit;
}

echo "<pre>";
print_r($this->fichiers_exclus);
print_r($this->extensions_exclues);
echo "</pre>";
exit;
*/

	if( !is_dir( $d.$fichier ) ) {
			if( !in_array($fichier, $this->fichiers_exclus ) ) {
				$fileinfo = pathinfo($fichier);
		/*
		if(is_dir($fichier)) echo "------- Dossier -------";		
				
		echo "<pre>";
		print_r($fileinfo);
		echo "</pre>";
		exit;
		*/
				$ext = strtolower($fileinfo["extension"]);
				
				if (!in_array($ext, $this->extensions_exclues)) {
					/*
					if ($fichiers_visites == '')
						return true;
					else
						if (!in_array($fichier, $fichiers_visites) ) return true;
					*/
					//echo "Fichier = ".$fichier. " +++ true +++<br />";
					return true;
				}
			}
	}//echo "Fichier = ".$fichier. " +++ false +++<br />";
	return false;
}


/* --------------------------------------------------------- *\
		Comparaison rapide des fichiers
\* --------------------------------------------------------- */

private function compareFichiers($fichier1, $fichier2) {
	//echo sha1_file($fichier1)."<br />";
	//echo sha1_file($fichier2)."<br />";
	
	if(!file_exists($fichier1) || !file_exists($fichier2)) return false;
		
	if (sha1_file($fichier1) == sha1_file($fichier2)) return true;
	return false;
}


/* ======================================================================== *\
		Fonctions utilisées par 
		
		- config-restauration-home.php
		- config-restauration-etc-usr.php

		dans le cadre d'accès aux backups en ftp
\* ======================================================================== */

/* --------------------------------------------------------- *\
		Obtention des informations du compte FTP
\* --------------------------------------------------------- */

  /*! @fn     private function getCompteFTP()
   *
	 * @brief   Obtention des informations du compte FTP\n
   */

private function getCompteFTP() {
	if(!file_exists(PATH_IDEFIX_CONF)) {
		echo "<div class='message'><p>"._("Impossible d'accéder aux sauvegardes")."</p><p>"._("Le fichier")."</p><p><b>".PATH_IDEFIX_CONF."</b></p><p>"._("est inexistant")."</p></div>";
		$this->compteFTP[0] = false;
	} else {
		$fichier_lecture = file(PATH_IDEFIX_CONF);

		foreach($fichier_lecture as $ligne) {
			$ligne = trim($ligne);    // Pour supprimer les éventuels blancs après le nom de section
			// Retirer un éventuel BOM en début de ligne
			$ligne = str_replace("\xEF\xBB\xBF",'',$ligne);
			$pieces = explode("=", $ligne);
			if( trim($pieces[0]) == 'idefix_id' ) $this->compteFTP['ftp_id']				= trim($pieces[1]);
			if( trim($pieces[0]) == 'login' ) 		$this->compteFTP['ftp_user_name']	= trim($pieces[1]);
			if( trim($pieces[0]) == 'password' ) 	$this->compteFTP['ftp_user_pass']	= trim($pieces[1]);
			if( trim($pieces[0]) == 'ftp' ) 			$this->compteFTP['ftp']						= trim($pieces[1]);
		}
	
		if($this->compteFTP['ftp_id'] == '' || $this->compteFTP['ftp_user_name'] == '' || $this->compteFTP['ftp_user_pass'] == '') {
			echo "<div class='message'><p>"._("Impossible d'accéder aux sauvegardes")."</p><p>"._("Pas de compte FTP disponible dans le fichier")."</p><p><b>".PATH_IDEFIX_CONF."</b></p></div>";
			$this->compteFTP[0] = false;
		} else {
			$this->compteFTP[0] = true;
			$this->compteFTP['ftp_user_pass'] = $this->dechiffrer( $this->compteFTP['ftp_user_pass']	);
		}
		
	}
}

/* --------------------------------------------------------- *\
		Déchiffrage du mot de passe
\* --------------------------------------------------------- */

/*! @fn     private function dechiffrer($chaine)
   *
	 * @brief   Déchiffrage d'un mot de passe chiffré\n
	 *
	 * @param		$chaine	Chaîne à déchiffrer
	 * 
	 * @note		substr ($chaine , 0 , 1) != '%') :\n
	 * 					Mot de passe considéré comme non chiffré
   */

private function dechiffrer($chaine) {
  $chaine = trim($chaine);
	$lg = strlen($chaine);
  if($lg == 0  || substr ($chaine , 0 , 1) != '%') return $chaine;
  $mdp = '';
  for ($i=1; $i<=$lg; $i += 2) {
    $mdp .= substr ($chaine , $i , 1);
  }
  return $mdp;
}

/* ------------------------------------------------------------------ *\

\* ------------------------------------------------------------------ */

	}// Fin de la classe

/** @} */
?>	