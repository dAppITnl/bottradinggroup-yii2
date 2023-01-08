<?php

//------------------------//
// SYSTEM SETTINGS
// get: Yii::$app->params[''];
//------------------------//

return [
	'adminEmail' => 'webadmin@bottradinggroup.nl', // 'admin.botsignals@bot-support.nl',
	'supportEmail' => 'websupport@bottradinggroup.nl', // 'support.botsignals@bot-support.nl',
	'senderEmail' => 'noreply@bottradinggroup.nl',
	'senderName' => 'bottradinggroup.nl mailer',
	'user.passwordResetTokenExpire' => 3600,
	'user.passwordMinLength' => 8,

	'standard_sym_code' => 'EUR',
	'standard_sym_name' => 'EURO',
	'standard_sym_symbol' => '€',
	'standard_sym_html' => '&euro;',

	'discord_webhook'     => 'https://discord.com/api/v6',
	'discord_bot_token'   => '',
	'discord_bot_token_2' => '',
	'discord_server_id'   => '885495602144772117',
	'discord_cryptoyardsignals' => '892473573367771156',
	'discord_premiumlogs' => '916070975529971772',

	'moralis_applicationID' => '',
	'moralis_serverUrl'     => 'https://kjzl4puf1lpi.moralishost.com:2053/server',

	//'binance_api_webhook' => 'https://api.binance.com', // https://binance-docs.github.io/apidocs/spot/en/

];
