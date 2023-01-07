<?php
namespace common\helpers;

use Yii;
use common\models\User;
use backend\models\User as BackendUser;
use backend\models\Userpay;
use backend\models\Membership;
use backend\models\Pricelist;
use backend\models\Signallog;
use backend\models\Symbol;
use backend\models\Category;

/**
 * General helper class.
 */
class GeneralHelper
{
	const DISCORD_LOCK_FILE = '/tmp/bottradinggroup.nl-disordlockfile';
	const DISCORD_LOGWAIT_FILE = '/usr/share/php/bottradinggroup-yii2/common/helpers/discord-wait.log';
	const DISCORD_ERROR_FILE = '/usr/share/php/bottradinggroup-yii2/common/helpers/discord-error.log';

	/*
	* SET field values
	*/
	const LANGUAGE_ENUS = 'en-US';
	const LANGUAGE_NLNL = 'nl-NL';

	public static function getContext() {
		$context = basename(Yii::getAlias('@app')); // frontend, backend, hftadmin, apiv1
		Yii::trace('** getContext: '.$context);
		return $context;
	}

	public static function generateRandomString($length) {
		// https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
		$keyspace = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ#$%&*@!';
		$kslen = mb_strlen($keyspace, '8bit') - 1;
		$result = '';
		for ($i=0; $i<$length; $i++) {
			$result .= $keyspace[random_int(0,$kslen)];
		}
		return $result;
	}

	public static function getMonths($hort=true) {
		return $short ? [
			1  => Yii::t('app', 'Jan'),
			2  => Yii::t('app', 'Feb'),
			3  => Yii::t('app', 'Mar'),
			4  => Yii::t('app', 'Apr'),
			5  => Yii::t('app', 'Mai'),
			6  => Yii::t('app', 'Jun'),
			7  => Yii::t('app', 'Jul'),
			8  => Yii::t('app', 'Aug'),
			9  => Yii::t('app', 'Sep'),
			10 => Yii::t('app', 'Oct'),
			11 => Yii::t('app', 'Nov'),
			12 => Yii::t('app', 'Dec')
		] : [
      1  => Yii::t('app', 'January'),
      2  => Yii::t('app', 'February'),
      3  => Yii::t('app', 'March'),
      4  => Yii::t('app', 'April'),
      5  => Yii::t('app', 'Mai'),
      6  => Yii::t('app', 'June'),
      7  => Yii::t('app', 'July'),
      8  => Yii::t('app', 'August'),
      9  => Yii::t('app', 'September'),
      10 => Yii::t('app', 'October'),
      11 => Yii::t('app', 'November'),
      12 => Yii::t('app', 'December')
		];
	}

	// see createCssFilename for filename creation
	const SITECSS_WHITE = 'whitetheme';
	const SITECSS_DARK  = 'darktheme';
	const SITECSS_BASEN = 'base_n';

	public static function getLanguages() {
		return [
			self::LANGUAGE_ENUS => Yii::t('common', 'English'),
			self::LANGUAGE_NLNL => Yii::t('common', 'Dutch (nederlands)'),
		];
	}

	public static function getSiteCssFiles() {
		return [
			self::SITECSS_WHITE => 'white theme',
			self::SITECSS_DARK => 'dark theme',
			self::SITECSS_BASEN => 'base_n theme',
		];
	}

	public static function createCssFilename($cssFile='') {
		$filename = (!empty($cssFile) ? $cssFile : 'darkTheme') . '.css';
		$cssmap = Yii::getAlias('@cssthemes') . '/';
		Yii::trace('createCssFilename: cssmap='.$cssmap.' filename='.$filename);
		if (!file_exists($cssmap.$filename)) file_put_contents($cssmap.$filename, "/"."* CSS dummy file for ".$cssFile." */");
		return $filename;
	}

	public static function getFrontendViewFiles() {
		$result = [];
		$removelength = strlen(Yii::getAlias('@frontendViews')) + 1;

		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator( Yii::getAlias('@frontendViews') ));
		foreach ($rii as $file) {
			$fileparts = pathinfo($file);
			if ($file->isDir() || $fileparts['extension']!='php' || !is_writeable($file)) continue;

			if (!in_array($fileparts['filename'], [
				'error'
			])) {
				$viewfile = substr($file->getPathname(), $removelength, -4); // only path inside view folder and without extenssion
				$result[ $viewfile ] = $viewfile;
			}
		}
		Yii::trace('** getFrontendViewFiles: '.print_r($result, true));
		return $result;
	}

// ----------------------------

		public static function shortenAddress($address='') {
			return (!empty($address) ? substr($address,0,7).'...'.substr($address,-5) : "");
		}

// ----------------------------
	public static function getQuoteBasePrice($quote='', $base='', $_errorReverseOnce=false) {
		$result = [];
		try {
			$quote=strtoupper($quote);
			if (!empty($quote)) {
				$base = (empty($base) ? 'EUR' : strtoupper($base));
				if ($base == $quote) {
					$result['price'] = 1;
				} else {
					$url = 'https://api.binance.com/api/v3/ticker/price?symbol='.strtoupper($quote).$base;
					Yii::trace('** getQuoteBasePrice url='.$url);
					$pricedata = file_get_contents($url); // => {"symbol":"ETHEUR","price":"3368.36000000"}
					Yii::trace('** getQuoteBasePrice pricedata='.$pricedata);
					if (!empty($pricedata) && (strpos($pricedata,'symbol')!==false) && (strpos($pricedata,'price')!==false)) {
						$result = json_decode($pricedata, true);
						if ($_errorReverseOnce && !empty($result['price'])) {
							$result['price'] = 1 / $result['price'];
						}
					} else {
						$result['error'] = Yii::t('app', 'Error getting coinprice: {reason}', ['reason'=> $pricedata]);
					}
				}
			}
		} catch (\Exception $e) {
			if ($_errorReverseOnce) {
	      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
        Yii::trace('** calcEuro2CryptoPrice error msg='.$msg);
   	    $result['error'] = $msg;
			} else {
				Yii::trace('** getQuoteBasePrice REVERSED: '.$base.'-'.$quote);
				return self::getQuoteBasePrice($base, $quote, true); // try reverse order
			}
		}
		Yii::trace('** getQuoteBasePrice quote='.$quote.' base='.$base.' => '.print_r($result, true));
		return $result;
	}

// ----------------------------

	public static function getUserStatuses() {
		return [
			USER::STATUS_DELETED => Yii::t('common', 'Deleted'),
			USER::STATUS_INACTIVE => Yii::t('common', 'Not confirmed'),
			USER::STATUS_ACTIVE => Yii::t('common', 'Confirmed'),
		];
	}

	public static function getContactKinds() {
		return [
			'sales' => Yii::t('common', 'Sales'),
			'payment' => Yii::t('common', 'Payment'),
			'signal' => Yii::t('common', 'Signal'),
			'support' => Yii::t('common', 'Support'),
			'website' => Yii::t('common', 'Website'),
			'general' => Yii::t('common', 'General'),
			'discord' => Yii::t('common', 'Discord'),
			'tech' => Yii::t('common', 'Tech'),
		];
	}

	public static function getCategoryTypes() {
		return [
      Category::CAT_CATTYPE_USR => Yii::t('common', 'User'),
			Category::CAT_CATTYPE_MBR => Yii::t('common', 'Membership'),
      Category::CAT_CATTYPE_BOT => Yii::t('common', 'Bot'),
      Category::CAT_CATTYPE_SYM => Yii::t('common', 'Symbol'),
			Category::CAT_CATTYPE_PRL => Yii::t('common', 'Pricelist'),
      Category::CAT_CATTYPE_PAY => Yii::t('common', 'Payment'),
      Category::CAT_CATTYPE_SIG => Yii::t('common', 'Signal'),
      Category::CAT_CATTYPE_SLG => Yii::t('common', 'Signallog'),
		];
	}

	public static function getYesNos($asIcon=true) {
		return [
			0 => ($asIcon ? '<i class="fa-solid fa-minus"></i>' : Yii::t('common', 'No')),
			1 => ($asIcon ? '<i class="fa-solid fa-check"></i>' : Yii::t('common', 'Yes'))
		];
  }

	public static function getSymbolTypes() {
		return [
			Symbol::SYM_TYPE_FIAT => Yii::t('common', 'Fiat'),
			Symbol::SYM_TYPE_CRYPTO => Yii::t('common', 'Crypto'),
			Symbol::SYM_TYPE_ERC20 => Yii::t('common', 'erc20'),
			Symbol::SYM_TYPE_ERC720 => Yii::t('common', 'erc720'),
			Symbol::SYM_TYPE_ERC1150 => Yii::t('common', 'erc1150'),
			Symbol::SYM_TYPE_NETWORK => Yii::t('common', 'Network'),
			Symbol::SYM_TYPE_OTHER => Yii::t('common', 'Other'),
		];
	}

// -------------------------

	public static function getDiscordRoles() {
		// From https://discord.com/channels/885495602144772117/898310007018389544
		// https://discord.com/api/guilds/885495602144772117/widget.json
		// serverId: 885495602144772117
		return [
			'905148136291442808' => Yii::t('common', 'Dutch'),
			'905148241862098996' => Yii::t('common', 'English'),
			'885500197378080850' => Yii::t('common', 'Server admin(s)'),
			'886298494221037629' => Yii::t('common', 'Moderator(s)'),
			'893813945016651786' => Yii::t('common', 'Developer(s)'),
			'887285275921907745' => Yii::t('common', 'Support'),
			'886301326567436289' => Yii::t('common', 'Smart trades'),
			'893078940187525180' => Yii::t('common', 'Strategies'),
			'886300278838669382' => Yii::t('common', 'Pionex'),
			'887287906450939944' => Yii::t('common', 'Bitsgap'),
			'892860116758581339' => Yii::t('common', 'TA provider'),
			'898916678182776852' => Yii::t('common', 'Signal provider'),
			'892862677754474546' => Yii::t('common', 'Tester'),
			'886301834883514379' => Yii::t('common', 'VIP'),
			'890581296554836078' => Yii::t('common', 'Actual prices'),
			'887269829650808872' => Yii::t('common', 'Muted'),
			'887269899624390656' => Yii::t('common', 'Quarantine'),
			'890522178552995880' => Yii::t('common', 'Community Adviser (Lvl 75)'),
			'890521138952802345' => Yii::t('common', 'Premium Supporter (Lvl 50)'),
			'890288259131924500' => Yii::t('common', 'Basis Supporter (Lvl 20)'),
			'890288119176396800' => Yii::t('common', 'Beginner (Lvl 5)'),
			'892143306815963158' => Yii::t('common', 'Sponsor'),
			'885839738316128266' => Yii::t('common', 'verified user'),
			'890287406320861216' => Yii::t('common', 'Observer'),
			'893827838837415987' => Yii::t('common', '[PREMIUM] Bakker'),
			'892860634356662352' => Yii::t('common', '[PREMIUM] TA'),
			'892860718435684392' => Yii::t('common', '[PREMIUM] Cryptoyard Signals'),
			'892860634356662352' => Yii::t('common', '[PREMIUM] TA'),
			//'' => Yii::t('common', ''),
		];
	}

	// planned to be used in /backend/views/user/setdiscordtoken.php BUT disabled options DOES NOT survive...!!
	// now only used (keys) in frontend/controllers/MembershipController.php ..
	public static function disabledDiscordRoles() {
		return [
      //'905148136291442808' => ['disabled' => true], //=> Yii::t('common', 'Dutch'),
      //'905148241862098996' => ['disabled' => true], //=> Yii::t('common', 'English'),
      '885500197378080850' => ['disabled' => true], //=> Yii::t('common', 'Server admin(s)'),
      '886298494221037629' => ['disabled' => true], //=> Yii::t('common', 'Moderator(s)'),
      '893813945016651786' => ['disabled' => true], //=> Yii::t('common', 'Developer(s)'),
      '887285275921907745' => ['disabled' => true], //=> Yii::t('common', 'Support'),
      '886301326567436289' => ['disabled' => true], //=> Yii::t('common', 'Smart trades'),
      '893078940187525180' => ['disabled' => true], //=> Yii::t('common', 'Strategies'),
      '886300278838669382' => ['disabled' => true], //=> Yii::t('common', 'Pionex'),
      '887287906450939944' => ['disabled' => true], //=> Yii::t('common', 'Bitsgap'),
      '892860116758581339' => ['disabled' => true], //=> Yii::t('common', 'TA provider'),
      '898916678182776852' => ['disabled' => true], //=> Yii::t('common', 'Signal provider'),
      '892862677754474546' => ['disabled' => true], //=> Yii::t('common', 'Tester'),
      '886301834883514379' => ['disabled' => true], //=> Yii::t('common', 'VIP'),
      '890581296554836078' => ['disabled' => true], //=> Yii::t('common', 'Actuele prijzen'),
      '887269829650808872' => ['disabled' => true], //=> Yii::t('common', 'Muted'),
      '887269899624390656' => ['disabled' => true], //=> Yii::t('common', 'Quarantine'),
      '890522178552995880' => ['disabled' => true], //=> Yii::t('common', 'Community Adviseur (Lvl 75)'),
      '890521138952802345' => ['disabled' => true], //=> Yii::t('common', 'Premium Supporter (Lvl 50)'),
      '890288259131924500' => ['disabled' => true], //=> Yii::t('common', 'Basis Supporter (Lvl 20)'),
      '890288119176396800' => ['disabled' => true], //=> Yii::t('common', 'Beginner (Lvl 5)'),
      '892143306815963158' => ['disabled' => true], //=> Yii::t('common', 'Sponsor'),
      '885839738316128266' => ['disabled' => true], //=> Yii::t('common', 'verified user'),
      '890287406320861216' => ['disabled' => true], //=> Yii::t('common', 'Observeerder'),
      //'893827838837415987' => ['disabled' => true], //=> Yii::t('common', '[PREMIUM] Bakker'),
      //'892860634356662352' => ['disabled' => true], //=> Yii::t('common', '[PREMIUM] TA'),
      //'892860718435684392' => ['disabled' => true], //=> Yii::t('common', '[PREMIUM] Cryptoyard Signals'),
      //'892860634356662352' => ['disabled' => true], //=> Yii::t('common', '[PREMIUM] TA'),
		];
	}

	public static function getMembershipDiscordroles4User($usrid='') {
		$result = [];
		if (!empty($usrid)) {
			$sql = "SELECT DISTINCT mbr.id, mbr.mbr_discordroles FROM (usermember umb, membership mbr)"
						." WHERE umb.umbusr_id=".$usrid." AND umb.umb_active=1 AND umb.umb_deletedat=0 AND (NOW() BETWEEN umb.umb_startdate and umb.umb_enddate)"
						." AND mbr.id=umb.umbmbr_id AND mbr.mbr_active=1 AND mbr.mbr_deletedat=0";
			$rows = self::runSql($sql);
			foreach($rows as $nr => $row) if (is_numeric($nr) && !empty($row['mbr_discordroles'])) {
				foreach(explode(',', $row['mbr_discordroles']) as $role) if (!in_array($role, $result)) $result[] = $role;
			}
		}
		Yii::trace('** getMembershipDiscordroles4User userid='.$usrid.' => '.print_r($result, true));
		return $result;
	}

  private static function __sendDiscordData($urlpath, $method='', $postdata=[]) // called via _getDiscordData($urlpath) !!
  {
		$result = [];
    try {
			if (!empty($urlpath) && !empty($method)) {
				$maxtries = 5;
				do {
	  	    $discordWebhook = Yii::$app->params['discord_webhook'];
  	  	  $discordBotToken = Yii::$app->params['discord_bot_token'];
    	  	Yii::trace('** __sendDiscordData 1 maxtimes='.$maxtries.' method='.$method.' url='.$discordWebhook.$urlpath.' token='.$discordBotToken);

					$curl = curl_init();

					$headers = ['Authorization: Bot '.$discordBotToken];
					switch ($method) {
						case 'GET':  break;
						case 'PATCH':
						case 'POST':
							if (!empty($postdata)) {
								array_push($headers, ...['Content-Type: application/json', 'Accept: application/json']);
								curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postdata));
								break;
							} else {
								$msg='No data for method='.$method;
								Yii::trace('** __sendDiscordData ERROR: '.$msg);
								$result['error'] = $msg;
              	break 2;
							}
						default:
							$msg='Invalid method: '.$method;
							Yii::trace('** __sendDiscordData ERROR: '.$msg);
							$result['error'] = $msg;
							break 2;
					}
					Yii::trace('** __sendDiscordData 2 headers: '.print_r($headers, true));
					curl_setopt($curl, CURLOPT_HTTPHEADER,     $headers); //['Authorization: Bot '.$discordBotToken],
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  $method);

					curl_setopt($curl, CURLOPT_URL,            $discordWebhook . $urlpath);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($curl, CURLOPT_VERBOSE,        1);
					curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

					$errFile = fopen('getDiscordData-curlerror.txt', 'w');
					curl_setopt($curl, CURLOPT_STDERR,         $errFile);

					$curlresult = curl_exec($curl);
					$result = (empty($curlresult) ? curl_getinfo($curl) : json_decode($curlresult, true));
					$curlerrno = curl_errno($curl);
					if ($curlerrno) $result['error'] = $curlerrno .': '. curl_error($curl);

		      fclose($errFile);
  		    curl_close($curl);
					Yii::trace('** __sendDiscordData 3 maxtimes='.$maxtries.' urlpath='.$urlpath.' => curl result: '.print_r($result, true));

					// https://discord.com/developers/docs/topics/rate-limits
					$waitseconds = 0;
					if (!empty($result['retry_after']) && ($maxtries > 0)) {
						$waitseconds = 1 +  $result['retry_after'];
						if ($waitseconds > 0) {
							Yii::trace('** __sendDiscordData 4 DISCORD REQUEST RESULT: maxtries='.$maxtries.' RATE LIMIT : retry after '.$waitseconds.' seconds! SLEEPING...');
							file_put_contents(self::DISCORD_LOCK_FILE, (time() + $waitseconds), LOCK_EX);
							$usrid = (!empty(\Yii::$app->user->id) ? \Yii::$app->user->id : 0);
							$username = (!empty(Yii::$app->user->identity->username) ? Yii::$app->user->identity->username : '');
							$debugbacktrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3);
							Yii::trace('** __sendDiscordData debugbacktrace: '.print_r($debugbacktrace, true));
							$debugbacktrace2 = (!empty($debugbacktrace[2]) ? basename($debugbacktrace[2]['file'],'.php').'('.$debugbacktrace[2]['args'][0].')' : '');
							file_put_contents(self::DISCORD_LOGWAIT_FILE, 'S:'.date('yMd-His').'|' . str_pad($waitseconds,4,' ',STR_PAD_LEFT) . '|' . $debugbacktrace2 .'|'. $urlpath ."\n", FILE_APPEND);
							sleep( $waitseconds );
							file_put_contents(self::DISCORD_LOGWAIT_FILE, 'E:'.date('yMd-His').'|' . str_pad($waitseconds,4,' ',STR_PAD_LEFT) . '|' . $debugbacktrace2 .'|'. $urlpath ."\n", FILE_APPEND);
							Yii::trace('** __sendDiscordData 5 DISCORD REQUEST RESULT: maxtimes='.$maxtries.' RATE LIMIT : slept for '.$waitseconds.' seconds! RETRYING...');
							$maxtries--;
							//$result = self::__sendDiscordData($urlpath, $maxtries); // recursive
						}
					}
				} while (($waitseconds > 0) && ($maxtries > 0));

				if (!empty($result['message'])) {
					$result['error'] = $result['message'] . (!empty($result['retry_after']) ? ' Retry after '.$result['retry_after'].' seconds' : '');
					$sqlresult = '';
					if (!empty($result['code']) && ($result['code'] == 10007) && (($pos=strpos($urlpath, '/members/')) !== false)) {
						$discordid = substr($urlpath, $pos+9);
						if (!empty($discordid)) {
							$sql = "UPDATE user SET usr_remarks=CONCAT(usr_remarks,'".$result['message'].">',usr_discordid,':',usr_discordjoinedat,'=',usr_discordusername,',',usr_discordnick,':',usr_discordroles,'>cleared'),"
										." usr_discordid=NULL, usr_discordusername=NULL, usr_discordnick=NULL, usr_discordroles=NULL, usr_discordjoinedat=NULL, usr_updatedt=NOW(), updated_at='".time()."'"
										." WHERE usr_discordid='".$discordid."' AND deleted_at=0";
							$sqlresult = self::runSql($sql);
						}
					}
					file_put_contents(self::DISCORD_ERROR_FILE, date('yMd-His').">".$urlpath.">".$result['error'].(!empty($sqlresult) ? '>'.json_encode($sqlresult) : '')."\n", FILE_APPEND);
				}
			}
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      Yii::trace('** __sendDiscordData error msg='.$msg);
      $result['error'] = $msg;
    }
    //Yii::trace('** __sendDiscordData result: '.print_r($result, true));
    return $result; //(isset($result[0]) ? $result[0] : $result );
  }

	private static function _sendDiscordData($urlpath='', $method='', $postdata=[]) {
		$result = [];
		try {
			if (!empty($urlpath) && !empty($method)) {
				$method = strtoupper($method);
				while (file_exists(self::DISCORD_LOCK_FILE)) {
					Yii::trace('** _sendDiscordData file exists '.self::DISCORD_LOCK_FILE);
					$maxwaittimestamp = (int) file_get_contents(self::DISCORD_LOCK_FILE);
					Yii::trace('** _sendDiscordData maxwaittimestamp='.$maxwaittimestamp);
					if ($maxwaittimestamp < time()) break; // no timestamp or value in past
					Yii::trace('** _sendDiscordData FILE '.self::DISCORD_LOCK_FILE.' EXISTS = '.$maxwaittimestamp.' => sleep a second and recheck till removed or data in past..');
					sleep( 1 );
				}
				touch(self::DISCORD_LOCK_FILE);
				$result = self::__sendDiscordData($urlpath, $method, $postdata);
				unlink(self::DISCORD_LOCK_FILE);
			} else {
				$msg = 'Missing urlpath='.$urlpath.' or method='.$method;
				Yii::trace('** _sendDiscordData ERROR: '.$msg);
				$result['error'] = $msg;
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** _sendDiscordData urlpath='.$urlpath.' method='.$method.' => ERROR: '.$msg);
			$result['error'] = $msg;
		}
		return $result;
	}

	public static function getDiscordMember($discordId='', $username='')
  {
    $result = [];

    if (!empty($discordId) || !empty($username)) {
      try {
        $discordServerId = (is_numeric(Yii::$app->params['discord_server_id']) ? Yii::$app->params['discord_server_id'] : '');
        Yii::trace('** getDiscordMember ServerId='.$discordServerId.' discordId='.$discordId.' username='.$username);

        if (!empty($discordId)) {
          $urlpath = '/guilds/'.$discordServerId.'/members/'.$discordId;
        } elseif (!empty($username)) {
          $urlpath = '/guilds/'.$discordServerId.'/members/search?query='.$username;
        }

        $userData = self::_sendDiscordData($urlpath, 'GET');
        Yii::trace('** getDiscordMember urlpath='.$urlpath.' => userdata:'.print_r($userData, true));

        if (!empty($userData)) {
					if (!empty($userData['error'])) $result = $userData;
          elseif (!empty($userData['message']) && isset($userData['code'])) $result['error'] = $userData['message'] .' ('. $userData['code'] .')';
          else {
						$data = (!empty($userData[0]['user']) ? $userData[0] : $userData);
            $result = [
              'id' => (is_numeric($data['user']['id']) ? $data['user']['id'] : ''),
              'username' => $data['user']['username'] .'#'. $data['user']['discriminator'],
              'nick' => (!empty($data['nick']) ? $data['nick'] : ''),
              'joinedat' => date('Y-m-d H:i:s', strtotime($data['joined_at'])),
              'roles' => $data['roles'],
            ];
          }
        }
      } catch (\Exception $e) {
        $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
        Yii::trace('** getDiscordMember error msg='.$msg);
        $result['error'] = $msg;
      }
    }
    Yii::trace('** getDiscordMember result:'.print_r($result, true));
    return json_encode($result);
  }

	// For urls: https://discord.com/developers/docs/resources/application
/*  private function _sendDataToDiscordServer($method='', $urlpath='', $data='') {
		$result = [];
		try {
			if (!empty($method) && !empty($urlpath) && !empty($data)) {
				$discordWebhook = Yii::$app->params['discord_webhook'];
				$discordBotToken = Yii::$app->params['discord_bot_token'];
				Yii::trace('** _sendDataToDiscordServer method='.$method.' urlpath='.$urlpath.' wehbhook='.$discordWebhook.' token='.$discordBotToken.' data:'.print_r($data, true));
				$headers = [
					'Content-Type: application/json',
					'Accept: application/json',
					'Authorization: Bot '.$discordBotToken
				];

				$curl = curl_init();
				$errFile = fopen('saveDataToDiscordServer_curlerror.txt', 'w');
				curl_setopt($curl, CURLOPT_URL,           $discordWebhook . $urlpath);
				curl_setopt($curl, CURLOPT_HTTPHEADER,    $headers);
				curl_setopt($curl, CURLOPT_POSTFIELDS,    json_encode($data));
				/ * if ($method == 'POST') { curl_setopt($curl, CURLOPT_POST, true); } else { * / curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method); // }
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_VERBOSE,        true);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // for debug: true
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // for debug: true
				curl_setopt($curl, CURLOPT_STDERR,         $errFile);
				$curlresult = curl_exec($curl);
				$curlerrno = curl_errno($curl);
				if ($curlerrno) {
					$result = ['errno'=>$curlerrno, 'msg'=>curl_error($curl)];
				}
				$result['result'] = (empty($curlresult) ? curl_getinfo($curl) : json_decode($curlresult, true));
				fclose($errFile);
				curl_close($curl);
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** _sendDataToDiscordServer error msg='.$msg);
			$result['error'] = $msg;
		}
		Yii::trace('** _sendDataToDiscordServer result: '.(!empty($result['timestamp']) ? 'ok: '.$result['timestamp'] : print_r($result, true)));
		return $result;
	} */

  public function saveRolesToDiscordServer($discordId=0, $discordRoles=[])
  {
    $result = [];
    try {
      if (!empty($discordId) && !empty($discordRoles) && is_array($discordRoles)) {
				$discordServerId = (is_numeric(Yii::$app->params['discord_server_id']) ? Yii::$app->params['discord_server_id'] : '');
        $urlpath = '/guilds/'.$discordServerId.'/members/'.$discordId;
        $data = ['roles' => $discordRoles];
				Yii::trace('** saveRolesToDiscordServer discordServerId='.$discordServerId.' urlpath='.$urlpath.' data='.print_r($data,true));
				$result = self::_sendDiscordData($urlpath, 'PATCH', $data);  //self::_sendDataToDiscordServer('PATCH', $urlpath, $data);
      }
    } catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      Yii::trace('** saveRolesToDiscordServer error msg='.$msg);
      $result['error'] = $msg;
    }
    Yii::trace('** saveRolesToDiscordServer result: '.print_r($result, true));
    return $result;
  }

	// https://discord.com/developers/docs/resources/channel#create-message
	public function sendMessageToDiscordCategory($channel='', $message='') {
		$result = [];
		try {
			Yii::trace('** sendMessageToDiscordCategory channel='.$channel.' message='.$message);
			$channelId = (is_numeric($channel) ? $channel : Yii::$app->params[$channel]);
			if (!empty($channelId)) {
				Yii::trace('**  channel='.$channel.' => channelId='.$channelId.'; message='.$message);
				if (!empty($channelId) && !empty($message)) {
					$urlpath = '/channels/'. $channelId . '/messages';
					$data = ['content' => $message];
					$result = self::_sendDiscordData($urlpath, 'POST', $data); //self::_sendDataToDiscordServer('POST', $urlpath, $data);
				} else {
					$result['error'] = 'No ChannelId='.$channelId.' or no message='.$message;
				}
			} else {
				$result['error'] = 'No Channel given! '.$channelId.' . '.$channel;
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** error msg='.$msg);
			$result['error'] = $msg;
		}
		Yii::trace('** sendMessageToDiscordCategory result '.(!empty($result['timestamp']) ? 'ok: '.$result['timestamp'] : print_r($result, true)));
		return $result;
	}

// ==================================

	public static function sendToCoinqvest($method='', $urlpath='', $data='') {
		$result = [];
		try {
			$apikey = Yii::$app->params['cryptodirect_coinqvest_apikey'];
			$apisecret = Yii::$app->params['cryptodirect_coinqvest_apisecret'];
			$webhook = Yii::$app->params['cryptodirect_coinqvest_webhook'];
			$msg = 'webhook='.$webhook.' apikey.len='.strlen($apikey).' apisecret.len='.strlen($apisecret).' method='.$method.'; urlpath='.$urlpath.'; data='.print_r($data,true);
			Yii::trace('** sendToCoinqvest '.$msg);
			if (!empty($webhook) && !empty($apikey) && !empty($apisecret) && !empty($method) && !empty($urlpath) && !empty($data)) {
				$method = strtoupper($method);
				$timestamp = time();
				$body = ($method == 'GET' ? null : json_encode($data) );
				$signature = hash_hmac('sha256', $urlpath . $timestamp . $method . $data, $apisecret);
				$headers = [
					'X-Digest-Key: ' . $apikey,
					'X-Digest-Signature: ' . $signature,
					'X-Digest-Timestamp: ' . $timestamp
				];
				Yii::trace('** sendToCoinqvest url='.$webhook.'/'.$urlpath.' body='.$body.' header:'.print_r($headers, true));
				$curl = curl_init();
        $errFile = fopen('sendToCoinqvest_curlerror.txt', 'w');
        curl_setopt($curl, CURLOPT_URL,           $webhook .'/'. $urlpath);
        curl_setopt($curl, CURLOPT_HTTPHEADER,    $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS,    $body);
        /*if ($method == 'POST') { curl_setopt($curl, CURLOPT_POST, true); } else {*/ curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method); // }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_VERBOSE,        true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // for debug: true
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // for debug: true
        curl_setopt($curl, CURLOPT_STDERR,         $errFile);
        $result = json_decode(curl_exec($curl), true);
        fclose($errFile);
        curl_close($curl);
			} else {
				$result['errors'][] = 'Invalid params: '.$msg;
			}
		} catch (\Exception $e) {
      $msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
      Yii::trace('** error msg='.$msg);
      $result['errors'][] = $msg;
    }
		Yii::trace('** sendToCoinqvest result: '.print_r($result, true));
		return $result;
	}

// -------------------------

	public static function sendToUtrust($path='', $data = []) {
		$result = [];
		try {
			$mode = Yii::$app->params['cryptoutrust_mode']; $mode = ((($mode=='sandbox') || ($mode=='live')) ? $mode : '');
			$apikey = (!empty($mode) ? Yii::$app->params['cryptoutrust_apikey_'.$mode] : '');
			$apiurl = (!empty($mode) ? Yii::$app->params['cryptoutrust_apiurl_'.$mode] : '');
			if (!empty($path) && !empty($data) && !empty($apikey) && !empty($apiurl)) {
				Yii::trace('** sendToUtrust $path='.$path.' data: '.print_r($data, true));
				$ch = curl_init();
				$errFile = fopen('sendToUtrust_curlerror.txt', 'w');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_MAXREDIRS,      10);
				curl_setopt($ch, CURLOPT_TIMEOUT,        30);
				curl_setopt($ch, CURLOPT_HTTP_VERSION,   CURL_HTTP_VERSION_1_1);
				curl_setopt($ch, CURLOPT_POST,           1);
				$headers = [];
				$headers[] = 'Authorization: Bearer ' . $apikey;
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_HTTPHEADER,     $headers);
				curl_setopt($ch, CURLOPT_URL,            $apiurl .'/'. $path); //  '/stores/orders/');
				curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode($data));
				curl_setopt($ch, CURLOPT_STDERR,         $errFile);
				$response = curl_exec($ch);
				fclose($errFile);
				curl_close($ch);

				if ($response !== false) {
					$decoded = json_decode($response, true);
					if (!empty($decoded)) {
						if (!empty($decoded['errors'])) {
							$result['error'] = json_encode($decoded['errors']);
						} else {
							$result = $decoded;
						}
					} else {
						$result['error'] = 'JSON: ' . json_last_error();
					}
				} else {
					$result['error'] = 'cURL: ' . curl_error($this->curlHandle);
				}
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** error msg='.$msg);
			$result['error'] = $msg;
		}
		Yii::trace('** sendToUtrust result: '.print_r($result, true));
		return $result;
	}

	public static function validateSignatureUtrust($data=[]) {
		$result = '';
		try {
			$mode = Yii::$app->params['cryptoutrust_mode']; $mode = ((($mode=='sandbox') || ($mode=='live')) ? $mode : '');
			$secret = (!empty($mode) ? Yii::$app->params['cryptoutrust_secret_'.$mode] : '');
			if (!empty($secret) && !empty($data) && !empty($data['signature'])) {
				$dataSignature = $data['signature'];
				unset($data['signature']);
				// Concat keys and values into one string
				$concatedData = [];
				foreach ($data as $key => $value) {
					if (is_object($value)) {
						foreach ($value as $k => $v) {
							$concatedData[] = $key;
							$concatedData[] = $k . $v;
						}
					} else {
						$concatedData[] = $key . $value;
					}
				}
				ksort($concatedData);
				$concatedData = join('', $concatedData);
				$calcSignature = hash_hmac('sha256', $concatedData, $secret);
				$result = (($dataSignature === $calcSignature) ? 'ok' : 'invalid signature');
			} else {
				$result = 'invalid data or no secret';
			}
		} catch (\Exception $e) {
			$msg = (isset($e->errorInfo[2]))?$e->errorInfo[2]:$e->getMessage();
			Yii::trace('** validateSignatureUtrust error msg='.$msg);
			$result = 'error: ' . $msg;
		}
		Yii::trace('** validateSignatureUtrust result: '.$result);
		return $result;
	}

// -------------------------

	public static function checkSiteAccess() {
		$result = ['backend'=>'false', 'frontend'=>'false', 'hftadmin'=>'false', 'level'=>BackendUser::USR_SITELEVEL_NONE];
		if (!Yii::$app->user->isGuest) {
			$sitelevel = Yii::$app->user->identity->usr_sitelevel;
			$backend = (in_array($sitelevel, [
				BackendUser::USR_SITELEVEL_DEV,
				BackendUser::USR_SITELEVEL_SUPERADMIN,
				BackendUser::USR_SITELEVEL_ADMIN,
				//BackendUser::USR_SITELEVEL_SUPPORT,
				//BackendUser::USR_SITELEVEL_BTESTER,
				//BackendUser::USR_SITELEVEL_ATESTER,
				//BackendUser::USR_SITELEVEL_MEMBER,
				//BackendUser::USR_SITELEVEL_HFTMEMBER,
				//BackendUser::USR_SITELEVEL_USER,
				//BackendUser::USR_SITELEVEL_GUEST,
				//BackendUser::USR_SITELEVEL_NONE,
			]) ? 'true' : 'false');
			$frontend = (in_array($sitelevel, [
				BackendUser::USR_SITELEVEL_DEV,
				BackendUser::USR_SITELEVEL_SUPERADMIN,
				BackendUser::USR_SITELEVEL_ADMIN,
				BackendUser::USR_SITELEVEL_SUPPORT,
				BackendUser::USR_SITELEVEL_BTESTER,
				BackendUser::USR_SITELEVEL_ATESTER,
				BackendUser::USR_SITELEVEL_MEMBER,
				//BackendUser::USR_SITELEVEL_HFTMEMBER,
				BackendUser::USR_SITELEVEL_USER,
				BackendUser::USR_SITELEVEL_GUEST,
				//BackendUser::USR_SITELEVEL_NONE,
			]) ? 'true' : 'false');
			$hftadmin = (in_array($sitelevel, [
        BackendUser::USR_SITELEVEL_DEV,
        BackendUser::USR_SITELEVEL_SUPERADMIN,
        BackendUser::USR_SITELEVEL_ADMIN,
        //BackendUser::USR_SITELEVEL_SUPPORT,
        //BackendUser::USR_SITELEVEL_BTESTER,
        //BackendUser::USR_SITELEVEL_ATESTER,
        //BackendUser::USR_SITELEVEL_MEMBER,
				//BackendUser::USR_SITELEVEL_HFTMEMBER,
        //BackendUser::USR_SITELEVEL_USER,
        //BackendUser::USR_SITELEVEL_GUEST,
        //BackendUser::USR_SITELEVEL_NONE,
      ]) ? 'true' : 'false');
			$result = ['backend'=>$backend, 'frontend'=>$frontend, 'hftadmin'=>$hftadmin, 'level'=>$sitelevel];
			Yii::trace('** checkSiteAccess: '.print_r($result, true));
		}
		return $result;
	}

	public static function allowWhenMinimal($level, $usrid=0) {
		$result = false;
		if (!Yii::$app->user->isGuest) { // must be logged in to check..
			$sitelevel = (!empty($usrid) ? BackendUser::findOne($usrid)->usr_sitelevel : Yii::$app->user->identity->usr_sitelevel);
			//Yii::trace('** allowWhenMinimal level='.$level.' for (usrid='.$usrid.' has sitelevel='.$sitelevel.')');
			switch ($level) {
				case BackendUser::USR_SITELEVEL_DEV:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV]));
					break;
				case BackendUser::USR_SITELEVEL_SUPERADMIN:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN]));
					break;
				case BackendUser::USR_SITELEVEL_ADMIN:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN]));
					break;
				case BackendUser::USR_SITELEVEL_SUPPORT:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
																					 BackendUser::USR_SITELEVEL_SUPPORT]));
					break;
				case BackendUser::USR_SITELEVEL_BTESTER:
          $result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
                                           BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER]));
          break;
				case BackendUser::USR_SITELEVEL_ATESTER:
          $result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
                                           BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER, BackendUser::USR_SITELEVEL_ATESTER]));
          break;
				case BackendUser::USR_SITELEVEL_MEMBER:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
																					 BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER, BackendUser::USR_SITELEVEL_ATESTER,
                                           BackendUser::USR_SITELEVEL_MEMBER]));
          break;
				case BackendUser::USR_SITELEVEL_HFTMEMBER:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
																					 BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER, BackendUser::USR_SITELEVEL_ATESTER,
																					 BackendUser::USR_SITELEVEL_MEMBER, BackendUser::USR_SITELEVEL_HFTMEMBER]));
					break;
				case BackendUser::USR_SITELEVEL_USER:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
																					 BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER, BackendUser::USR_SITELEVEL_ATESTER,
																					 BackendUser::USR_SITELEVEL_MEMBER, BackendUser::USR_SITELEVEL_HFTMEMBER, BackendUser::USR_SITELEVEL_USER]));
					break;
				case BackendUser::USR_SITELEVEL_GUEST:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
																					 BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER, BackendUser::USR_SITELEVEL_ATESTER,
																					 BackendUser::USR_SITELEVEL_MEMBER, BackendUser::USR_SITELEVEL_HFTMEMBER, BackendUser::USR_SITELEVEL_USER,
																					 BackendUser::USR_SITELEVEL_GUEST]));
					break;
				case BackendUser::USR_SITELEVEL_NONE:
					$result = (in_array($sitelevel, [BackendUser::USR_SITELEVEL_DEV, BackendUser::USR_SITELEVEL_SUPERADMIN, BackendUser::USR_SITELEVEL_ADMIN,
																					 BackendUser::USR_SITELEVEL_SUPPORT, BackendUser::USR_SITELEVEL_BTESTER, BackendUser::USR_SITELEVEL_ATESTER,
																					 BackendUser::USR_SITELEVEL_MEMBER, BackendUser::USR_SITELEVEL_HFTMEMBER, BackendUser::USR_SITELEVEL_USER,
																					 BackendUser::USR_SITELEVEL_GUEST, BackendUser::USR_SITELEVEL_NONE]));
					break;
			}
		}
		Yii::trace('** allowWhenMinimal level='.$level.' for (usrid='.$usrid.' has sitelevel='.$sitelevel.') => result='.($result ? 'TRUE' : 'FALSE'));
		return ($result ? 'true' : 'false');
	}

	public static function maximalSitelevels($maxUsersLevel) {
		$result = [];
		$hftadmin = self::getContext() == 'hftadmin';
		if (!Yii::$app->user->isGuest) {
			switch($maxUsersLevel) {
        case BackendUser::USR_SITELEVEL_DEV:        array_push($result, BackendUser::USR_SITELEVEL_DEV);
        case BackendUser::USR_SITELEVEL_SUPERADMIN: array_push($result, BackendUser::USR_SITELEVEL_SUPERADMIN);
        case BackendUser::USR_SITELEVEL_ADMIN:      array_push($result, BackendUser::USR_SITELEVEL_ADMIN);
        case BackendUser::USR_SITELEVEL_SUPPORT:    array_push($result, BackendUser::USR_SITELEVEL_SUPPORT);
        case BackendUser::USR_SITELEVEL_BTESTER:    array_push($result, BackendUser::USR_SITELEVEL_BTESTER);
        case BackendUser::USR_SITELEVEL_ATESTER:    array_push($result, BackendUser::USR_SITELEVEL_ATESTER);
        case BackendUser::USR_SITELEVEL_MEMBER:     array_push($result, BackendUser::USR_SITELEVEL_MEMBER);
				case BackendUser::USR_SITELEVEL_HFTMEMBER:  array_push($result, BackendUser::USR_SITELEVEL_HFTMEMBER);
        case BackendUser::USR_SITELEVEL_USER:       array_push($result, BackendUser::USR_SITELEVEL_USER);
        case BackendUser::USR_SITELEVEL_GUEST:      array_push($result, BackendUser::USR_SITELEVEL_GUEST);
        default:
        case BackendUser::USR_SITELEVEL_NONE:       if (!$hftadmin) array_push($result, BackendUser::USR_SITELEVEL_NONE);
			}
		}
		return $result;
	}

	public static function minimalSitelevels($minUsersLevel) {
    $result = [];
		$hftadmin = self::getContext() == 'hftadmin';
    if (!Yii::$app->user->isGuest) {
      switch($minUsersLevel) {
        default:
        case BackendUser::USR_SITELEVEL_NONE:       if (!$hftadmin) array_push($result, BackendUser::USR_SITELEVEL_GUEST);

				case BackendUser::USR_SITELEVEL_GUEST:      array_push($result, BackendUser::USR_SITELEVEL_USER);
				case BackendUser::USR_SITELEVEL_USER:       array_push($result, BackendUser::USR_SITELEVEL_HFTMEMBER);
				case BackendUser::USR_SITELEVEL_HFTMEMBER:  array_push($result, BackendUser::USR_SITELEVEL_MEMBER);
				case BackendUser::USR_SITELEVEL_MEMBER:     array_push($result, BackendUser::USR_SITELEVEL_ATESTER);
				case BackendUser::USR_SITELEVEL_ATESTER:    array_push($result, BackendUser::USR_SITELEVEL_BTESTER);
				case BackendUser::USR_SITELEVEL_BTESTER:    array_push($result, BackendUser::USR_SITELEVEL_SUPPORT);
				case BackendUser::USR_SITELEVEL_SUPPORT:    array_push($result, BackendUser::USR_SITELEVEL_ADMIN);
				case BackendUser::USR_SITELEVEL_ADMIN:      array_push($result, BackendUser::USR_SITELEVEL_SUPERADMIN);
				case BackendUser::USR_SITELEVEL_SUPERADMIN: array_push($result, BackendUser::USR_SITELEVEL_DEV);
      }
			if ($hftadmin) array_push($result, BackendUser::USR_SITELEVEL_NONE);
    }
    return $result;
  }

	// $allLevels only for GridView or View, not for DropdownList() !!
	public static function getSiteLevels($fromUsersLevel=true, $maxUsersLevel='') {
		$result = [];
		$hftadmin = self::getContext() == 'hftadmin';
		if (empty($maxUsersLevel) && $hftadmin) $maxUsersLevel = BackendUser::USR_SITELEVEL_HFTMEMBER;
		if (empty($maxUsersLevel)) $maxUsersLevel = Yii::$app->user->identity->usr_sitelevel;
		Yii::trace('** getSitelevels maxUsersLevel='.$maxUsersLevel);
		if (!Yii::$app->user->isGuest) {
      switch ( ($fromUsersLevel ? $maxUsersLevel : BackendUser::USR_SITELEVEL_DEV) ) {
        case BackendUser::USR_SITELEVEL_DEV:        $result = array_merge($result, [BackendUser::USR_SITELEVEL_DEV => Yii::t('common', 'Dev')]);
        case BackendUser::USR_SITELEVEL_SUPERADMIN: $result = array_merge($result, [BackendUser::USR_SITELEVEL_SUPERADMIN => Yii::t('common', 'Superadmin')]);
        case BackendUser::USR_SITELEVEL_ADMIN:      $result = array_merge($result, [BackendUser::USR_SITELEVEL_ADMIN => Yii::t('common', 'Admin')]);
        case BackendUser::USR_SITELEVEL_SUPPORT:    $result = array_merge($result, [BackendUser::USR_SITELEVEL_SUPPORT => Yii::t('common', 'Support')]);
				case BackendUser::USR_SITELEVEL_BTESTER:    $result = array_merge($result, [BackendUser::USR_SITELEVEL_BTESTER => Yii::t('common', 'BTester')]);
				case BackendUser::USR_SITELEVEL_ATESTER:    $result = array_merge($result, [BackendUser::USR_SITELEVEL_ATESTER => Yii::t('common', 'ATester')]);
        case BackendUser::USR_SITELEVEL_MEMBER:     $result = array_merge($result, [BackendUser::USR_SITELEVEL_MEMBER => Yii::t('common', 'Member')]);
				case BackendUser::USR_SITELEVEL_HFTMEMBER:  $result = array_merge($result, [BackendUser::USR_SITELEVEL_HFTMEMBER => Yii::t('common', 'HFTMember')]);
        case BackendUser::USR_SITELEVEL_USER:       $result = array_merge($result, [BackendUser::USR_SITELEVEL_USER => Yii::t('common', 'User')]);
        case BackendUser::USR_SITELEVEL_GUEST:      $result = array_merge($result, [BackendUser::USR_SITELEVEL_GUEST => Yii::t('common', 'Guest')]);
				default:
        case BackendUser::USR_SITELEVEL_NONE:       if (!$hftadmin) $result = array_merge($result, [BackendUser::USR_SITELEVEL_NONE => Yii::t('common', 'None')]);
				break;
      }
    }
		Yii::trace('** getSitelevels result: '.print_r($result,true));
		return $result;
	}

// -------------------------

	protected function _getCategories() {
		$sql = "SELECT id, cat_language, cat_title, cat_order FROM category WHERE cat_type='sig' AND cat_deletedat=0 ORDER BY cat_order ASC, cat_title ASC";
		return self::runSql($sql);
	}

	protected function _getCatTitle($categories=[], $catIds='', $language='') {
		$catIds = explode(',', $catIds);
		$defTitle = $catTitle = '';
		if (!empty($categories)) {
			foreach($categories as $catId => $category) if (is_numeric($catId) && in_array($catId, $catIds)) {
				$defTitle = $category['cat_title'];
				if (!empty($language) && ($category['cat_language'] == $language)) $catTitle = $defTitle;
				if (empty($catTitle) && ($category['cat_language'] == 'en-US')) $catTitle = $defTitle;
			}
		}
		return (!empty($catTitle) ? $catTitle : Yii::t('common', 'General signal'));
	}

	public function getUserSignals($usrid='', $language='', $onlyCurrently=true) {
		$result = [];
		if (!empty($usrid)) {
			$categories = self::_getCategories();
			Yii::trace('** getcategorySignals Categories: '.print_r($categories,true));

			// memberships of user
			$sql = "SELECT umb.id as id, mbr.id as mbrid, mbr.mbr_title, umb.umb_name, umb.umb_startdate, umb.umb_enddate"
						." FROM (usermember umb, membership mbr)"
						." WHERE umb.umbusr_id=".$usrid." AND umb.umb_active=1 AND umb.umb_deletedat=0"
						.($onlyCurrently ? " AND (NOW() BETWEEN umb.umb_startdate and umb.umb_enddate)" : "")
						." AND umb.umbmbr_id=mbr.id AND mbr.mbr_active=1 AND mbr.mbr_deletedat=0"
						." ORDER BY mbr_title, umb_startdate";
			$usermembers = self::runSql($sql);
			Yii::trace('** getcategorySignals usermembers: '.print_r($usermembers,true));

			foreach($usermembers as $umbid => $usermember) if (is_numeric($umbid)) {
				// signals for usermember
				$sql = "SELECT ubt.id as id, ubt.ubt_name, ubt.ubt_active, ubt.ubt_3cbotid,"
							." bsg.id as bsgid, bsg.bsg_active,"
							." sig.id, sig.sigcat_ids, sig.sig_name, sig.sig_maxbots, "
							." IFNULL((SELECT sb.sym_name FROM symbol sb WHERE sb.id=sig.sigsym_base_id AND sb.sym_deletedat=0),'') as sigbasename,"
							." IFNULL((SELECT sq.sym_name FROM symbol sq WHERE sq.id=sig.sigsym_quote_id AND sq.sym_deletedat=0),'') as sigquotename"
							." FROM (userbot ubt, botsignal bsg, `signal` sig)"
							." WHERE ubt.ubtumb_id=".$umbid." AND ubt.ubt_deletedat=0"	// ubt_active as a field returned
							." AND bsg.bsgubt_id=ubt.id AND bsg.bsg_deletedat=0" 				// bsg_active as a field returned
							." AND bsg.bsgsig_id=sig.id AND sig.sig_active=1 AND sig.sig_deletedat=0"
							." ORDER BY sig_name ASC";
				$signals = self::runSql($sql);
				Yii::trace('** getcategorySignals signals: '.print_r($signals,true));

				$signalResult = [];
				foreach($signals as $sigid => $signal) if (is_numeric($sigid)) {
					$catTitle = self::_getCatTitle($categories, $signal['sigcat_ids'], $language);

					$signalResult[ $catTitle ][$sigid] = [
						'signame'   => $signal['sig_name'],
						'maxbots'   => $signal['sig_maxbots'],
						'sigbase'   => $signal['sigbasename'],
						'sigquote'  => $signal['sigquotename'],
						'bsgid'     => $signal['bsgid'],
						'bsgactive' => $signal['bsg_active'],
						'ubtid'     => $signal['id'],
						'ubtactive' => $signal['ubt_active'],
						'ubtname'   => $signal['ubt_name'],
						'3cbotid'   => $signal['ubt_3cbotid']
					];
				}

				$result[$umbid] = [
					'umbname'   => $usermember['umb_name'],
					'mbrtitle'  => $usermember['mbr_title'],
					'startdate' => $usermember['umb_startdate'],
					'enddate'   => $usermember['umb_enddate'],
					'signals'   => $signalResult
				];
			}
		}
		Yii::trace('** getUserSignals usrid='.$usrid.' lang='.$language.' onlyCurr='.($onlyCurrently?'True':'False').' => ', print_r($result,true));
		return $result;
	}

	// for Dropdown select with category optgroup's
  public function getCategorySignalsForUserBotsignal($language='', $mbrid=0, $symQuoteId=0, $symBaseId=0) {
		$result = [];

		$categories = self::_getCategories();
    Yii::trace('** getCategorySignalsForUserBotsignal Categories: '.print_r($categories,true));

		$sql = "SELECT id, sig_name, sigcat_ids"
					." FROM `signal`"
					." WHERE sig_active=1 AND sig_deletedat=0"
					.(!empty($mbrid)      ? " AND LOCATE(',".$mbrid.",',CONCAT(',',sigmbr_ids,','))>0" : "")
					.(!empty($symQuoteId) ? " AND sigsym_quote_id=".$symQuoteId : "")
          .(!empty($symBaseId)  ? " AND sigsym_base_id=".$symBaseId : "");
		$signals = self::runSql($sql);
		Yii::trace('** getCategorySignalsForUserBotsignal signals: '.print_r($signals,true));

		foreach($signals as $sigId => $signal) if (is_numeric($sigId)) {
			$catTitle = self::_getCatTitle($categories, $signal['sigcat_ids'], $language);
			if (!empty($catTitle)) {
				$result[ $catTitle ][ $signal['id'] ] = $signal['sig_name'];
			}	else {
				$result[ $signal['id'] ] = $signal['sig_name'];
			}
    }
    Yii::trace('** getCategorySignalsForUserBotsignal result: '.print_r($result,true));
    return $result;
	}

	public static function getSignalDetails($id=0) {
		$result = [];
		if (!empty($id)) {
			$sql = "SELECT sig.id as id, sig.sig_name, sig.sig_maxbots, sig.sig_description,"
						." IFNULL((SELECT sb.sym_code FROM symbol sb WHERE sb.id=sig.sigsym_base_id AND sb.sym_deletedat=0),'') as sigbasecode,"
						." IFNULL((SELECT sq.sym_code FROM symbol sq WHERE sq.id=sig.sigsym_quote_id AND sq.sym_deletedat=0),'') as sigquotecode"
						." FROM `signal` sig"
						." WHERE id=".$id." AND sig_deletedat=0"
						//.(!empty($mbrid) ? " AND LOCATE(',".$mbrid.",', CONCAT(',',sigmbr_ids,','))>0" : "")
						." LIMIT 1";
			$rows = self::runSql($sql);
			//Yii::trace('** getSignalDetails data: '.print_r($rows, true));
			foreach($rows as $nr => $row) {
				if (is_numeric($nr)) {
					$result = [
						'id'      => $row['id'],
						'name'    => $row['sig_name'],
						'maxbots' => $row['sig_maxbots'],
						'html'    => $row['sig_description'],
						'base'    => $row['sigbasecode'],
						'quote'   => $row['sigquotecode'],
					];
				}
			}
		}
		return $result;
	}

// --------------------------

	public static function getSymbolCodeToPairs() {
		$result = [];
    $sql = "SELECT sym.id, sym.sym_code, CONCAT("
          ."(SELECT sb.sym_code FROM symbol sb WHERE sb.id=sym.symsym_base_id AND sb.sym_deletedat=0 LIMIT 1)"
          .",'_',"
          ."(SELECT sq.sym_code FROM symbol sq WHERE sq.id=sym.symsym_quote_id AND sq.sym_deletedat=0 LIMIT 1)"
          .") as sym_pair"
          ." FROM symbol sym"
          ." WHERE sym.sym_ispair=1 AND sym.sym_deletedat=0"
          ." ORDER BY sym.sym_code";
		$rows = self::runSql($sql);
		Yii::trace('** getSymbolCodeToPairs rows: '.print_r($rows,true));
		foreach($rows as $nr => $row) {
			if (is_numeric($nr)) {
				$result[ $row['sym_code'] ] = $row['sym_pair'];
			}
		}
		Yii::trace('** getSymbolCodeToPairs result: '.print_r($result,true));
		return $result;
	}

	/*public static function getSignallogStates() {
		return [
			Signallog::SLG_STATUS_OK => Yii::t('common', 'Ok'),
			Signallog::SLG_STATUS_ERROR => Yii::t('common', 'Error'),
			Signallog::SLG_STATUS_429 => Yii::t('common', '429'),
			Signallog::SLG_STATUS_418 => Yii::t('common', '418'),
		];
	}*/

	public static function getUserpayStates() {
		return [
      Userpay::UPY_STATE_CART => Yii::t('common', 'Cart'),
      Userpay::UPY_STATE_PAYING => Yii::t('common', 'Paying'),
      Userpay::UPY_STATE_PAID => Yii::t('common', 'Paid'),
      Userpay::UPY_STATE_CANCEL => Yii::t('common', 'Cancel'),
      Userpay::UPY_STATE_REJECT => Yii::t('common', 'Reject'),
      Userpay::UPY_STATE_UNKNOWN => Yii::t('common', 'Unknown'),
		];
	}

	const PAYPROVIDER_NONE = 'none';
	const PAYPROVIDER_PAYPAL = 'paypal';
	const PAYPROVIDER_MOLLIE = 'mollie';
	const PAYPROVIDER_UTRUST = 'utrust';
	const PAYPROVIDER_CRYPTOWALLET = 'cryptowallet';
	const PAYPROVIDER_CRYPTODIRECT = 'cryptodirect';
	const PAYPROVIDER_COINQVEST = 'coinqvest';
	const PAYPROVIDER_COINBASE = 'coinbase';
	const PAYPROVIDER_MORALIS = 'moralis';
	//const PAYPROVIDER_ = '';

	public static function getPayproviders($onlyNone=false) {
    $result = [];
    if ($onlyNone) {
      $result[ self::PAYPROVIDER_NONE ] = Yii::t('common', 'None');
    } else {
      $result[ self::PAYPROVIDER_PAYPAL ] = Yii::t('common', 'Paypal');
    //  if (Yii::$app->user->identity->isDev()) $result[ self::PAYPROVIDER_MOLLIE ] = Yii::t('common', 'Mollie [as Dev]');
			/*if (self::allowWhenMinimal( BackendUser::USR_SITELEVEL_ADMIN ))*/ $result[ self::PAYPROVIDER_UTRUST ] = Yii::t('common', 'Utrust (in crypto)');
			if (Yii::$app->user->identity->isDev()) $result[ self::PAYPROVIDER_CRYPTOWALLET ] = Yii::t('common', 'Crypto Wallet [as Dev]');
      if (Yii::$app->user->identity->isDev()) $result[ self::PAYPROVIDER_CRYPTODIRECT ] = Yii::t('common', 'Crypto Direct [as Dev]');
    //  $result[ self::PAYPROVIDER_MORALIS ] = Yii::t('common', 'Moralis');
    //  $result[ self::PAYPROVIDER_CRYPTODIRECT ] = Yii::t('common', 'Cryptodirect');
    //  $result[ self::PAYPROVIDER_COINQVEST ] = Yii::t('common', 'Coinqvest');
    //  $result[ self::PAYPROVIDER_COINBASE ] = Yii::t('common', 'Coinbase');
    }
    return $result;
  }


  public static function getUserpayProvidertype( $onlyNone=false ) {
		$result = [];
		if ($onlyNone) {
			$result[ self::PAYPROVIDER_NONE ] = Yii::t('common', 'None');
		} else {
			$result[ self::PAYPROVIDER_PAYPAL ] = Yii::t('common', 'Paypal');
		//	if (Yii::$app->user->identity->isDev()) $result[ self::PAYPROVIDER_MOLLIE ] = Yii::t('common', 'Mollie [as Dev]');
		/*	if (self::allowWhenMinimal( BackendUser::USR_SITELEVEL_ADMIN ))*/ $result[ self::PAYPROVIDER_UTRUST ] = Yii::t('common', 'Utrust (in crypto)');
			if (Yii::$app->user->identity->isDev()) $result[ self::PAYPROVIDER_CRYPTODIRECT ] = Yii::t('common', 'Crypto Direct [as Dev]');
		//	$result[ self::PAYPROVIDER_MORALIS ] = Yii::t('common', 'Moralis');
		//	$result[ self::PAYPROVIDER_CRYPTODIRECT ] = Yii::t('common', 'Cryptodirect');
		}
		return $result;
	}

	public static function getMembershipRoles() {
		return [
			Membership::MBR_ROLES_ADM    => Yii::t('common', 'Administrator'),
			Membership::MBR_ROLES_MOD    => Yii::t('common', 'Moderator'),
			Membership::MBR_ROLES_SUP    => Yii::t('common', 'Support'),
			Membership::MBR_ROLES_HLPDSK => Yii::t('common', 'Helpdesk'),
			Membership::MBR_ROLES_DEV    => Yii::t('common', 'Developer'),
			Membership::MBR_ROLES_MKTG   => Yii::t('common', 'Marketing'),
			Membership::MBR_ROLES_MKTPLC => Yii::t('common', 'Marketplace'),
			Membership::MBR_ROLES_TA     => Yii::t('common', 'TA'),
			Membership::MBR_ROLES_TARES  => Yii::t('common', 'TA Results'),
			Membership::MBR_ROLES_CALL   => Yii::t('common', 'Calls'),
			Membership::MBR_ROLES_CHAT   => Yii::t('common', 'Chat'),
		];
	}

	public static function showRoles($roleCodes) {
		$result = [];
		$r = self::getMembershipRoles();
		if (!empty($roleCodes)) {
			$roles = explode(',', $roleCodes);
			foreach($roles as $role) {
				$result[] = $r[$role];
			}
		}
		return implode(', ', $result);
	}

	public static function getPricelistPeriods() {
		return [
    	Pricelist::PRL_PERCODE_1D => Yii::t('common', '1 day'),
    	Pricelist::PRL_PERCODE_2D => Yii::t('common', '2 days'),
    	Pricelist::PRL_PERCODE_3D => Yii::t('common', '3 days'),
    	Pricelist::PRL_PERCODE_1W => Yii::t('common', '1 week'),
    	Pricelist::PRL_PERCODE_2W => Yii::t('common', '2 weeks'),
    	Pricelist::PRL_PERCODE_3W => Yii::t('common', '3 weeks'),
    	Pricelist::PRL_PERCODE_4W => Yii::t('common', '4 weeks'),
    	Pricelist::PRL_PERCODE_1M => Yii::t('common', '1 month'),
    	Pricelist::PRL_PERCODE_2M => Yii::t('common', '2 months'),
    	Pricelist::PRL_PERCODE_3M => Yii::t('common', '3 months'),
    	Pricelist::PRL_PERCODE_6M => Yii::t('common', '6 months'),
    	Pricelist::PRL_PERCODE_1Y => Yii::t('common', '1 year'),
    	Pricelist::PRL_PERCODE_UNL => Yii::t('common', 'unlimited'),
		];
	}

	public static function getStrToTimePricelistPeriod($prl_percode='', $startdate='', $aDayShorter=true, $minimalToday=false) {
		$offset = '';
		switch ($prl_percode) {
      case Pricelist::PRL_PERCODE_1D: $offset = '1 days'; break;
      case Pricelist::PRL_PERCODE_2D: $offset = '2 days'; break;
      case Pricelist::PRL_PERCODE_3D: $offset = '3 days'; break;
      case Pricelist::PRL_PERCODE_1W: $offset = '1 weeks'; break;
      case Pricelist::PRL_PERCODE_2W: $offset = '2 weeks'; break;
      case Pricelist::PRL_PERCODE_3W: $offset = '3 weeks'; break;
      case Pricelist::PRL_PERCODE_4W: $offset = '4 weeks'; break;
      case Pricelist::PRL_PERCODE_1M: $offset = '1 months'; break;
      case Pricelist::PRL_PERCODE_2M: $offset = '2 months'; break;
      case Pricelist::PRL_PERCODE_3M: $offset = '3 months'; break;
      case Pricelist::PRL_PERCODE_6M: $offset = '6 months'; break;
      case Pricelist::PRL_PERCODE_1Y: $offset = '1 years'; break;
      case Pricelist::PRL_PERCODE_UNL: $offset = '99 years'; break;
		}
		$correction = ($aDayShorter ? (24*60*60) : 0); // 1 day shorter if enddate which is an inclusive day of a period
		$date = ((!empty($offset)) ? date('Y-m-d', strtotime($startdate.' + '.$offset) - $correction) : null);
		if ($minimalToday && (self::showDateTime($date, 'unix') < mktime(0))) $date = date('Y-m-d');
		return $date;
	}

	public static function dd($value) {
		return (is_numeric($value) ? (($value<10) ? '0':'') . $value : '00');
	}

	public static function showDateTime($dateTime, $format='dmy') {
		$result = '';
		if (strpos($dateTime, ' ')===false) $dateTime.=' 00:00:00';
		$p = date_parse_from_format('Y-m-d H:i:s', $dateTime);
		//Yii::trace('** showDateTime dateTime='.$dateTime.' p='.print_r($p, true));
		switch ($format) {
			case 'dmY':    $result = self::dd($p['day']).'-'.self::dd($p['month']).'-'.$p['year']; break;
			case 'dmy':    $result = self::dd($p['day']).'-'.self::dd($p['month']).'-'.substr($p['year'],-2,2); break;
      case 'hi':     $result = self::dd($p['hour']).':'.self::dd($p['minute']); break;
      case 'his':    $result = self::dd($p['hour']).':'.self::dd($p['minute']).':'.self::dd($p['second']); break;
      case 'dmyhi':  $result = self::dd($p['day']).'-'.self::dd($p['month']).'-'.$p['year'].' '.self::dd($p['hour']).':'.self::dd($p['minute']); break;
			case 'dmyhis': $result = self::dd($p['day']).'-'.self::dd($p['month']).'-'.$p['year'].' '.self::dd($p['hour']).':'.self::dd($p['minute']).':'.self::dd($p['second']); break;
			case 'unix':   $result = mktime($p['hour'], $p['minute'], $p['second'], $p['month'], $p['day'], $p['year']); break;
		}
		return $result;
	}

	public static function showActiveInPeriod($active=0, $startDate='', $endDate='') {
		$result = [];
		if ($active != 0) {
			Yii::trace('** showActiveInPeriod start='.$startDate.' end='.$endDate);
			if (!empty($startDate) && !empty($endDate)) {
      	$p = date_parse_from_format('Y-m-d', date('Y-m-d')); $nowTS   = mktime($p['hour'], $p['minute'],$p['second'],$p['month'],$p['day'],$p['year']);
				$p = date_parse_from_format('Y-m-d', $startDate);    $startTS = mktime($p['hour'], $p['minute'],$p['second'],$p['month'],$p['day'],$p['year']);
    		$p = date_parse_from_format('Y-m-d', $endDate);      $endTS   = mktime($p['hour'], $p['minute'],$p['second'],$p['month'],$p['day'],$p['year']);
				Yii::trace('** showActiveInPeriod now='.$nowTS.' start='.$startTS.' end='.$endTS);
				if     ($nowTS < $startTS)													$result = ['active'=>'false', 'message'=>Yii::t('common', 'Now inactive, period in future')];
				elseif (($nowTS >= $startTS) && ($nowTS < $endTS)) 	$result = ['active'=>'true',  'message'=>Yii::t('common', 'Now active, period is current')];
				else 																								$result = ['active'=>'false', 'message'=>Yii::t('common', 'Now inactive, period in past')];
			} else {
																														$result = ['active'=>'false', 'message'=>Yii::t('common', 'Now inactive, incomplete period')];
			}
		} else {
																														$result = ['active'=>'false', 'message'=>Yii::t('common', 'Switched off')];
		}
		Yii::trace('** showActiveInPeriod result: '.print_r($result,true));
		return $result;
	}

	public static function runSql($sql='', $withRowcount=false, $withTrace=false) {
    try {
			if (!empty($sql)) {
				if ($withTrace) Yii::trace("runSql: sql=".$sql);
      	if (strncmp(strtoupper($sql),'SELECT ',7)==0) {
        	$rows = \Yii::$app->db->createCommand($sql)->queryAll();
					if ( count($rows)>0 ) {
  	        foreach($rows as $nr => $row) $data[ (isset($row['id']) ? $row['id'] : $nr) ] = $row;
    	      if ($withRowcount) $data['rowcount'] = count($rows);
						if ($withTrace) Yii::trace('runSql count>0: rows: '.print_r($data,true));
        	  return $data;
	        } else {
  	        /*if ($rows)*/ if ($withRowcount) $rows['rowcount'] = count($rows); // even if no rows!
    	      return $rows;
      	  }
	      } else {
  	      return \Yii::$app->db->createCommand($sql)->execute(); // no "select.."
    	  }
			}
    } catch(\yii\db\Exception $e) {
      $msg = 'runSql Error: '.$e->getMessage();
      Yii::error( $msg );
      throw new \Exception($msg /*, $code, $previous*/);
    }
  }

	public static function getUsersWithUserlevel($userlevel = '') {
		$result = [];
		if (!empty($userlevel)) {
			$sql = "SELECT id, TRIM(CONCAT(IFNULL(usr_firstname,''),' ',IFNULL(usr_lastname,''),' (',username,')')) as username"
						." FROM user"
						." WHERE usr_sitelevel='".$userlevel."' AND deleted_at=0"
						." ORDER BY username";
			$users = self::runSql($sql);
			foreach($users as $nr => $row) {
				if (is_numeric($nr)) $result[ $row['id'] ] = $row['username'];
			}
		}
		return $result;
	}


	public static function getCategoriesOfType($catType='', $withHint=false, $language='') {
		$result = [];
		if (!empty($catType)) {
			$sql = "SELECT id, cat_title" .($withHint ? ", cat_description" : ""). ", cat_language FROM category WHERE cat_type='$catType' ".(!empty($language) ? "AND cat_language='".$language."'" : "")." AND cat_deletedat=0";
			$rows = self::runSql($sql);
			foreach($rows as $nr => $row) {
				if(is_numeric($nr)) $result[ $row['id'] ] = (empty($language) ? "[".substr($row['cat_language'],0,2)."] " : "" ) . $row['cat_title'] . ($withHint && !empty($row['cat_description']) ? " [".$row['cat_description']."]" : "");
			}
		}
		//Yii::trace('getCategoriesOfType result=' . print_r($result,true));
		return $result;
	}

  public static function getSymbolCodes($withName=false) {
    $result = [];
    $sql = "SELECT id, sym_code" .($withName ? ", sym_name" : ""). " FROM symbol WHERE sym_deletedat=0";
    $rows = self::runSql($sql);
    foreach($rows as $nr => $row) {
      if(is_numeric($nr)) $result[ $row['id'] ] = $row['sym_code'] . ($withName && !empty($row['sym_name']) ? " [".$row['sym_name']."]" : "");
    }
    //Yii::trace('getSymbolCodes result=' . print_r($result,true));
    return $result;
  }

  public static function getMembershipsForLanguage($language='', $asCodes=false) {
    $result = [];
    $sql = "SELECT id, ".($asCodes ? "mbr_code":"mbr_title")." as mbr_title, mbr_language, mbr_order FROM membership WHERE mbr_active=1 ".(!empty($language) ? "AND mbr_language='".$language."'" : "")." AND mbr_deletedat=0 ORDER BY mbr_language, mbr_order, mbr_code";
    $rows = self::runSql($sql);
    foreach($rows as $nr => $row) {
      if(is_numeric($nr)) $result[ $row['id'] ] = (empty($language) ? "[".substr($row['mbr_language'],0,2)."] " : "" ) . $row['mbr_title'];
    }
    Yii::trace('getMembershipsForLanguage language='.$language.' result=' . print_r($result,true));
    return $result;
  }

	public static function getPricelistOfMembership($id=0) {
    $result = [];
		// Only show with empty dicountcodes!!
		$userIsMinimalAdmin = self::allowWhenMinimal( BackendUser::USR_SITELEVEL_ADMIN );
    $sql = "SELECT prl.id, prl.prl_name, prl.prl_pretext, prl.prl_posttext, prl.prl_price, prl.prl_percode, prl.prl_allowedtimes, sym.sym_code FROM (pricelist prl, symbol sym) "
					."WHERE prl.prlmbr_id=".$id." AND (prl.prl_active=1".($userIsMinimalAdmin ? " OR prl.prl_active4admin=1":"").") AND prl.prl_discountcode='' AND (NOW() BETWEEN prl.prl_startdate and prl.prl_enddate) AND prl.prl_deletedat=0 AND sym.id=prl.prlsym_id AND sym.sym_deletedat=0";
    $rows = self::runSql($sql);
		//Yii::trace('getPricelistOfMembership id='.$id.' rows='.print_r($rows,true));
    foreach($rows as $nr => $row) {
      if(is_numeric($nr)) $result[ $row['id'] ] = [
				'name' => $row['prl_name'],
				'pretext' => $row['prl_pretext'],
				'posttext' => $row['prl_posttext'],
				'price' => $row['prl_price'],
				'symbol' => $row['sym_code'],
				'allowedtimes' => $row['prl_allowedtimes'],
				'percode' => $row['prl_percode'],
			];
    }
		Yii::trace('getSymbolCodes result=' . print_r($result,true));
    return $result;
	}

	public static function getMembershipQuantitiesForUser($usrid=0) {
		$result = [];
		if (!empty($usrid)) {
			$sql = "SELECT upymbr_id, upyprl_id, count(*) as quantity FROM userpay WHERE upyusr_id=".$usrid." AND upy_state='".Userpay::UPY_STATE_PAID."' AND upy_deletedat=0 GROUP BY upymbr_id, upyprl_id";
			$rows = self::runSql($sql);
			foreach($rows as $nr => $row) {
				$result[ $row['upymbr_id'] ][ $row['upyprl_id'] ] = $row['quantity'];
			}
		}
		Yii::trace('** getMembershipQuantitiesForUser usrid='.$usrid.' => '.print_r($result, true));
		return $result;
	}

	public static function checkDiscountcode($mbrId=0, $usrId=0, $symId=0, $perCode='', $discountCode='') {
		$prlid = 0;
		$price = 'na';
		$usedcount = 0;
		$allowedtimes = -1;
		Yii::trace('** checkDiscountcode mbrId='.$mbrId." usrId=".$usrId.' symId='.$symId.' perCode='.$perCode.' discountCode='.$discountCode);
		if (!empty($mbrId) && !empty($usrId) && !empty($symId) && !empty($perCode) && !empty($discountCode)) {
			$userIsMinimalAdmin = self::allowWhenMinimal( BackendUser::USR_SITELEVEL_ADMIN );
			$sql = "SELECT prl.id, prl.prl_price, prl.prl_allowedtimes, "
						." IFNULL((SELECT count(*) FROM userpay upy WHERE upy.upyusr_id=".$usrId." AND upy.upy_state='".Userpay::UPY_STATE_PAID."'"
						."   AND UPPER(SUBSTRING_INDEX(upy.upy_discountcode,'|',1))=UPPER('".$discountCode."')"
						."   AND upy.upy_deletedat=0),0) as usedcount"
						." FROM pricelist prl"
						." WHERE prl.prlmbr_id='".$mbrId."' AND prlsym_id='".$symId."' AND UPPER(prl.prl_discountcode)=UPPER('".$discountCode."') AND prl.prl_percode='".$perCode."'"
						." AND (prl.prl_active=1".($userIsMinimalAdmin ? " OR prl.prl_active4admin=1":"").") AND (NOW() BETWEEN prl.prl_startdate and prl.prl_enddate) AND prl.prl_deletedat=0";
			$rows = self::runSql($sql);
			foreach($rows as $nr => $row) {
				if (is_numeric($nr)) {
					$prlid = $row['id'];
					$price = ''.$row['prl_price'];
					$usedcount = $row['usedcount'];
					$allowedtimes = $row['prl_allowedtimes'];
				}
			}
		}
		$result = ['prlid'=>$prlid, 'price'=>$price, 'usedcount'=>$usedcount, 'allowedtimes'=>$allowedtimes];
		Yii::trace('** checkDiscountcode result='.print_r($result,true));
		return $result;
	}

	public static function getUsermembers4User($userId, $onlyActive=true) {
		$result = [];
		if (!empty($userId)) {
			$sql = "SELECT umb.id, CONCAT(mbr.mbr_title, IF(umb.umb_name!='', CONCAT(' (',umb.umb_name,')'),'')) as name"
						." FROM usermember umb, membership mbr"
						." WHERE umb.umbusr_id='".$userId."' AND umb_deletedat=0 ".($onlyActive ? "AND umb.umb_active=1 " : "")
						." AND umb.umbmbr_id=mbr.id AND mbr.mbr_deletedat=0 "
						." ORDER BY 2";
			$rows = self::runSql($sql);
			foreach($rows as $nr => $row) {
				if (is_numeric($nr)) $result[ $row['id'] ]  = ''.$row['name'];
			}
		}
		Yii::trace('** getUsermembers4User result: '.print_r($result, true));
		return $result;
	}

}
