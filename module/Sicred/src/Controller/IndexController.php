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
        return new ViewModel();
    }

    public function serchAction()
    {
        $resultSerch = null;
        if ($this->getRequest()->isPost()){
            $data = $this->request->getPost();
            $serch = new Model\serchStudent();
            $resultSerch = $serch->getStudent($data->serch,$data->plantel);
            if(isset($resultSerch)){
                return new ViewModel(["resultSerch"=>$resultSerch]);
            }else{
                return new ViewModel(["resultSerch"=>$data]);
            }
        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'sicred/index');
        }
    }
}
