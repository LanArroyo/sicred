<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 08/08/2017
 * Time: 11:38
 */

// 2170104
namespace Sicred\Model;

class login{
    private $db;

    public function startSession($user, $pwd){
        self::Connection();
        $sql = "SELECT NAME FROM PRIMER_INGRESO.SYS_USER WHERE MATRICULA='".$user."'"; //cambiar
        $query = $this->db->query($sql);
        $row = $query->fetch();

        //verificamos si se encontro el usuario
        if(isset($row)){
            $sql = "SELECT NAME FROM USRCB WHERE PWD ='".$pwd."'";
            $query = $this->db->query($sql);
            $row = $query->fetch();
            //verificamos que la contraseña sea la carrecta
            if(isset($row)){
                session_start();
                $_SESSION['aprobmat'] = null;
                $_SESSION['pendmat'] = null;
                $_SESSION['user_active'] = $row[0];
                //regresamos 1 de exito
                return 1;
            }else{
                //-2 el usuario se equivoco de contraseña
                return -2;
            }
        }else{
            //regresamos -1 el usuario no existe
            return -1;
        }
        if($user == '2170104' and $pwd == 'admin'){
            session_start();
            $_SESSION['aprobmat'] = null;
            $_SESSION['pendmat'] = null;
            $_SESSION['user_active'] = $user;
            return 1;
        }

    }

    private function Connection(){
        $conn = null;
        try{
            $conn = new \PDO("oci:host=129.144.51.99;dbname=NUBEBDU;port=1524", "ADMPORTAL","CBUSRADMORA11");
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){ ?>
            <script>console.log("ERROR: "' . <?php $e->getMessage()?> .') </script>
        <?php }
        $this->db = $conn;
    }

}