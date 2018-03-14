<?php

namespace app\controllers;

use Yii;
use app\models\Groups;
use app\models\Helper;
use app\models\GroupsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\assets\GroupAsset;
use yii\filters\AccessControl;

/**
 * GroupsController implements the CRUD actions for Groups model.
 */
class GroupsController extends Controller
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
                'only' => ['index','create','update','delete','view'],
                'rules' => [
                  [
                    'actions' => ['index','create','update','delete','view'],
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
     * Lists all Groups models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        GroupAsset::register(Yii::$app->view);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Groups model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        GroupAsset::register(Yii::$app->view);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    

    /**
     * Creates a new Groups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Groups();
        if ((Yii::$app->request->isAjax ) 
              && $model->load(Yii::$app->request->post())) {
          $model->validate();
          Yii::$app->response->format = Response::FORMAT_JSON;
          return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            GroupAsset::register(Yii::$app->view);
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Groups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        Yii::$app->language = 'ru';
        $loaded = $model->load(Yii::$app->request->post());
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
          GroupAsset::register(Yii::$app->view);
          return $this->render('update', [
              'model' => $model,
          ]);
        }
    }

    /**
     * Deletes an existing Groups model.
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
     * Finds the Groups model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Groups the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Groups::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
