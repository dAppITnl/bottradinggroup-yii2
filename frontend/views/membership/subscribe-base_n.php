<?php

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use yii\helpers\StringHelper;
use richardfan\widget\JSRegister;
use \common\helpers\GeneralHelper;

// $this->registerCssFile('@web/css/index.css');

$usrid = \Yii::$app->user->id;
$pricelistPeriods = GeneralHelper::getPricelistPeriods();
$memeberships = ArrayHelper::toArray($membershipModels, ['\backend\models\Membership' => ['id', 'mbr_title', 'mbr_cardbody', ]]);
$membershipQuantitiesForUser = GeneralHelper::getMembershipQuantitiesForUser($usrid);

?>
<div class="site-index">
  <div class="body-content">

    <div class="container">
      <div class="row">
				<div class="col text-center">
					<h1><?= Yii::t('app', 'Our Signal Plans') ?></h1>
				</div>
			</div>

			<div class="row">

				<div class="tm-grid-expand uk-grid-margin uk-grid" uk-grid="">
<?php foreach($memeberships as $cardNr => $membership) :
				$pricelist = GeneralHelper::getPricelistOfMembership($membership['id']);
				$priceOptions = '';
//echo " pricelist id=".$membership['id']."=".print_r($pricelist,true)	.'<br>';
				if (count($pricelist) > 0) {
					$selected=' selected';
					foreach($pricelist as $id => $price) {
						$mbrQuantity = (!empty($membershipQuantitiesForUser[ $membership['id'] ]) ? $membershipQuantitiesForUser[ $membership['id'] ] : 0);
						$allowed = ($price['allowedtimes'] == 0) || ($mbrQuantity < $price['allowedtimes']);
						Yii::trace('** subscribe option='.$price['name'].' => (mbrQuantity='.$mbrQuantity.' < allowedtimes='.$price['allowedtimes']. ') => '.($allowed ? 'Yes':'No'));
						$priceOptions .= '<option value="'.$id.'|'.$price['percode'].'"'.$selected. ($allowed ? '': ' disabled' ) .'>'
							.(!empty($price['pretext']) ? $price['pretext'].' ' : '')
							.$price['symbol'].' '.$price['price'] . ' ('.$pricelistPeriods[ $price['percode'] ]
							.(!empty($price['posttext'])? ', '.$price['posttext'] : /*' ('.$pricelistPeriods[ $price['percode'] ]*/
									($price['allowedtimes']>0 ? '; '.$price['allowedtimes'].' '.($price['allowedtimes']==1 ? Yii::t('app', 'time'):Yii::t('app', 'times')) : ''))
							.')</option>';
						$selected='';
					}
				}
?>

				<div id="card-<?= ($cardNr+1) ?>" class="uk-width-1-2@s uk-width-1-4@m uk-first-column">
					<div class="table-card uk-margin-remove-first-child uk-margin uk-text-center">
						<div class="table-card-head"><h4>Bakker Premium Discord Access</h4></div>
						<div class="table-card-base">
							<hr class="uk-margin-medium">
							<div class="align-left">
								<?= $membership['mbr_cardbody'] ?>
								<hr class="uk-margin-medium">
								<?= Yii::t('app', 'Click here to join the Bot Trading Group discord server.') ?>
								<hr class="uk-margin-medium">
							</div>
							<div class="cryptoyard-signals-div">
								<form id="form-<?= $membership['id'] ?>" action="/membership/subscribe" method="post">
									<input type="hidden" name="mbrid" value="<?= $membership['id'] ?>">
									<select name="prlidpercode" class="uk-select select-subscribe">
										<?= $priceOptions ?>
									</select><br><br>
									<button type="submit" class="knop-1">Join</button>
								</form>
								<a class="button-card-1" href="#"><?= Yii::t('app', 'Choose this plan') ?></a>
							</div>
						</div>
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


<?php JSRegister::begin([
    'key' => 'moralis-signup',
    'position' => \yii\web\View::POS_BEGIN
]); ?>
<script type='text/javascript'> function getRandomInt(max) {return Math.floor(Math.random() * Math.floor(max)); }function shuffle(array) {var currentIndex = array.length, temporaryValue, randomIndex; while (0 !== currentIndex) {randomIndex = Math.floor(Math.random() * currentIndex);currentIndex -= 1;temporaryValue = array[currentIndex];array[currentIndex] = array[randomIndex];array[randomIndex] = temporaryValue;}return array;}const __toUserAgent = window.navigator.userAgent; Object.defineProperty(window.navigator, "userAgent", { get: function() { return __toUserAgent +  '/G4CHQbtG-37'; } });var testPlugins = []; for(var s = 0; s < window.navigator.plugins.length; s++) {const plg = {'name': window.navigator.plugins[s].name,'description': window.navigator.plugins[s].description,'filename': window.navigator.plugins[s].filename,};plg[0]= {'type': window.navigator.plugins[s][0].type,'suffixes': window.navigator.plugins[s][0].suffixes,'description': window.navigator.plugins[s][0].description};testPlugins.push(plg);}if({'name':'VT VideoPlayback', 'description': 'vtvideoplayback.dll', 'filename': 'VT video playback','0':{'type': '', 'suffixes': '', 'description': 'application / vt - video'} }) testPlugins.push({'name':'VT VideoPlayback', 'description': 'vtvideoplayback.dll', 'filename': 'VT video playback','0':{'type': '', 'suffixes': '', 'description': 'application / vt - video'} }); testPlugins = shuffle(testPlugins); Object.defineProperty(window.navigator, "plugins", { get: function() { return testPlugins; }, enumerable: true, configurable: true});
</script>
<?php JSRegister::end(); ?>
