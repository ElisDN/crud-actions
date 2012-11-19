<?php
/**
 * @author ElisDN <mail@elisdn.ru>
 * @link http://www.elisdn.ru
 */

class DViewAction extends DCrudAction
{
    public $view = 'view';
    public $json = false;

    public function run()
    {
        $model = $this->loadModel();

        $this->clientCallback('beforeView', $model);

        if ($this->json && isset($_GET['json']))
        {
            echo CJSON::encode($model);
        }
        else
        {
            $this->controller->render($this->view, array(
                'model'=>$model,
            ));
        }
    }
}
