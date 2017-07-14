<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 11/07/2017
 * Time: 12:50
 */
namespace sicred;

use ZendXml\Security;


/**
 * Verificar como enviar los datos principales (matricula, plantel, nombre)
 * NO utilizar un metodo POST
 * ++checar la ruta del archivo (si se puede hacer desde Data o se deja en public)
 */
$matricula = $_POST['inp-matricula'];
$nombre = $_POST['inp-nombre'];
$plantel = $_POST['inp-plantel'];

class agregarCredencial{

    public function __construct($matricula, $nombre ,$plantel){
        $nameFile = "Plantel".$plantel.".xml";
        $document = new \DOMDocument('1.0');
        $document->load('../../data/Plantel/'.$nameFile);
        if($xml = simplexml_load_file($document->documentURI)){
            $this->llenarArchivo($xml, $matricula, $nombre, $plantel);
            $xml->saveXML($document->documentURI);
        }
    }

    public function llenarArchivo(\SimpleXMLElement $xmlCred, $matricula, $nombre, $plantel){
        echo "<script>console.log('estoy en llenar archivo');</script>";
        $find = $this->buscarMatricula($xmlCred, $matricula);
        $date = getdate();

        if($find === false){
            $numElem = $xmlCred->children()->count();
            $xml = $xmlCred->addChild('registro');

            //Agregamos los recursos
            $xml->addAttribute('id', $numElem);
            $xml->addAttribute('matricula', $matricula);
            $xml->addAttribute('nombre', $nombre);
            $xml->addAttribute('numPlantel', $plantel);
            $xml->addAttribute('fechaVer', $date['mday']."/".$date['mon']."/".$date['year']);
        }
    }

    public function buscarMatricula(\SimpleXMLElement $xmlCred, $matricula){
        $result = false;
        foreach ($xmlCred->children() as $child) {
            if($child['matricula'] === $matricula){
                $result = true;
                break;
            }
        }

        return $result;
    }
}

new agregarCredencial($matricula,$nombre,$plantel);