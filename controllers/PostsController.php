<?php

namespace app\controllers;

use Yii;
use app\models\Posts;
use app\models\Helper;
use app\models\PostsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\assets\PostAsset;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * PostsController implements the CRUD actions for Posts model.
 */
class PostsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','update','delete','image-upload','image-delete','del-photo'],
                'rules' => [
                    [
                        'actions' => ['create','update','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['image-upload','image-delete','del-photo'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                          return ((Yii::$app->user->identity === NULL)? 
                            false : Yii::$app->user->identity->inOneOfGroups(['root','admins','photos']));
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Posts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        PostAsset::register(Yii::$app->view);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Posts model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    

    /**
     * Creates a new Posts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Posts();
        if ((Yii::$app->request->isAjax ) 
              && $model->load(Yii::$app->request->post())) {
          $model->validate();
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) 
        && ( ((Yii::$app->user->identity) ? ($model->user_id = Yii::$app->user->identity->id):(true)) )
        && $model->save()
      ) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Posts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if( !(Yii::$app->user->identity && Yii::$app->user->identity->inOneOfGroups(['root','admins'])) 
              && $model->user->id !== Yii::$app->user->identity->id){
          throw new HttpException(403 ,'У вас нет доступа');
        }
        Yii::$app->language = 'ru';
        $loaded = $model->load(Yii::$app->request->post());
        //((Yii::$app->user->identity) ? ($model->user_id = Yii::$app->user->identity->id):(true));
        Helper::dates2ansi($model);
        if ( (Yii::$app->request->isAjax ) && $loaded ) {
          $model->validate();
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
        }
        if ($loaded && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
          Helper::ansi2dates($model);
          
          return $this->render('update', [
              'model' => $model,
          ]);
        }
    }

    /**
     * Deletes an existing Posts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if( !(Yii::$app->user->identity && Yii::$app->user->identity->inOneOfGroups(['root','admins'])) 
              && $model->user->id !== Yii::$app->user->identity->id){
          throw new HttpException(403 ,'У вас нет доступа');
        }
        if ($model->file){
          unlink($_SERVER['DOCUMENT_ROOT']  . $model->file);
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Posts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Posts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Posts::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
  public function actionImageUpload($id)
  {
      $model = $this->findModel($id);

      $imageFile = UploadedFile::getInstance($model, 'img_fl');

      $directory = Yii::getAlias('@app/web/img/temp') . DIRECTORY_SEPARATOR . Yii::$app->session->id . DIRECTORY_SEPARATOR;
      if (!is_dir($directory)) {
          FileHelper::createDirectory($directory);
      }

      if ($imageFile) {
          $uid = uniqid(time(), true);
          $fileName = $uid . '.' . $imageFile->extension;
          $filePath = $directory . $fileName;
          if ($imageFile->saveAs($filePath)) {
              $path = Url::to(['img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName]);
              if ($model->file){
                try {
                unlink($_SERVER['DOCUMENT_ROOT'] . $model->file);
                } catch(yii\base\ErrorException $ex) { $model->file= null; }
              }
              $model->file = $path;
              if (!$model->save()){
                Yii::trace($model->errors);
              }
              return Json::encode([
                  'files' => [
                      [
                          'name' => $fileName,
                          'size' => $imageFile->size,
                          'url' => $path,
                          'thumbnailUrl' => $path,
                          'deleteUrl' => 'image-delete?name=' . $fileName.'&id='.$model->id,
                          'deleteType' => 'POST',
                      ],
                  ],
              ]);
          }
      }

      return '';
  }

  public function actionImageDelete($name,$id)
  {
      $model = $this->findModel($id);
      $model->file = null;
      $model->save();
      $directory = Yii::getAlias('@app/web/img/temp') . DIRECTORY_SEPARATOR . Yii::$app->session->id;
      if (is_file($directory . DIRECTORY_SEPARATOR . $name)) {
          unlink($directory . DIRECTORY_SEPARATOR . $name);
      }

      $files = FileHelper::findFiles($directory);
      $output = [];
      foreach ($files as $file) {
          $fileName = basename($file);
          $path = '/img/temp/' . Yii::$app->session->id . DIRECTORY_SEPARATOR . $fileName;
          $output['files'][] = [
              'name' => $fileName,
              'size' => filesize($file),
              'url' => $path,
              'thumbnailUrl' => $path,
              'deleteUrl' => 'image-delete?name=' . $fileName,
              'deleteType' => 'POST',
          ];
      }
      return Json::encode($output);
  }
  
  public function actionDelPhoto($id)
  {
      $model = $this->findModel($id);
      if ($model->file){
        unlink($_SERVER['DOCUMENT_ROOT']  . $model->file);
        $model->file = null;
        $model->save();
      }
      return $this->redirect(Url::toRoute(['posts/update','id' => $id]));
  }
}
