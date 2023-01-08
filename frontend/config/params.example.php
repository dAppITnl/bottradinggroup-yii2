<?php
return [
	'adminEmail' => 'webadmin@bottradinggroup.nl',
	'contactEmail' => 'contact@bottradinggroup.nl',
	'bsVersion' => '4.x',

	// Web3Autrh => https://docs.moralis.io/moralis-server/users/crypto-login/web3auth
	// https://dashboard.web3auth.io/home/overview
	'web3Auth_clientID' => '',
	'web3Auth_chainId'  => 'Moralis.Chains.ETH_ROPSTEN',
	'web3Auth_theme'    => 'dark', // light | dark
	'web3Auth_loginMethods' => json_encode(["google", "facebook", "twitter", "reddit", "discord", "twitch", "apple", "line", "github", "kakao", "linkedin", "weibo", "wechat", "email_passwordless"]),
	'web3Auth_whitelistIPs' => ["",],

  // Bitcko PayPal Api Extension
	// sb-9zqis7955524@business.example.com
	// 21oct21
  'payPalClientId_sandbox' => '',
  'payPalClientSecret_sandbox' => '',
	// live: 8nov21
	'payPalClientId_live' =>  '',
	'payPalClientSecret_live' => '',
	// Paypal mode..
	'payPalMode' => 'live',
	//'payPalMode' => 'sandbox',

	// Mollie
	'mollie_APIkey_test' => '',
	'mollie_APIkey_live' => '',
	'mollie_mode' => 'test',
	//'mollie_mode' => 'live',

	// Crypto Direct via coinqvest.com
	'cryptodirect_coinqvest_webhook' => 'https://www.coinqvest.com/api/v1',
	'cryptodirect_coinqvest_apikey' => '',
	'cryptodirect_coinqvest_apisecret' => '',
	'cryptodirect_coinqvest_mode' => 'test', // or live

	// https://merchants.sandbox-utrust.com/onboarding/get-started
	// https://docs.api.utrust.com/#section/Authentication
	// https://github.com/utrustdev/utrust-php

  // https://merchants.sandbox-utrust.com/integrations/api_keys
	'cryptoutrust_apiurl_sandbox' => 'https://merchants.api.sandbox-utrust.com/api',
	'cryptoutrust_apikey_sandbox' => '',
	'cryptoutrust_secret_sandbox' => '',
	// https://merchants.utrust.com/integrations/api_keys
	'cryptoutrust_apiurl_live'    => 'https://merchants.api.utrust.com/api',
	'cryptoutrust_apikey_live'    => '',
	'cryptoutrust_secret_live'    => '',
	'cryptoutrust_mode'           => 'live',

	// https://github.com/nwkcoins/ethereum-boilerplate/blob/main/src/components/Wallet/components/Transfer.jsx
	'cryptowallet_mode' => 'testnet',
	


//------------------------//
// SYSTEM SETTINGS
// get: Yii::$app->params[''];
//------------------------//

	/*
  * webroot of iamges
	*/
	'imagesmap' => 'https://bottradinggroup.nl/images/',

];
