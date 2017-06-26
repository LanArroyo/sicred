<?php

/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 23/06/2017
 * Time: 8:33
 */

namespace Sicred\Model;

use Doctrine\DBAL\Driver\PDOException;

class Connect
{
    protected $db;

    public function Connection(){
        $conn = null;

        try{
            $conn = new \PDO("oci:host=129.144.47.62;dbname=NUBEBDU;port=1524", "ADMPORTAL","CBUSRADMORA11");
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            ?> <script>console.log("Conexion exitosa!")</script>
        <?php
        }catch (PDOException $e){ ?>
            <script>console.log("ERROR: "' . <?php $e->getMessage()?> .') </script>
        <?php }
        $this->db = $conn;
    }

    public function getConnection(){
        return $this->db;
    }

}
?>