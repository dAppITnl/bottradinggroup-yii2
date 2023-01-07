<?php

namespace apiv1\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

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
        'class' => AccessControl::className(),
        'rules' => [
          /*[
            'actions' => ['login', 'error'],
            'allow' => true,
          ],
          [
            'actions' => ['logout', 'index'],
            'allow' => true,
            'roles' => ['@'],
          ],*/
          [
            'allow' => true,
            //'roles' => ['@'],  // Allow authenticated/loged in users
          ],
				],
      ],
      /*'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['post'],
        ],
      ],*/
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect('https://bottradinggroup.nl');
    }

}
