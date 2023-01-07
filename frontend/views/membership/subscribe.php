<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;

use \common\helpers\GeneralHelper;

// $this->registerCssFile('@web/css/index.css');

$usrid = \Yii::$app->user->id;
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$memberships = ArrayHelper::toArray($membershipModels, ['\backend\models\Membership' => ['id', 'mbr_title', 'mbr_cardbody', ]]);
$membershipQuantitiesForUser = GeneralHelper::getMembershipQuantitiesForUser($usrid);
Yii::trace('** subscribe membershipQuantitiesForUser: '.print_r($membershipQuantitiesForUser, true));

$isDev = false; // Yii::$app->user->identity->isDev();

?>
<div class="site-index">
  <div class="body-content">

    <div class="container">

      <div class="row">
				<div class="col text-center">
					<h1><?= Yii::t('app', 'Our Signal plans') ?></h1>
				</div>
			</div>

			<div class="row mt-2 justify-content-md-center">

<?php foreach($memberships as $cardNr => $membership) :
				Yii::trace('** subscribe membership: '.print_r($membership, true));
				$pricelist = GeneralHelper::getPricelistOfMembership($membership['id']);
				$priceOptions = '';
//echo " pricelist id=".$membership['id']."=".print_r($pricelist,true)	.'<br>';
				if (count($pricelist) > 0) {
					$selected=' selected';
					foreach($pricelist as $prlid => $price) {
						$mbrQuantity = (!empty($membershipQuantitiesForUser[ $membership['id'] ][ $prlid ]) ? $membershipQuantitiesForUser[ $membership['id'] ] : 0);
						$allowed = ($price['allowedtimes'] == 0) || ($mbrQuantity < $price['allowedtimes']);
						Yii::trace('** subscribe option='.$price['name'].' => (mbrQuantity='.$mbrQuantity.' < allowedtimes='.$price['allowedtimes']. ') => '.($allowed ? 'Yes':'No'));
						$priceOptions .= '<option value="'.$prlid.'"'.$selected. ($allowed ? '': ' disabled' ) .'>'
							.(!empty($price['pretext']) ? $price['pretext'].' ' : '')
							.$price['symbol'].' '.$price['price'] . ' ('.$pricelistPeriods[ $price['percode'] ]
							.(!empty($price['posttext'])? ', '.$price['posttext'] : /*' ('.$pricelistPeriods[ $price['percode'] ]*/
									($price['allowedtimes']>0 ? '; '.$price['allowedtimes'].' '.($price['allowedtimes']==1 ? Yii::t('app', 'time'):Yii::t('app', 'times')) : ''))
							.($isDev ? ' '.$prlid.'|'.$price['allowedtimes'].'x':'')
							.')</option>';
						$selected='';
					}
				}
?>
				<br><br>
        <div id="card-<?= ($cardNr+1) ?>" class="col-lg-4 mb-4 subscribe-cards">
 	        <div class="cryptoyard-signals index-box-tier h-100">
   	        <div class="card-body">
     	        <div class="table-card-head">
       	        <h4 class="center"><?= $membership['mbr_title'] ?></h4>
         	    </div>
           	  <div class="table-card-content">
								<?= $membership['mbr_cardbody'] ?><br><?= ($isDev ? $membership['id']:'') ?><br>
  								<form id="form-<?= $membership['id'] ?>" action="/membership/subscribe" method="post">
									<input type="hidden" name="mbrid" value="<?= $membership['id'] ?>">
									<select name="prlid" class="uk-select select-subscribe">
										<?= $priceOptions ?>
									</select><br><br>
       	       		<button type="submit" class="button-card">Join</button>
								</form>
							</div>
           	</div>
					  <div class="cryptoyard-signals-div"></div><br>
          </div>
				</div>

<?php   if (($cardNr % 3) == 2) : ?>
      </div>
			<div class="row">
<?php   endif;
			endforeach; ?>

			</div>

  		<div><?= implode('<br>',$errors) ?></div>

    </div>

  </div>
</div>
