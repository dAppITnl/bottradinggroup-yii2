<?php
return [
	'adminEmail' => 'webadmin@bottradinggroup.nl',
	'contactEmail' => 'contact@bottradinggroup.nl',
	'bsVersion' => '4.x',

	// Web3Autrh => https://docs.moralis.io/moralis-server/users/crypto-login/web3auth
	// https://dashboard.web3auth.io/home/overview
	'web3Auth_clientID' => 'BNfbFs14GLEyFHgp0Y_U1jhWrJMow_RZ-Snic_7cCtXYNF6TOTUY2xtI28Oui0tI9IbiyuzMp9B7rTawdqpaLWM',
	'web3Auth_chainId'  => 'Moralis.Chains.ETH_ROPSTEN',
	'web3Auth_theme'    => 'dark', // light | dark
	'web3Auth_loginMethods' => json_encode(["google", "facebook", "twitter", "reddit", "discord", "twitch", "apple", "line", "github", "kakao", "linkedin", "weibo", "wechat", "email_passwordless"]),
	'web3Auth_whitelistIPs' => ["5.104.122.110",],

  // Bitcko PayPal Api Extension
	// sb-9zqis7955524@business.example.com
	// 21oct21
  'payPalClientId_sandbox' => 'AaB7Day9SR0EaTzf5NmisJVv3z7anPEZ75S4Q1Y_Dx0QFr1x6NwCqCEHpuZRu3q4QC_PXn3NJBswgR46',
  'payPalClientSecret_sandbox' => 'EC03A8vXRBlygZ4d2eMHzKERkoEx67fi6gdThyFiSBLuoKt3EWjK0Ph-9JCLBiFkBZveNo2PL7uAJMFD',
	// live: 8nov21
	'payPalClientId_live' =>  'AQNbQcRHk9yS60d5Tr6RAP6SfxtHMF3kgBHhINb_MTrHFddT-dnypeCV7msa-4_PUyoPbEFJiienfveK',
	'payPalClientSecret_live' => 'EKXQM6axJdYUJ7-2_QN0g3mE2w2FL_Z36oQgqtRi7-kcGBAarTJd6MERztjEWKdyM6S0qP-tNPEeuk6N',
	// Paypal mode..
	'payPalMode' => 'live',
	//'payPalMode' => 'sandbox',

	// Mollie
	'mollie_APIkey_test' => 'test_u7M2je88qSWfDcq2uE5rWM7H2hWevz',
	'mollie_APIkey_live' => '',
	'mollie_mode' => 'test',
	//'mollie_mode' => 'live',

	// Crypto Direct via coinqvest.com
	'cryptodirect_coinqvest_webhook' => 'https://www.coinqvest.com/api/v1',
	'cryptodirect_coinqvest_apikey' => 'c7214d13cca6',
	'cryptodirect_coinqvest_apisecret' => 'f3DC-s6av-SHk@-WCXt-M@W$-f99@',
	'cryptodirect_coinqvest_mode' => 'test', // or live

	// https://merchants.sandbox-utrust.com/onboarding/get-started
	// https://docs.api.utrust.com/#section/Authentication
	// https://github.com/utrustdev/utrust-php

  // https://merchants.sandbox-utrust.com/integrations/api_keys
	// Rinkeby 0.00762 https://rinkeby.etherscan.io/tx/0x7b1f269aab0cebf5ce49d9e7768539db2acb203126f612e66b129d6ea62bbefc --> not the one..
	// Ropsten 0.0076  https://ropsten.etherscan.io/tx/0x42d98e26676c33626e2a9b8a8fc36bb91f21ae8980ee89ef86306524806566bd --> processed by Utrust
	'cryptoutrust_apiurl_sandbox' => 'https://merchants.api.sandbox-utrust.com/api',
	'cryptoutrust_apikey_sandbox' => 'u_test_api_0b009cbc-965c-470c-89e6-bd6a0f238ba8',
	'cryptoutrust_secret_sandbox' => 'u_test_webhooks_879e0a5b-a2b6-4903-85a9-b08960f59d6a',
	// https://merchants.utrust.com/integrations/api_keys
	'cryptoutrust_apiurl_live'    => 'https://merchants.api.utrust.com/api',
	'cryptoutrust_apikey_live'    => 'u_live_api_fdfdd92b-3688-47b4-965a-01a5e04ef49c',
	'cryptoutrust_secret_live'    => 'u_live_webhooks_99cfeff8-e7af-449a-8b87-0b498f161ea4',
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
