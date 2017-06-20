<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sicred\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function serchAction()
    {
        if ($this->getRequest()->isPost()){
            $data = $this->request->getPost();
            return new ViewModel(["titulo"=>"Recibi los datos!!", "data"=>$data]);
        }else{
            return $this->redirect()->toUrl($this->getRequest()->getBaseURL().'sicred/index');
        }
    }
}
