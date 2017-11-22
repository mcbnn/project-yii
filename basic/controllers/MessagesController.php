<?php

namespace app\controllers;

use Yii;
use app\models\Messages;
use app\models\Answers;
use app\models\Status;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * MessagesController implements the CRUD actions for Messages model.
 */
class MessagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];

    }

    /**
     * Lists all Messages models.
     * @return mixed
     */
    public function actionIndex()
    {

        $user_id = Yii::$app->getUser()->getId();
        $role = Yii::$app->authManager->getRolesByUser($user_id);

        if(!$this->getAccessMessage()){

            return $this->redirect('/site/index');
        }


        if(isset($role['admin'])){
            $dataProvider = new ActiveDataProvider([
                'query' => Messages::find(),
            ]);
        }
        else{
            $dataProvider = new ActiveDataProvider([
                'query' => Messages::find()->where('user_id = :user_id', ['user_id' => $user_id]),
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Messages model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(!$this->getAccessMessage($id)){
            return $this->redirect('/site/index');
        }

        $modelMessage = $this->findModel($id);

        $modelAnswer = new Answers();

        $modelAnswer->user_id = Yii::$app->getUser()->getId();
        $modelAnswer->message_id = $id;

        if ($modelAnswer->load(Yii::$app->request->post()) && $modelAnswer->save()) {
            $status = Yii::$app->request->post();
            if(isset($status['status'])){
                $message = Messages::findOne(['id' => $id]);
                $message->status_id = $status['status'];
                $message->save();
            }
            return $this->redirect(['messages/view', 'id' => $id]);
        }

        $answers = Answers::findAll(['message_id' => $id]);

        return $this->render('view', [
            'modelMessage'  => $modelMessage,
            'modelAnswer'   => $modelAnswer,
            'answers'       => $answers
        ]);
    }

    /**
     * Creates a new Messages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Messages();
        $model->user_id = Yii::$app->getUser()->getId();
        $model->status_id = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Messages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Messages model.
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
     * Finds the Messages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Messages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
    * @param int $id
    * @return bool
    */
    protected function getAccessMessage($id = 0)
    {
        $user_id = Yii::$app->getUser()->getId();
        $isGuest = Yii::$app->getUser()->isGuest;
        $role = Yii::$app->authManager->getRolesByUser($user_id);

        if(!$id){
            $user_id_messages = 0;
        }
        else{
            $model = $this->findModel($id);
            $user_id_messages = $model->user_id;
        }

        if(isset($role['admin']))return true;
        if($user_id == $user_id_messages)return true;
        if(!$isGuest and $id == 0)return true;
        return false;
    }


    public function getAllStatus()
    {
        $statusObj = Status::find()->all();
        $statusArray = [];
        foreach ($statusObj as $value){
            $statusArray[$value->id] = $value->name;
        }

        return $statusArray;
    }
}
