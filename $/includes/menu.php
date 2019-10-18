 
<div id="accordion">
  <h3><?php echo _("Tableaux de bord"); ?></h3>
  <div>
      <ul>
        <li><a href="visu-donnees-systeme.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("État du système"); ?></a></li>
        <li><a href="munin.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("Graphiques système"); ?></a></li>
        <li><a href="visu-bandwidth.php"><img src="$/icones/chart_line.png" alt='' /><?php echo _("Bande passante"); ?></a></li>
      </ul>
  </div>


  <h3><?php echo _("Fonctions Utilisateur"); ?></h3>
  <div>
    <ul>
         <li><a href="subuser.php"><img src="$/icones/magnifier.png" alt='' /><?php echo _("Mot de passe utilisateur php"); ?></a></li>
        <li><a href="analyse_blocage_sites.php"><img src="$/icones/magnifier.png" alt='' /><?php echo _("Analyse des blocages de sites"); ?></a></li>
   </ul>
  </div>


  <h3><?php echo _("Fonctions de base"); ?></h3>
  <div>
    <ul>
        <li><a href="update-config.php"><img src="$/icones/cog_edit.png" alt='' /><?php echo _("Mise à jour FTP du filtre"); ?></a></li>
        <li><a href="reboot-idefix.php"><img src="$/icones/arrow_refresh.png" alt='' /><?php echo _("Redémarrage d'Idéfix"); ?></a></li>
        <li><a href="changer-password.php"><img src="$/icones/key.png" alt='' /><?php echo _("Changement du mot de passe"); ?></a></li>
    </ul>
  </div>
	
  <h3><?php echo _("Fonctions avancées"); ?></h3>
  <div>
    <ul>
        <li><a href="config-filtres.php"><img src="$/icones/computer_link.png" alt='' /><?php echo _("Configuration des filtres"); ?></a></li>
        <li><a href="config-reseau.php"><img src="$/icones/computer_link.png" alt='' /><?php echo _("Configuration Réseau"); ?></a></li>
        <li><a href="config-internet.php"><img src="$/icones/computer_link.png" alt='' /><?php echo _("Configuration Internet"); ?></a></li>
        <li><a href="update-system.php"><img src="$/icones/cog_edit.png" alt='' /><?php echo _("Mises à jour Système &bull; Interface"); ?></a></li>
        <li><a href="halt-idefix.php"><img src="$/icones/stop.png" alt='' /><?php echo _("Arrêt d'Idéfix"); ?></a></li>
      </ul>
  </div>

  <h3><?php echo _("Restauration"); ?></h3>
  <div>
    <ul>
        <li><a href="config-restauration-home.php"><img src="$/icones/cog.png" alt='' /><?php echo _("Configuration Idéfix"); ?></a></li>
        <li><a href="config-restauration-etc-usr.php"><img src="$/icones/cog.png" alt='' /><?php echo _("Configuration Système"); ?></a></li>
   </ul>
  </div>

  <h3><?php echo _("Gestion"); ?></h3>
  <div>
    <ul>
        <li><a href="config-backups-suppression.php"><img src="$/icones/cog.png" alt='' /><?php echo _("Suppression de sauvegardes"); ?></a></li>
   </ul>
  </div>


  <h3><?php echo _("Informations techniques"); ?></h3>
  <div>
    <ul>
        <li><a href="bandwidthd_jour.php"><img src="$/icones/chart_curve.png" alt='' />Bandwidthd : <?php echo _("Activité"); ?> <b><?php echo _("réseau"); ?></b></a></li>
        <li><a href="visu-users-connected.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("Utilisateurs connectés"); ?></a></li>
        <li><a href="visu-conf.php"><img src="$/icones/page.png" alt='' /><?php echo _("Visualiser les fichiers"); ?> <b>*.conf</b></a></li>
        <li><a href="visu-service-squid.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("État du service"); ?> <b>squid</b></a></li>
        <li><a href="visu-service-iptables.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("État du service"); ?> <b>iptables</b></a></li>
        <li><a href="visu-service-dhcp.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("État du service"); ?> <b>dhcp</b></a></li>
        <li><a href="visu-service-ddclient.php"><img src="$/icones/cmd.png" alt='' /><?php echo _("État du service"); ?> <b>ddclient</b></a></li>
      </ul>
  </div>
	
</div>


