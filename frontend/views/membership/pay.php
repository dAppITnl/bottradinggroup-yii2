<?php

use yii\helpers\Html;

use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use yii\widgets\DetailView;
use frontend\models\Userpay;
use frontend\models\Cryptoaddress;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

//$tiername = Html::encode($usermemberModel->umbmbr->mbr_title);
$mbrid = $usermemberModel->umbmbr_id;
$usrid = $usermemberModel->umbusr_id;
$percode = $usermemberModel->umbprl->prl_percode;
$symid = $usermemberModel->umbprl->prlsym_id;
$symCode = $usermemberModel->umbprl->prlsym->sym_code;
$symHtml = $usermemberModel->umbprl->prlsym->sym_html;
$umPrice = $usermemberModel->umbprl->prl_price;
$prlcadIds = $usermemberModel->umbprl->prlcad_crypto_ids;
Yii::trace('** view pay: umbid='.$usermemberModel->id.' mbrid='.$mbrid.' usrid='.$usrid.' percode='.$percode.' symid='.$symid.' symHtml='.$symHtml.' umPrice='.$umPrice.' prlcadIds='.$prlcadIds);

//$startdate = (!empty($usermemberModel->umb_paystartdate) ? $usermemberModel->umb_startdate : date('Y-m-d'));
//$enddate = (!empty($usermemberModel->umb_enddate) ? $usermemberModel->umb_enddate : GeneralHelper::getStrToTimePricelistPeriod($percode, $startdate, true));

$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$payProviders = GeneralHelper::getPayproviders( ($umPrice == 0) ); //);

$cryptoToAddress = []; //Cryptoaddress::getCryptoToAddresses($prlcadIds, $userpayModel->upy_payprovider, false);
//Yii::trace('** view pay prlcadIds='.$prlcadIds.' => cryptoToAddress:'.print_r($cryptoToAddress, true));
/**
* @var yii\web\View $this
* @var backend\models\Userpay $userpayModel
*/
?>
<div class="userpay-pay">
  <div class="body-content">
    <div class="container">

    <div class="row mt-4">
      <div class="col col-mt-12 text-left">
        <h3><?= (($umPrice != 0) ? Yii::t('app', "Your order") : Yii::t('app', "Accept terms")) ?>:</h3>
      </div>
    </div>

    <div class="row">
      <div class="col">
				<?= DetailView::widget([
					'model' => $usermemberModel,
					'options' => ['class' => 'table'],
			    'attributes' => [
						[
							'format' => 'raw',
							'attribute' => '',
							'label' => Yii::t('app', 'Subscription'),
							'value' => $usermemberModel->umbmbr->mbr_title,
						],
						[
							'format' => 'raw',
							'attribute' => '',
							'label' => Yii::t('app', 'Period'),
							'value' => $pricelistPeriods[ $percode ],
						],
						[
							'format' => 'raw',
							'attribute' => 'umb_startdate',
							'value'	=> $usermemberModel->umb_paystartdate,
						],
						[
							'format' => 'raw',
							'attribute' => 'umb_enddate',
							'value' => $usermemberModel->umb_payenddate,
						],
						[
							'format' => 'raw',
							'attribute' => 'umbprl_id',
							'value' => "<span id='umAmount'>" . $symHtml ." ". $umPrice . "</span><span id='cryptoAmount'></span>",
						],
					],
				]); ?>
			</div>
		</div>

		<hr>

    <div class="row mt-4">
      <div class="col col-mt-12 text-left">
        <h3><?= Yii::t('app', "Payment") ?>:</h3>
      </div>
    </div>

    <div class="row">
      <div class="col">
				<div class="membership-pay">

		  	  <?php $form = ActiveForm::begin([
    	  		'id' => 'Userpay',
			      'layout' => 'horizontal',
      			'enableClientValidation' => true,
			      'errorSummaryCssClass' => 'error-summary alert alert-danger',
      			'fieldConfig' => [
			        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
      			  'horizontalCssClasses' => [
			          'label' => 'col-sm-2',
      			    #'offset' => 'col-sm-offset-4',
			          'wrapper' => 'col-sm-8',
      			    'error' => '',
				         'hint' => '',
        			],
			      ],
			    ]); ?>

			    <div class="">
						<?php if ($umPrice != 0) : ?>
							<?= $form->field($userpayModel, 'upy_payprovider')->dropDownList($payProviders); ?>
							<?= $form->field($userpayModel, 'upy_discountcode')->textInput(['maxlength' => true]) ?>
						<div id="upyProvidername">
			      	<?= $form->field($userpayModel, 'upy_providername')->textInput(['maxlength' => true, 'disabled'=>true]) ?>
						</div>
						<div id="upycadtoid">
							<?= $form->field($userpayModel, 'upycad_to_id')->dropDownList($cryptoToAddress, ['class'=>'col-sm-10'])->label(Yii::t('app', 'Pay with')); ?>
						</div>

						<div id="upyFromaccount">
      				<?= $form->field($userpayModel, 'upy_fromaccount')->textInput(['maxlength' => true, 'disabled'=>true]) ?>
						</div>
						<?php endif; ?>

						<?= $form->field($userpayModel, '_acceptMembershipterms')->checkbox()->label(
								Yii::t('app', 'I accept the {termslink}.', ['termslink' => Html::a(Yii::t('app', 'membership terms'), ['/site/membershipterms'], ['target'=>'terms'])])
						); ?>

						<hr>

			      <?php echo $form->errorSummary($userpayModel); ?>

			      <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> ' . (($umPrice != 0) ? Yii::t('app', 'Pay...') : Yii::t('app', 'Continue...')),
      			  ['id' => 'save-' . $userpayModel->formName(),  'class' => 'btn btn-success']
			      ); ?>

			      <?= Html::a(Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-primary']) ?>
					</div>
      		<?php ActiveForm::end(); ?>
				</div>
			</div>
    </div>
  </div>
</div>

<?php JSRegister::begin([
    'position' => \yii\web\View::POS_READY
]); ?>
<script>
var fiatprice = <?= $umPrice ?>;
var cryptorate = 1.0;
var payincrypto = false;
var cryptosymbol = '';
var cryptodecimals = 0;

function changePayprovider() {
  try {
    var payprovider = $('#userpay-upy_payprovider').val();
    console.log('payprovider=['+payprovider+']');
		switch (payprovider) {
			case '<?= GeneralHelper::PAYPROVIDER_PAYPAL ?>':
			case '<?= GeneralHelper::PAYPROVIDER_MOLLIE ?>':
			case '<?= GeneralHelper::PAYPROVIDER_UTRUST ?>':
			default:
				$('#upyProvidername').hide();
				$('#upyFromaccount').hide();
				$('#upycadtoid').hide();
				$('#cryptoAmount').html('');
				//$('#userpay-upy_payprovider option[value="<?= GeneralHelper::PAYPROVIDER_NONE ?>"]').attr('disabled',true);
				payincrypto = false;
				break;
			case '<?= GeneralHelper::PAYPROVIDER_CRYPTODIRECT ?>':
			case '<?= GeneralHelper::PAYPROVIDER_CRYPTOWALLET ?>':
        $('#upyProvidername').hide();
        $('#upyFromaccount').hide();
				$('#upycadtoid').show();
				payincrypto = true;
				getCryptoCodes();
				getCryptoAmount();
				break;
    }
		console.log('payincrypto='+(payincrypto ? 'true':'false'));
  } catch (error) {
    submit=false;
    console.error('submit error: '+error.code+'='+error.message);
  }
};

function getCryptoCodes() {
	try {
		const payvia = $('#userpay-upy_payprovider').val();
		console.log('getCryptoCodes payvia='+payvia);
		if (typeof payvia !== 'undefined') {
			$("#userpay-upycad_to_id").empty().append($("<option value='' selected><?= Yii::t('app', 'Select...') ?></option>"));
			$.post(
				'/membership/getcryptocodes',
				{'prlcadIds':'<?=$prlcadIds?>', 'payvia':payvia},
				(response) => {
					var data=JSON.parse(response);
					if (data.options) {
						$.each(data.options, function(key,value) {
							$("#userpay-upycad_to_id").append($("<option></option>").attr("value", key).text(value));
						});
					}
				}
			);
		}
  } catch (error) {
    console.error('getCryptoCodes error: '+error.code+'='+error.message);
  }
}

function showCryptoprice() {
	if (payincrypto) {
		var cryptoprice = fiatprice / cryptorate;
		console.log('showCryptoprice cryptorate='+cryptorate+' -> cryptoprice='+cryptoprice);
		$('#cryptoAmount').html(' => ' + cryptoprice.toFixed(cryptodecimals) + ' ' + cryptosymbol + ' (<?= Yii::t('app','rate') ?>: <?= $symHtml ?> ' + cryptorate.toFixed(2) + ')');
	} else {
		$('#cryptoAmount').html('');
	}
}

function checkDiscount(discountcode) {
	try {
		//var discountcode = $(this).val();
		console.log('Discountcode='+discountcode);
		if (discountcode.length > 0) {
			$.post(
        '/membership/checkdiscount',
        {'discountcode':discountcode, 'mbrid':<?= (!empty($mbrid)?$mbrid:0) ?>, 'usrid':<?= (!empty($usrid)?$usrid:0) ?>, 'symid':<?= (!empty($symid)?$symid:0)?>, 'percode':'<?= (!empty($percode)?$percode:'') ?>'},
				(response) => {
          var data=JSON.parse(response);
					var free = false;
					var usedcount = 1 * data.usedcount;
					var allowedtimes = 1 * data.allowedtimes;
					console.log('price='+data.price+' usedcount='+usedcount+' allowedtimes='+allowedtimes);
          if ((data.price != 'na') && (usedcount < allowedtimes)) {
						fiatprice = data.price;
            $('#umAmount').html('<?= $symHtml ?> ' + fiatprice + " <?= Yii::t('app', '(discount price)') ?>");
						$('#userpay-upy_payprovider').val(0);
						free = (fiatprice == 0);
          } else {
						fiatprice = <?= $umPrice ?>;
            $('#umAmount').html("<?= $symHtml ?> " + fiatprice + " (" + ((data.price == 'na') ? "<?= Yii::t('app', 'invalid discountcode') ?>" : "<?= Yii::t('app', 'already used discountcode') ?>") + ')');
          }
					$('#userpay-upy_payprovider').prop('readonly', free);
					$('#save-Userpay').text((!free) ? "<?= Yii::t('app', 'Pay...') ?>" : "<?= Yii::t('app', 'Continue...') ?>");
					if (free) {
						if ( $('#userpay-upy_payprovider option[value="<?= GeneralHelper::PAYPROVIDER_NONE ?>"]').length == 0 ) {
							$('#userpay-upy_payprovider').append("<option value='<?= GeneralHelper::PAYPROVIDER_NONE ?>' selected><?= Yii::t('app', 'None') ?></option>");
						}
					} else {
							$('#userpay-upy_payprovider option[value="<?= GeneralHelper::PAYPROVIDER_NONE ?>"]').remove();
					}
					showCryptoprice();
        }
      );
		} else {
			$('#umAmount').html("<?= $symHtml .' '. $umPrice ?>");
		}
	} catch (error) {
		submit=false;
		console.error('submit error: '+error.code+'='+error.message);
	}
};

function getCryptoAmount() {
	try {
		const cadto = $('#userpay-upycad_to_id').val();
		const [id, symbol, decimals] = cadto.split('|');
		cryptosymbol = symbol;
		cryptodecimals = decimals;
		console.log('getCryptoAmount cadto='+cadto+' id='+id+' symbol='+symbol+' decimals='+decimals);
		if ((fiatprice > 0) && (symbol!='')) {
			$.post(
				'/membership/getcryptoprice',
				{'quote':symbol,'base':'<?= $symCode ?>'},
				(response) => {
					var data = JSON.parse(response);
					if (typeof data.price !== 'undefined') {
						cryptorate = 1.0 * data.price.price;
						showCryptoprice();
					} else {
						cryptorate = 1.0;
						$('#cryptoAmount').html(data.error);
					}
				},
			);
		}
	} catch (error) {
    submit=false;
    console.error('submit error: '+error.code+'='+error.message);
  }
	return false;
}

$('#userpay-upy_payprovider').on('change', changePayprovider);
$('#userpay-upycad_to_id').after("<span class='col-sm-2'><button type='button' id='refreshCrypto'><?= Yii::t('app', 'Refresh') ?></button></span>");
$('#refreshCrypto').on('click', getCryptoAmount);
$('#userpay-upycad_to_id').on('change', getCryptoAmount);
$('#userpay-upy_discountcode').on('blur', function() { checkDiscount($(this).val()); });
$('#userpay-upy_payprovider').click();
changePayprovider();
checkDiscount( $('#userpay-upy_discountcode').val() );
</script>
<?php JSRegister::end(); ?>

