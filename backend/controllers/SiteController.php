<?php

namespace backend\controllers;

use backend\models\AppleSearch;
use common\models\apple\Apple;
use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use backend\models\PollsSearch;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'login',
                    'error',
                    'logout',
                    'index',
                    'eat',
                    'fall',
                    'create'
                ],
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'eat',
                            'fall',
                            'create'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AppleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): Response
    {
        try {
            for ($i = 0; $i < rand(2, 5); $i++) {
                $apple = new Apple();
                $apple->save();
            }
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    public function actionEat(): Response
    {
        try {
            $id = Yii::$app->request->get('id');
            $eatPercent = Yii::$app->request->get('eaten_percent');
            if (!$model = Apple::findOne($id)) {
                throw new NotFoundHttpException('Яблоко не найдено');
            }
            $model->eat($eatPercent);
            $model->save();
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    public function actionFall(): Response
    {
        try {
            $id = Yii::$app->request->get('id');
            if (!$model = Apple::findOne($id)) {
                throw new NotFoundHttpException('Яблоко не найдено');
            }
            $model->fall();
            $model->save();
            return $this->redirect(['index']);
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
