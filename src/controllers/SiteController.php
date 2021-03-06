<?php

namespace ronashdkl\kodCms\controllers;

use ronashdkl\kodCms\actions\ErrorAction;
use ronashdkl\kodCms\models\service\FaqModel;

use lo\plugins\core\helloworld\AboutPlugin;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;


class SiteController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->appData->registerHomeWidget();

    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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
                'class' => ErrorAction::class,
                'layout' => 'main'
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }
    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
       VarDumper::dump($attributes,10,1);
       die;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderContent(null);
    }


    public function actionUpdate(){
        $commands = array(
            'echo $PWD',
            'whoami',
            'git reset --hard HEAD',
            'git pull',
            'git status',
            'git submodule sync',
            'git submodule update',
            'git submodule status',
        );

        $output = '';
        foreach($commands AS $command){
            // Run it
            $tmp = exec($command);
            // Output
            $output .= "<span style=\"color: #6BE234;\">\$</span> <span style=\"color: #729FCF;\">{$command}\n</span>";
            $output .= htmlentities(trim($tmp)) . "\n";
        }
        return $output;
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
