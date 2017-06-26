<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 26/06/2017
 * Time: 9:29
 */

namespace Sicred\Model;

use Doctrine\DBAL\Driver\PDOException;

class serchStudent
{
    protected $db;

    private function Connection(){
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

    public function getStudent($matricula, $plantel){
        self::Connection();
        $result = null;
        $sql = "SELECT MATRICULA, NOMBRE, PLANTEL, CURP FROM ADMPORTAL.DATOS_ALUMNOS_RESP WHERE MATRICULA='".$matricula."' AND PLANTEL=".$plantel;

        foreach ($this->db->query($sql) as $row){
            $result = [
                "matricula" => $row['MATRICULA'],
                "nombre" => $row['NOMBRE'],
                "plantel" => $row['PLANTEL'],
                "curp" => $row['CURP']
            ];
        }

        return $result;
    }

}