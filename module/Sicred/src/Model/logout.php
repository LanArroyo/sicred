<?php
/**
 * Created by PhpStorm.
 * User: colbach
 * Date: 08/08/2017
 * Time: 12:42
 */
namespace Sicred\Model;

use Sicred\Model;
class logout{

    public function closeSession(){
        session_start();
        if(isset($_SESSION['pendmat'])){
            $serch = new Model\serchStudent();
            $serch->updateStudent($_SESSION['pendmat']);
            session_unset();
            session_destroy();
        }else{
            session_destroy();
        }
    }


}