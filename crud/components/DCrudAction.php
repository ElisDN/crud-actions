<?php
/**
 * @author ElisDN <mail@elisdn.ru>
 * @link http://www.elisdn.ru
 */

class DCrudAction extends CAction
{
    /*
     * @var string $flash key for Yii::app()->user->setFlash($flashSuccess, $message);
     */
    public $flashSuccess = 'message';
    /*
     * @var string $flash key for Yii::app()->user->setFlash($flashError, $message);
     */
    public $flashError = 'message';

    protected function checkIsPostRequest()
    {
        if (!Yii::app()->request->isPostRequest)
            throw new CHttpException (400, 'Bad request');
    }

    protected function clientCallback($method, $model)
    {
        if (method_exists($this->controller, $method))
            $this->controller->$method($model);
    }

    protected function success($message)
    {
        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->user->setFlash($this->flashSuccess, $message);
    }

    protected function error($message)
    {
        if (!Yii::app()->request->isAjaxRequest)
            Yii::app()->user->setFlash($this->flashError, $message);
        else
            throw new CHttpException(400, $message);
    }

    protected function redirectToView($model)
    {
        $this->controller->redirect(array('view', 'id' => $model->getPrimaryKey()));
    }

    protected function redirectToReferrer()
    {
        if (!Yii::app()->request->isAjaxRequest)
            $this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * @return CModel
     */
    protected function createModel()
    {
        $this->checkMethodExists('createModel');
        return $this->controller->createModel();
    }

    /**
     * @return CActiveRecord
     */
    protected function loadModel()
    {
        $this->checkMethodExists('loadModel');
        $id = Yii::app()->request->getParam('id');
        $model = $this->controller->loadModel($id);
        return $model;
    }

    /**
     * @return CActiveRecord
     */
    protected function getIndexProviderModel()
    {
        $this->checkMethodExists('getIndexProviderModel');
        $model = $this->controller->getIndexProviderModel();
        return $model;
    }

    protected function checkMethodExists($method)
    {
        if (!method_exists($this->controller, $method))
            throw new CException("Method CController::{$method}() not found");
    }
}