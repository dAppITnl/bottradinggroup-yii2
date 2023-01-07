<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\LoginForm2fa;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

use common\models\ResendVerificationEmailForm; // from frontend also here
use common\models\VerifyEmailForm; // from frontend also here
use common\models\PasswordResetRequestForm;
use common\models\ResetPasswordForm;
use common\helpers\GeneralHelper;

use backend\models\Membership;

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
    $access = GeneralHelper::checkSiteAccess();
    Yii::trace('** behavior SiteController: '.print_r($access, true));
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'actions' => ['login', 'error', 'check42fa', 'resend-verification-email', 'verify-email', 'request-password-reset', 'reset-password'],
            'allow' => true,
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
					[
						//'actions' => ['cssfileform', 'getcssdata', 'viewfileform', 'getfiledata'],
						'allow' => ($access['backend'] == 'true'),
						'roles' => ['@'],  // @ Allow authenticated/loged in users
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

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
		Yii::trace('** Actions -> error');
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
		Yii::trace('** Index post: '.print_r($_POST, true));
		$adminMembershipData = Membership::getAdminMembershipData();
		$statisticReportData = [];
		$yearmonth = (!empty($_POST) ? $_POST['yearmonth'] : date('Ym'));
		$statisticReportData['report'] = Membership::getStatisticReportData( $yearmonth );
    return $this->render('index', [
			'yearmonth' => $yearmonth,
			'membershipData' => $adminMembershipData,
			'statisticReportData' => $statisticReportData,
		]);
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
   * Logout action.
   *
   * @return Response
   */
  public function actionLogout()
  {
    Yii::$app->user->logout();
    return $this->goHome();
  }

// ------------------------------- extra

  /**
   * Displays cssfile edit page.
   *
   * @return string
   */
  public function actionCssfileform()
  {
		$csstheme = $cssdata = '';
		Yii::trace('** actionCssfileform POST:'.print_r($_POST,true));
		if (!empty($_POST) && !empty($_POST['csstheme']) && !empty($_POST['cssdata'])) {
			$csstheme = $_POST['csstheme'];
			$cssdata = $_POST['cssdata'];
			$cssfile = Yii::getAlias('@cssthemes') . '/' . GeneralHelper::createCssFilename($csstheme);
			file_put_contents($cssfile, $cssdata);
		}
		Yii::trace('** actionCssfileform csstheme:'.$csstheme);
    return $this->render('cssfileform', ['csstheme' => $csstheme, 'cssdata' => $cssdata]);
  }

	public function actionGetcssdata()
	{
		$cssdata = '';
		if (!empty($_POST) && !empty($_POST['csstheme'])) {
			$csstheme = $_POST['csstheme'];
			$cssfile = Yii::getAlias('@cssthemes') . '/' . GeneralHelper::createCssFilename($csstheme);
			$cssdata = file_get_contents($cssfile);
		}
		return json_encode(['cssdata' => $cssdata]);
	}

// ---------

	/**
   * Displays frontend View file to edit page.
   *
   * @return string
   */
  public function actionViewfileform()
  {
    $viewfile = $filedata = '';
    Yii::trace('** actionViewfileform POST:'.print_r($_POST,true));
    if (!empty($_POST) && !empty($_POST['viewfile']) && !empty($_POST['filedata'])) {
      $viewfile = $_POST['viewfile'];
      $filedata = $_POST['filedata'];
      $file = Yii::getAlias('@frontendViews') . '/' . $viewfile . '.php';
      file_put_contents($file, $filedata);
    }
    Yii::trace('** actionViewfileform file:'.$viewfile);
    return $this->render('viewfileform', ['viewfile' => $viewfile, 'filedata' => $filedata]);
  }

  public function actionGetfiledata()
  {
    $filedata = '';
		Yii::trace('** actionGetfiledata POST: '.print_r($_POST,true));
    if (!empty($_POST) && !empty($_POST['viewfile'])) {
			$viewfile = $_POST['viewfile'] . '.php';
      $file = Yii::getAlias('@frontendViews') .'/'. $viewfile;
			$backupfile = Yii::getAlias('@frontendViewsBackup') .'/'. $viewfile .'-'. date('siHdmy');
			//Yii::trace('** actionGetfiledata viewfile='.$viewfile.' file='.$file.' bufile='.$backupfile.' basename='.dirname($backupfile));
			// safety backup
			if (!file_exists(dirname($backupfile))) mkdir( dirname($backupfile), 0775, true);
			copy($file, Yii::getAlias('@frontendViewsBackup') .'/'. $viewfile .'-'. date('siHdmy'));
			//
      $filedata = file_get_contents($file);
    }
    return json_encode(['filedata' => $filedata]);
  }

// ------------------------------- from frontend..

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
        if (($user=$model->verifyEmail()) && !is_null($user) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Your email has (already) been confirmed!'));
        } else {
		        Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to verify your account with provided token.'));
				}
        return $this->goHome();
    }

    /**
     * Resend verification email (from frontend also here)
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
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

// -----------------------------
}
