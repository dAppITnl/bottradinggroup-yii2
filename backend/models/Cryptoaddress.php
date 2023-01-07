<?php

namespace backend\models;

use Yii;
use \backend\models\base\Cryptoaddress as BaseCryptoaddress;
use yii\helpers\ArrayHelper;
//use backend\models\User;
use common\helpers\GeneralHelper;

/**
 * This is the model class for table "cryptoaddress".
 */
class Cryptoaddress extends BaseCryptoaddress
{

  public function behaviors()
  {
    return ArrayHelper::merge(
      parent::behaviors(),
        [
          # custom behaviors
        ]
    );
  }

  public function rules()
  {
    return ArrayHelper::merge(
      parent::rules(),
        [
          # custom validation rules
        ]
    );
  }

	public function getNetworks($withGroups=true) {
		$result = [
			'BTC - Bitcoin' => [
				'btc|mainnet' => ['name'=>Yii::t('app', 'Bitcoin mainnet'), 'rpcurl'=>'', 'chainID'=>'', 'symbol'=>'BTC', 'explorer'=>'https://www.blockchain.com/explorer'],
				'btc|testnet' => ['name'=>Yii::t('app', 'Bitcoin testnet'), 'rpcurl'=>'', 'chainID'=>'', 'symbol'=>'BTC', 'explorer'=>'https://www.blockchain.com/explorer?view=btc-testnet'],
			],
      'XLM - Stellar' => [
        'xlm|mainnet' => ['name'=>Yii::t('app', 'Stellar mainnet'), 'rpcurl'=>'', 'chainID'=>'', 'symbol'=>'XLM', 'explorer'=>''],
        'xlm|testnet' => ['name'=>Yii::t('app', 'Stellar testnet'), 'rpcurl'=>'', 'chainID'=>'', 'symbol'=>'XLM', 'explorer'=>''],
      ],
			'ETH - Ethereum' => [
				'eth|mainnet' => ['name'=>Yii::t('app', 'Ethereum mainnet')   ,'rpcurl'=>'https://mainnet.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161','chainID'=>0x1, 'symbol'=>'ETH','explorer'=>'https://etherscan.io'],
				'eth|ropsten' => ['name'=>Yii::t('app', 'Ropsten testnetwerk'),'rpcurl'=>'https://ropsten.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161','chainID'=>0x3, 'symbol'=>'ETH','explorer'=>'https://ropsten.etherscan.io'],
				'eth|goerli'  => ['name'=>Yii::t('app', 'Goerli Test Network'),'rpcurl'=>'https://goerli.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161', 'chainID'=>0x5, 'symbol'=>'ETH','explorer'=>'https://goerli.etherscan.io'],
				'eth|rinkeby' => ['name'=>Yii::t('app', 'Rinkeby testnetwerk'),'rpcurl'=>'https://rinkeby.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161','chainID'=>0x4, 'symbol'=>'ETH','explorer'=>'https://rinkeby.etherscan.io'],
				'eth|kovan'   => ['name'=>Yii::t('app', 'Kovan-testnetwerk'),  'rpcurl'=>'https://kovan.infura.io/v3/9aa3d95b3bc440fa88ea12eaa4456161',  'chainID'=>0x2A,'symbol'=>'ETH','explorer'=>'https://kovan.etherscan.io'],
			],
			'BSC - Binance Smart Chain' => [
				'bsc|mainnet' => ['name'=>Yii::t('app', 'Binance Smart Chain mainnet'),'rpcurl'=>'https://bsc-dataseed.binance.org','chainID'=>0x38,'symbol'=>'BNB','explorer'=>'https://bscscan.com'],
				'bsc|testnet' => ['name'=>Yii::t('app', 'BSC Testnet'),                'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/bsc/testnet','chainID'=>'97','symbol'=>'BNB','explorer'=>'https://testnet.bscscan.com/'],
			],
			'Polygon (Matic)' => [
				'polygon|mainnet' => ['name'=>Yii::t('app', 'Polygon Mainnet'),       'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/polygon/mainnet','chainID'=>137,  'symbol'=>'MATIC','explorer'=>'https://explorer-mainnet.maticvigil.com/'],
				'polygon|mumbai'  => ['name'=>Yii::t('app', 'Polygon Mumbai testnet'),'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/polygon/mumbai', 'chainID'=>80001,'symbol'=>'MATIC','explorer'=>'https://explorer-mumbai.maticvigil.com/'],
			],
			'Arbitrum' => [
				'arbitrum|mainnet' => ['name'=>Yii::t('app', 'Arbitrum mainnet'),'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/arbitrum/mainnet','chainID'=>'','symbol'=>'','explorer'=>''],
				'arbitrum|rinkeby' => ['name'=>Yii::t('app', 'Arbitrum testnet'),'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/arbitrum/testnet','chainID'=>'','symbol'=>'','explorer'=>''],
			],
			'Avalanche' => [
				'avalanche|mainnet' => ['name'=>Yii::t('app', 'Avalanche mainnet'),'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/avalanche/mainnet','chainID'=>'','symbol'=>'','explorer'=>''],
				'avalanche|testnet' => ['name'=>Yii::t('app', 'Avalanche testnet'),'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/avalanche/testnet','chainID'=>'','symbol'=>'','explorer'=>''],
			],
			'Fantom' => [
				'fantom|mainnet' => ['name'=>Yii::t('app', 'Fantom mainnet'),'rpcurl'=>'https://speedy-nodes-nyc.moralis.io/0e94e6eebb7882b820d99d24/fantom/mainnet','chainID'=>'','symbol'=>'','explorer'=>''],
				//'fantom|' => ['name'=>Yii::t('app', ''),'rpcurl'=>'','chainID'=>'','symbol'=>'','explorer'=>''],
			],
			'Other (develop)' => [
				'other|develop' => ['name'=>Yii::t('app', 'Other (develop)'),'rpcurl'=>'','chainID'=>'','symbol'=>'','explorer'=>''],
			],
		];
		if (!$withGroups) {
			$result1 = $result;
			$result = [];
			foreach($result1 as $optgroup => $networks) $result = array_merge($result, $networks);
		}
		return $result;
	}

	public function getNetworkNames($withGroups=true) {
		$result = [];
		$allNetworks = self::getNetworks();
		foreach($allNetworks as $optgroup => $networks)
			foreach($networks as $code => $network)
				if ($withGroups) $result[ $optgroup ][ $code ] = $network['name'];
				else $result[ $code ] = $network['name'];
		return $result;
	}

	public function getCryptoaddressesSymbols($extrafields=true, $short=true, $withsymbol=false) {
		$result = [];
		try {
			$providers = ($extrafields ? GeneralHelper::getPayproviders() : []);
			$sql = "SELECT cad.id, sym.sym_symbol, sym.sym_code"
						.($extrafields ? ", cad.cad_payprovider, cad.cad_name" : "")
						." FROM cryptoaddress cad, symbol sym"
						." WHERE cad.cad_active=1 AND cad.cad_deletedat=0 AND sym.id=cad.cadsym_id";
			$rows = GeneralHelper::runSql($sql);
			foreach($rows as $nr => $row) if (is_numeric($nr)) {
				$label = $row['sym_symbol']
				. ($extrafields ? (!$short ? ", " . $row['cad_name'] : "") ." ". Yii::t('app', 'via') ." ". (!$short ? $providers[ $row['cad_payprovider'] ] : $row['cad_payprovider']) : "");
				if (!$withsymbol) {
					$result[ $row['id'] ] = $label;
				} else {
					$result[ $row['id'] ] = ['label'=>$label, 'symcode'=>$row['sym_code'] ];
				}
			}
		} catch(\Exception $e) {
			$msg = 'Error: '.$e->getMessage();
			Yii::trace('** getCryptoaddressesSymbols msg='.$msg );
			throw new \Exception($msg);
		}
		return $result;
	}

}
