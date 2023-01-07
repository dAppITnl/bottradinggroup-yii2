<?php

namespace frontend\controllers;

//use frontend\models\ResendVerificationEmailForm;
//use frontend\models\VerifyEmailForm;
//use frontend\models\PasswordResetRequestForm;
//use frontend\models\ResetPasswordForm;
use common\models\ResendVerificationEmailForm;
use common\models\VerifyEmailForm;
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\Usermember;
use common\helpers\GeneralHelper;

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
		//$access = GeneralHelper::checkSiteAccess();
		//Yii::trace('** behavior BotsignalController: '.print_r($access, true));
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'signup', 'getdiscorddata'],
				'rules' => [
					[
						'actions' => ['signup', 'getdiscorddata'],
						'allow' => true,
						'roles' => ['?'], // not logged in
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'], // logged in
					],
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

	/*public function rules()
	{
		return [
			[['premium',], 'required'],
		]
	}*/

	/**
	 * {@inheritdoc}
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		if (Yii::$app->user->isGuest || (\Yii::$app->user->id == 0)) {
			return $this->render('index-notloggedin');
		} else {
			Yii::trace('** index id='.\Yii::$app->user->id);
			$usermembersModel = Usermember::getUsermembersOfUser(\Yii::$app->user->id, false);
			Yii::trace('** index usermemebrsModel: count='. count($usermembersModel));
			return $this->render('index-loggedin', ['usermembersModel' => $usermembersModel]);
		}
	}

  /**
   * Displays disclaimer.
   *
   * @return mixed
   */
  public function actionDisclaimer()
  {
		return $this->render('disclaimer');
  }

  /**
   * Displays membership-terms.
   *
   * @return mixed
   */
  public function actionMembershipterms()
  {
    return $this->render('membershipterms');
  }


	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goHome(); //$this->goBack();
		} else {
			Yii::trace('** actionLogin ERROR: '.print_r(Yii::$app->request->post(),true));
			Yii::trace('** actionLogin ERROR: '.print_r($model->errors,true));
		}

		$model->password = '';

		return $this->render('login', [
			'model' => $model,
		]);
	}

  public function actionCheck42fa()
  {
    Yii::trace('** ActionCheck2fa.. POST='.print_r($_POST,true));
    if (!Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $model = new LoginForm();
    $model->username = (!empty($_POST['username']) ? $_POST['username'] : '');
    return json_encode(['check42fa' => ($model->check42fa() ? 'true' : 'false')]);
  }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['contactEmail'])) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'There was an error sending your message.'));
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays faq page.
     *
     * @return mixed
     */
    public function actionFaq()
    {
        return $this->render('faq');
    }


    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Thank you for registration. Please check your inbox for verification email.'));
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

		public function actionGetdiscorddata()
		{
			$result = [];
			Yii::trace('** actionGetdiscorddata POST: '.print_r($_POST, true));
			if (!empty($_POST['id']) || !empty($_POST['username'])) {
				$result = GeneralHelper::getDiscordMember( $_POST['id'], $_POST['username'] );
			}
			Yii::trace('** actionGetdiscorddata result: '.print_r($result, true));
			return $result;
		}

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.'));
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'New password saved.'));

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your email has been confirmed!'));
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to verify your account with provided token.'));
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
				//Yii::trace('** actionResendVerificationEmail POST: '.print_r($_POST,true));
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to resend verification email for the provided email address.'));
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
