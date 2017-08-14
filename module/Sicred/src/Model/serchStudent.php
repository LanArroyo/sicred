<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 26/06/2017
 * Time: 9:29
 */

namespace Sicred\Model;

use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Driver;


class serchStudent
{
    protected $db;

    public function getStudent($matricula){
        self::Connection();
        $sql="select ALUM.MATRICULA, ALUM.NOMBRE, ALUM.PATERNO, ALUM.MATERNO, ALUM.PLANTEL, ALUM.CURP, ALUM.GRUPO, ALUM.FOLIO, STU.VALIDATION_DATE, STU.STATUS FROM PRIMER_INGRESO.ALUMNOS_INSCRITOS ALUM, PRIMER_INGRESO.STUDENT_REGISTERED STU WHERE ALUM.MATRICULA = STU.MATRICULA AND ALUM.MATRICULA ='".$matricula."'";

        try{
            $alum = null;
            $query = $this->db->query($sql);
            while($row = $query->fetch()){
                $alum = [
                    "matricula" => $row[0],
                    "nombre" => $row[1],
                    "apellidos" => $row[2]." ".$row[3],
                    "plantel" => $row[4],
                    "curp" => $row[5],
                    "grupo" => $row[6],
                    "img" => $row[7].".jpg",
                    "validation" => $row[8],
                    "status" => $row[9]
                ];
            }
            return $alum;

        }catch (PDOException $e){
            return $alum;
        }
    }

    public function updateStudent($matricula, $typeuser){
        if($matricula != ''){
            self::Connection();
            $sqlupdate =null;
            $sql = "SELECT STATUS FROM PRIMER_INGRESO.STUDENT_REGISTERED WHERE MATRICULA='".$matricula."'";
            $query = $this->db->query($sql);
            $row = $query->fetch();

            //verificamos que la credencial es una reimpresion
            if($row[0] == 3){ //que se envio una solicitud de reimpresion
                $this->db->beginTransaction();
                //update

                //status = 2 (reimpresa y validada)
                $sqlupdate = "UPDATE PRIMER_INGRESO.STUDENT_REGISTERED SET VALIDATION_DATE=SYSDATE, STATUS=2, MATRICULA_EMP='".$_SESSION['user']."' WHERE MATRICULA='".$matricula."'";
                try{
                    $update = $this->db->prepare($sqlupdate);
                    $update->execute();
                    $this->db->commit();

                }catch (PDOException $e){
                    echo "Error: ".$e->getMessage();
                }

            }else{
                $this->db->beginTransaction();

                //update
                if($typeuser == 1){
                    $sqlupdate = "UPDATE PRIMER_INGRESO.STUDENT_REGISTERED SET VALIDATION_DATE=SYSDATE, STATUS=1, MATRICULA_EMP='".$_SESSION['user']."' WHERE MATRICULA='".$matricula."'";
                }else{
                    $sqlupdate = "UPDATE PRIMER_INGRESO.STUDENT_REGISTERED SET DELIVER_DATE=SYSDATE, MATRICULA_EMP='".$_SESSION['user']."' WHERE MATRICULA='".$matricula."'";
                }

                try{
                    $update = $this->db->prepare($sqlupdate);
                    $update->execute();
                    $this->db->commit();

                }catch (PDOException $e){
                    echo "Error: ".$e->getMessage();
                }

            }

        }
    }

    public function enviarReimpresion($matricula){
        self::Connection();

        $this->db->beginTransaction();
        //update

        //status = 3 (reimpresion)
        $sql = "UPDATE PRIMER_INGRESO.STUDENT_REGISTERED SET VALIDATION_DATE=null, STATUS=3, MATRICULA_EMP='".$_SESSION['user_active']."' WHERE MATRICULA='".$matricula."'";
        try{
            $update = $this->db->prepare($sql);
            $update->execute();
            $this->db->commit();

        }catch (PDOException $e){
            echo "Error: ".$e->getMessage();
        }

    }

    private function Connection(){
        $conn = null;
        try{
            $conn = new \PDO("oci:host=10.100.30.76;dbname=ORACRED;port=1523", "ADMPORTAL","CBUSRADMORA11");
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){ ?>
            <script>console.log("ERROR: "' . <?php $e->getMessage()?> .') </script>
        <?php }
        $this->db = $conn;
    }

}