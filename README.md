Toggle Icon Column for CGridView
==========================

CRUD actions collection for Yii controllers

Readme
------------

[README RUS](http://www.elisdn.ru/blog/24)

Installation
------------

Extract to `protected/components`.

Usage example
-------------

~~~
[php]
Yii::import('application.components.crud.*');

class PostController extends Controller
{
    public function actions()
    {
        return array(
            'index'=>'DIndexAction',
            'admin'=>'DAdminAction',
            'create'=>'DCreateAction',
            'update'=>'DUpdateAction',
            'toggle'=>'DToggleAction',
            'delete'=>'DDeleteAction',
            'view'=>'DViewAction',
        );
    }

    public function getIndexProviderModel()
    {
        return Post::model()->published();
    }

    public function createModel()
    {
        $model = new Post;
        $model->date = date('Y-m-d H:i:s');
        return $model;
    }

    public function loadModel($id)
    {
        $model = Post::model()->findByPk($id);
        if($model === null)
            throw new CHttpException(404, 'Страница не найдена');
        return $model;
    }    
    
    public function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='blog-post-form'){
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
~~~

See [DToggleColumn](https://github.com/ElisDN/zii-toggle-column) for samples of `actionToggle($id, $attribute)` using.

Needle controller methods:

~~~
[php]
// Delete, Toggle, Update, View 
public function loadModel($id){...}

// Admin, Create 
public function createModel(){...}

// Index
public function getIndexProviderModel(){...}
~~~

Optional controller methods:

~~~
[php]
// Create, Update
public function performAjaxValidation($model){...}
~~~

Optional callbacks:

~~~
[php]
// Create
public function beforeCreate($model){...}

// Update
public function beforeUpdate($model){...}

// Toggle
public function beforeToggle($model){...}

// Delete
public function beforeDelete($model){...}
~~~

Access checking in callback:

~~~
[php]
Yii::import('application.components.crud.*');

class PostController extends Controller
{
    public function actions()
    {
        return array(
            'index'=>'DIndexAction',
            'admin'=>'DAdminAction',
            'create'=>'DCreateAction',
            'update'=>'DUpdateAction',
            'toggle'=>'DToggleAction',
            'delete'=>'DDeleteAction',
            'view'=>'DViewAction',
        );
    }
    
    public function beforeDelete($model)
    {        
        if (!$this->checkAccess($model))
            throw new CHttpException(403, 'You can't remove this post!');
    }
    
    protected function checkAccess($model)
    {
        $isAuthor = $model->author_id == Yii::app()->user->id;
        $isAdmin = Yii::app()->user->checkAccess(User::ROLE_ADMIN);         
        return $isAuthor || $isAdmin;        
    }

    // ...
}
~~~

Configure sample:

~~~
[php]
class PostAdminController extends Controller
{
    public function actions()
    {
        return array(
            // rename actionAdmin to actionIndex in backend
            // and set different view fot Ajax updating of CGridView
            'index'=>array(
                'class'=>'DAdminAction',
                'view'=>'index',
                'ajaxView'=>'_grid'
            ),
            'update'=>'DUpdateAction',
            'toggle'=>array(
                'class'=>'DToggleAction',
                'attributes'=>array('public', 'popular')
            ),
            'delete'=>'DDeleteAction',
            // Allow getting JSON if $_GET['json'] is setted 
            'view'=>array(
                'class'=>'DViewAction',
                'json'=>true
            )
        );
    }
    
    // ...
}
~~~

All attributes you can see in sourse code of any class.

You can use any from this actions or can create your actions in your controller.

Actions Create, Toggle and Update redirects to actionView on success instead of calling `CController::refresh()`. If you will not use view, create mock view.php file with redirecting:

~~~
[php]
<!-- Redirect to frontend view page -->
<?php $this->redirect($model->getUrl()); ?> 
~~~

or

~~~
[php]
<!-- Come back to form -->
<?php $this->redirect('update', array('id'=>$model->id)); ?> 
~~~
