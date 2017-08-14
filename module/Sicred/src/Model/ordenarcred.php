<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 09/08/2017
 * Time: 8:45
 */
namespace Sicred\Model;

class ordenarcred{
    protected $db;

    public function generarlista($plantel, $grupo, $fechacorte){
        self::Connection();
        //$fecha = self::fechascorte();
        //$grupo = self::grupos();

        $data = array();
        //FILTRO FECHA_CORTE ASC, PLANTEL ASC, GRUPO ASC, PATERNO ASC, MATERNO ASC, NOMBRE ASC
        $sql = "SELECT MATRICULA, PATERNO, MATERNO, NOMBRE, GRUPO, FECHA_CORTE FROM PRIMER_INGRESO.ALUMNOS_INSCRITOS WHERE PLANTEL =".$plantel." AND GRUPO=".$grupo." AND FECHA_CORTE='".$fechacorte."' ORDER BY FECHA_CORTE, GRUPO,PATERNO, MATERNO, NOMBRE";
        try{
            $query = $this->db->query($sql);
            //$row = $query->fetch(PDO::FETCH_NUM);
            while ($row = $query->fetch()){
                array_push($data, [$row[0],$row[1],$row[2],$row[3],$row[4],$row[5]]);
            }
            return $data;
        }catch (PDOException $e){
            return $data;
        }
    }

    public function grupos(){
        self::Connection();
        $sql = "SELECT DISTINCT GRUPO FROM PRIMER_INGRESO.ALUMNOS_INSCRITOS WHERE PLANTEL=1 ORDER BY GRUPO";
        $data = array();
        try{
            $query = $this->db->query($sql);
            while($row = $query->fetch()){
                array_push($data,$row[0]);
            }
            return $data;

        }catch(PDOException $e){
            return $data;
        }
    }

    public function fechascorte(){
        self::Connection();
        $data = array();
        $sql = "SELECT DISTINCT FECHA_CORTE FROM PRIMER_INGRESO.ALUMNOS_INSCRITOS ORDER BY FECHA_CORTE";
        try{
            $query = $this->db->query($sql);
            while($row = $query->fetch()){
                array_push($data,$row[0]);
            }
            return $data;

        }catch(PDOException $e){
            return $data;
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

    public function filtrardatos($datos, $grupos){
        //$lista contendra el listado de los alumnos por grupo
        $listas = array();

        $aux_i=0;
        foreach ($grupos as $grupo){
            $aux = array();
            foreach ($datos as $dato){
                if($datos[$aux_i][4] == $grupo){
                    array_push($aux,$dato);
                    $aux_i++;
                }
            }
            array_push($listas, $aux);
        }
        return $listas;
    }

}