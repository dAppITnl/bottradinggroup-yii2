<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use yii\data\ActiveDataProvider;
use common\helpers\GeneralHelper;

/**
 * Signal controller
 */
class SignalController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $access = GeneralHelper::checkSiteAccess();
        Yii::trace('** behavior BotsignalController: '.print_r($access, true));
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout', 'signup'],
                'rules' => [
                    /*[
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],*/
                    [
                        'allow' => ($access['frontend'] == 'true'),
                        'roles' => ['@'],  // Allow authenticated/loged in users
                    ],
                    // anybody else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
                'class' => 'yii\web\ErrorAction',
            ],
            /*'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
				//$signals = GeneralHelper::getCategorySignalsForUserBotsignal(Yii::$app->user->identity->usr_language);
        return $this->render('index', [/*'signals' => $signals*/]);
    }

    /**
     * Displays assigned signals.
     *
     * @return mixed
     */
    public function actionAssigned()
    {
        //$signals = GeneralHelper::getCategorySignalsForUserBotsignal(Yii::$app->user->identity->usr_language);
        return $this->render('assigned', [/*'signals' => $signals*/]);
    }

	public function actionGetmembershipsignals($id)
	{
		$result = [];
		if (!empty($id)) {
			$categorySignals = GeneralHelper::getCategorySignalsForUserBotsignal(Yii::$app->user->identity->usr_language, $id);
			if (!empty($categorySignals)) {
				foreach($categorySignals as $key => $value) {
					if (is_array($value)) {
						$result[] = ['value'=>-1, 'text'=>$key];
						foreach($value as $id => $text) {
							$result[] = ['value'=>$id, 'text'=>$text];
						}
					} else {
						$result[] = ['value'=>$key, 'text'=>$value];
					}
				}
			}
		}
		Yii::trace('** actionGetmembershipsignals result: '.print_r($result,true));
		return json_encode($result);
	}

	public function actionGetsignal($id)
	{
		$result = [];
		if (!empty($id)) {
			$result = GeneralHelper::getSignalDetails($id);
		}
		Yii::trace('** actionGetsignal result: '.print_r($result, true));
		return json_encode($result);
	}

}
