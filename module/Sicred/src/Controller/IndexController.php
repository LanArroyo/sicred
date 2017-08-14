<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sicred\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Sicred\Model;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if(!empty($_SESSION)){
            return new ViewModel();
        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/login');
        }
    }

    public function loginAction()
    {
        if($this->getRequest()->isPost()){
            $data = $this->request->getPost();
            $login = new Model\login();
            $result = $login->startSession($data->mat_empleado, $data->pwd_empleado);

            if($result == 1){
                //ingreso correctamente
                return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/revisioncred');

            }elseif ($result == -1){
                $msj = ["error"=>"El usuario no existe, verifique que su matricula es la correcta"];
                $css=["sty" => "display:block"];
                return new ViewModel(["msj"=>$msj, "css"=>$css]);
            }elseif ($result == -2){
                $msj = ["error"=>"Contraseña incorrecta"];
                $css=["sty" => "display:none"];
                return new ViewModel(["msj"=>$msj, "css"=>$css]);
            }
        }else{
            $msj = "";
            $css=["sty" => "display:none"];
            return new ViewModel(["msj"=>$msj, "css"=>$css]);
        }
    }

    public function revisioncredAction(){
        session_start();
        if(!empty($_SESSION)){
            return new ViewModel();
        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/login');
        }
    }

    public function serchAction(){
        session_start();
        if(!empty($_SESSION)){
            if ($this->getRequest()->isPost()){
                $resultSerch = null;
                $css = null;
                $data = $this->request->getPost();
                $serch = new Model\serchStudent();
                $resultSerch = $serch->getStudent($data->serch);
                if(isset($resultSerch)){
                    //se actualizan las variables de session
                    if($data->status == 1){
                        $_SESSION['aprobmat'] = $data->beforemat;
                        $_SESSION['pendmat'] = $resultSerch["matricula"];
                        $serch->updateStudent($_SESSION['aprobmat'], $_SESSION['typeuser'] );
                        $css=["sty" => "display:none"];
                    }elseif ($data->status == 2){
                        $_SESSION['aprobmat'] = null;
                        $_SESSION['pendmat'] = $resultSerch["matricula"];
                        $css=["sty" => "display:block"];
                    }
                    return new ViewModel(["resultSerch"=>$resultSerch, "css"=>$css]);
                }else{
                    $resultSerch =["validation"=>null];
                    $css=["sty" => "display:none"];
                    return new ViewModel(["data"=>$data, "css"=>$css, "resultSerch"=>$resultSerch]);
                }
            }else{
                return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/revisioncred');
            }

        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/login');
        }
    }

    public function reporterrorAction(){
        session_start();
        if(!empty($_SESSION)){
            if($this->getRequest()->isPost()){
                $data = $this->request->getPost();
                $serch = new Model\serchStudent();
                $serch->enviarReimpresion($_SESSION['pendmat']);
                return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/revisioncred');
            }else{
                return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/index');
            }

        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/login');
        }

    }

    public function logoutAction(){
        session_start();
        $logout = new Model\logout();
        $logout->closeSession();
        return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/login');
    }

    public function ordenarcredAction(){
        session_start();
        if(!empty($_SESSION)){
            $order = new Model\ordenarcred();
            $fechascorte = $order->fechascorte();
            if($this->request->isPost()){
                $band = 2;
                $data = $this->request->getPost();
                $lista = $order->generarlista($data->plantel,$data->grupo,$data->fechacorte);
                return new ViewModel(["band" => $band,"lista"=>$lista,"data"=>$data,"fechascorte"=>$fechascorte]);
            }else{
                $band = 1;
                return new ViewModel(["band" => $band, "fechascorte"=>$fechascorte]);
            }
        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'/sicred/login');
        }
    }
}
