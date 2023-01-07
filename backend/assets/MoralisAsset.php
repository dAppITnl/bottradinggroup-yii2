<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MoralisAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
    ];
    public $js = [
			'https://cdn.jsdelivr.net/npm/web3@latest/dist/web3.min.js',
			'https://unpkg.com/moralis/dist/moralis.js',
			'/js/backend_moralis.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
