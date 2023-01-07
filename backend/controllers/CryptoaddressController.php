<?php

namespace backend\controllers;

use Yii;
use backend\models\Cryptoaddress;
use backend\models\Symbol;

/**
* This is the class for controller "CryptoaddressController".
*/
class CryptoaddressController extends \backend\controllers\base\CryptoaddressController
{

	private function _getFromMoralis($path, $method='GET', $postdata='')
	{
		$result = [];
		try {
			$moralisapiurl = Yii::$app->params['moralis_api_url'];
			$moralisapikey = Yii::$app->params['moralis_api_key'];
			if (!empty($path) && !empty($moralisapiurl) && !empty($moralisapikey)) {
				$header = [
					'accept: application/json',
					'X-API-Key: ' . $moralisapikey,
				];
				Yii::trace('** _getFromMoralis method='.$method.' path='.$path);
				$curl = curl_init();
				$errFile = fopen('getfrommoralis-curlerror.txt', 'w');
				curl_setopt($curl, CURLOPT_URL,            $moralisapiurl . $path);
				if (strtoupper($method) == 'POST') {
					curl_setopt($curl, CURLOPT_POST,         1);
					curl_setopt($curl, CURLOPT_POSTFIELDS,   json_encode($postdata));
				}
				curl_setopt($curl, CURLOPT_HTTPHEADER,     $header);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($curl, CURLOPT_VERBOSE,        1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_STDERR,         $errFile);
				$result = json_decode(curl_exec($curl), true);
				fclose($errFile);
				curl_close($curl);
				Yii::trace('**_getFromMoralis curl result: '.print_r($result, true));
			}
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      Yii::trace('** _getFromMoralis error msg='.$msg);
      $result['error'] = $msg;
    }
    Yii::trace('** _getFromMoralis result: '.print_r($result, true));
    return $result;
	}


	public function actionGetmoralistokenmetadata()
	{
		$result = [];
		try {
			$moralisapiurl = Yii::$app->params['moralis_api_url'];
			$moralisapikey = Yii::$app->params['moralis_api_key'];
			if (!empty($_POST) && !empty($_POST['network']) && !empty($_POST['symaddress']) && !empty($moralisapiurl) && !empty($moralisapikey)) {
				$network=$_POST['network']; $symaddress=$_POST['symaddress'];
				$networks = CryptoAddress::getNetworks(false);
				$chainid = (!empty($networks[ $network ]['chainID']) ? $networks[ $network ]['chainID'] : 0);
				//$symaddress = Symbol::findOne($symid)->sym_contractaddress;
				Yii::trace('** actionGetmoralistokenmetadata network='.$network.' => chainid='.$chainid); //.' symid='.$symid.' => symaddress='.$symaddress);
				if (!empty($chainid) && !empty($symaddress)) {
					$metadata = self::_getFromMoralis('/erc20/metadata?chain=0x'.dechex($chainid).'&addresses='.$symaddress);
					$result['metadata'] = (!empty($metadata[0]) ? $metadata[0] : $metadata['message']);
				}
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** actionGetmoralistokenmetadata error msg='.$msg);
			$result['error'] = $msg;
		}
		Yii::trace('** actionGetmoralistokenmetadata result: '.print_r($result, true));
		return json_encode($result);
	}

}
