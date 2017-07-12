<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 11/07/2017
 * Time: 12:50
 */
$nombre = $_POST['matricula'];
$plantel = $_POST['nombre'];
$plantel = $_POST['plantel'];
class agregarCredencial{

    public function __construct($matricula, $nombre ,$plantel){
        $nameFile = "Plantel".$plantel.".xml";
        $document = new DOMDocument();
        $document->load('../../../../../data/Plantel/'.$nameFile);
        if($xml = simplexml_load_file($document->documentURI)){
            $this->llenarArchivo($xml, $matricula, $nombre, $plantel);
            echo "<script>console.log('se cargo el archivo');</script>";
            $xml->saveXML($document->documentURI);
        }else{
            echo "<script>console.log('se creo el archivo');</script>";
        }
        //$this->llenarArchivo($xml, $nombre, $plantel);

        //$xml->saveXML($document->documentURI);

    }

    public function llenarArchivo(SimpleXMLElement $xmlCred, $matricula, $nombre, $plantel){
        echo "<script>console.log('estoy en llenar archivo');</script>";
        $find = $this->buscarMatricula($xmlCred, $matricula);
        $date = getdate();

        if($find === false){
            $numElem = $xmlCred->count();
            $xml = $xmlCred->addChild('registro');

            //Agregamos los recursos
            $xml->addAttribute('id', $numElem-1);
            $xml->addAttribute('matricula', $matricula);
            $xml->addAttribute('nombre', $nombre);
            $xml->addAttribute('numPlantel', $plantel);
            $xml->addAttribute('fechaVer', $date['mday']."/".$date['mon']."/".$date['year']);
        }
    }

    public function buscarMatricula(SimpleXMLElement $xmlCred, $matricula){
        $result = true;
        foreach ($xmlCred->children() as $child) {
            if($child['matricula'] === $matricula){
                $result = false;
                break;
            }
        }

        return $result;
    }
}

new agregarCredencial($matricula,$nombre,$plantel);