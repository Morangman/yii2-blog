<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\Helper;
use app\models\Groups;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\assets\UserAsset;
use yii\filters\AccessControl;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','chpwd','update','delete','view','generate'],
                'rules' => [
                  [
                    'actions' => ['index','create','chpwd','update','delete','view','generate'],
                    'allow' => true,
                    'matchCallback' => function ($rule, $action) {
                      return ((Yii::$app->user->identity === NULL)? 
                        false : Yii::$app->user->identity->inGroup('root'));
                    }
                  ],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        UserAsset::register(Yii::$app->view);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        UserAsset::register(Yii::$app->view);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Random users generator in count = $cnt
     * If generation was successful, will be redirected to 'index' page
     * @param integer $cnt
     * @return mixed
     */
    public function actionGenerate($cnt,$trunc)
    {
        $alph = ['a','b','c','d','e','f','g','h','i','j',
        'k','l','m','n','o','p','q','r','s','t',
        'u','v','w','x','y','z'];
        $time_start = microtime(true); 
        $succ = false;
        if ($trunc){
          Yii::$app->db->createCommand()->truncateTable('users')->execute();
        }
        $p = 0;
        for ($i = 0, $k=0; $i < $cnt && $k < 1000000; $i++,$k++){
          $model = new Users();
          $model->scenario = "checkpwd";
          if ($i==0){
            $model->name = 'root';
            $model->pwd = 'root';
            $model->pwd2 = 'root';
            $model->descr = 'root user';
            if ($model->save()){
              $p++;
            } else { $cnt++;}
          } else {
            $name = '';
            for ($j = 0; $j < rand(4,32); $j++){
              $s = $alph[rand(0,count($alph)-1)];
              if (rand(0,3)==2){
                $s = strval(rand(0,9));
              } elseif (rand(0,3) == 1){
                $s = strtoupper($s);
              } elseif (rand(0,5) == 3) {
                $s = "_";
              } else {
                $s = $s;
              }
              $name .= $s;
            }
            $model->name = $name;
            $model->pwd = $name.'_pwd';
            $model->pwd2 = $name.'_pwd';
            $model->descr = 'Now we get user "'.$name.'"';
            if (!$model->save()){
              $i = $i - 1;
            } else { $p++; }
          }
          unset($model);
        }
        $time_end = microtime(true);
        return json_encode(['cnt' => $p, 'time' => ($time_end - $time_start)]);
    }
    
    /**
     * User password changer
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionChpwd($id)
    {
        $model = $this->findModel($id);
        $model->pwd = "";
        $model->pwd2 = "";
        $model->scenario = "checkpwd";
        $loaded = $model->load(Yii::$app->request->post());
        if ( (Yii::$app->request->isAjax ) && $loaded ) {
          $model->validate();
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
        }
        if ($loaded && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
          UserAsset::register(Yii::$app->view);
          return $this->render('chpwd', [
              'model' => $model,
          ]);
        }
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $model->selected_groups = [];
        $model->scenario = "checkpwd";
        if ((Yii::$app->request->isAjax ) 
              && $model->load(Yii::$app->request->post())) {
          $model->validate();
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) 
              && ($model->selected_groups = Yii::$app->request->post()['Users']['selected_groups']) 
              && $model->save()) {
            return $this->redirect(['index', 'UsersSearch[id]' => $model->id]);
        } else {
            UserAsset::register(Yii::$app->view);
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $loaded = $model->load(Yii::$app->request->post());
        if ($loaded){
          Helper::dates2ansi($model);
          $model->selected_groups = Yii::$app->request->post()['Users']['selected_groups'];
        }
        if ( (Yii::$app->request->isAjax ) && $loaded ) {
          $model->validate();
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
        }
        $model->pwd = null;
        if ($loaded && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
          Helper::ansi2dates($model);
          UserAsset::register(Yii::$app->view);
          return $this->render('update', [
              'model' => $model,
          ]);
        }
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            $model->userGroups();
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
