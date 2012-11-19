<?php
/**
 * @author ElisDN <mail@elisdn.ru>
 * @link http://www.elisdn.ru
 */

class DToggleAction extends DCrudAction
{
    /**
     * @var string success message
     */
    public $success = 'Changed successfully';
    /**
     * @var string error message
     */
    public $error = 'Error';
    /**
     * @var mixed toggable attributes
     */
    public $attributes = array('public');

    public function run()
    {
        $this->checkIsPostRequest();

        $attribute = $this->getAttribute();

        $model = $this->loadModel();

        $this->clientCallback('beforeToggle', $model);

        $model->$attribute = $model->$attribute ? 0 : 1;

        if ($model->save())
            $this->success($this->success);
        else
            $this->error($this->error);

        $this->redirectToView($model);
    }

    protected function getAttribute()
    {
        if (empty($this->attributes))
            throw new CHttpException(400, 'Не указаны атрибуты для переключения');

        $attribute = Yii::app()->request->getParam('attribute');

        if (!in_array($attribute, $this->attributes))
            throw new CHttpException(400, 'Некорректный атрибут');

        return $attribute;
    }
}
