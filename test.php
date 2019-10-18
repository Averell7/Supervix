
<?php

$id = dba_open("/var/www/idefix/$/settings/vsftp_login.db", "n", "db2");

if (!$id) {
    echo "dba_open a échoué\n";
        exit;
        }

        dba_replace("key", "Ceci est un exemple !", $id);

        if (dba_exists("key", $id)) {
            echo dba_fetch("key", $id);
                dba_delete("key", $id);
                }

                dba_close($id);
                ?>
                
