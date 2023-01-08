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
];
