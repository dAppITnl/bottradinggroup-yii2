<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
				'https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js',
				'https://unpkg.com/moralis/dist/moralis.js',
				'js/botsignals_moralis.js',
				'https://unpkg.com/@web3auth/web3auth@0.2.3/dist/web3auth.umd.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
