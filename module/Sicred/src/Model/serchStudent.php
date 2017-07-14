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
    protected $data;

    public function getStudent($matricula, $plantel){
        self::Connection();
        $data = null;
        $sql = "SELECT MATRICULA, NMBR, APLLD_MTRN, CURP FROM USRCB.USUARIO WHERE MATRICULA='".$matricula."'";
        try{
            $query = $this->db->query($sql);
            $result = $query->setFetchMode(\PDO::FETCH_NUM);
            while($row = $query->fetch()){
                $this->data = [
                    "matricula" => $row[0],
                    "nombre" => $row[1],
                    "plantel" => "1",
                    "curp" => $row[3],
                    //"img" => $img
                ];
            }
            return $this->data;
        }catch (PDOException $e){
            return $this->data;
        }
    }


    private function Connection(){
        $conn = null;
        try{
            $conn = new \PDO("oci:host=10.100.30.26;dbname=BD;port=1521", "ADMBDCOLBACH","DBABDCOLBACH");
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){ ?>
            <script>console.log("ERROR: "' . <?php $e->getMessage()?> .') </script>
        <?php }
        $this->db = $conn;
    }

}