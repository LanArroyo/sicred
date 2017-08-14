<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 08/08/2017
 * Time: 11:38
 */

// 2170104
namespace Sicred\Model;

use Doctrine\DBAL\Driver\PDOException;

class login{
    private $db;

    public function startSession($user, $pwd){
        self::Connection();
        $sql = "SELECT PASSWORD, CT_USER FROM PRIMER_INGRESO.SYS_USER WHERE MATRICULA='".$user."'"; //cambiar
        try{
            $query = $this->db->query($sql);
            $row = $query->fetch();

            //verificamos si se encontro el usuario
            if(isset($row)){
                if($row[0] == $pwd){
                    session_start();
                    $_SESSION['aprobmat'] = null;
                    $_SESSION['pendmat'] = null;
                    $_SESSION['user'] = $user;
                    $_SESSION['typeuser'] = $row[1];
                    return 1;
                }else{
                    //regresamos -1 el usuario se equivoco de contraseña
                    return -1;
                }
            }else{
                //no existe el usuario
                return -2;
            }

        }catch (PDOException $e){

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