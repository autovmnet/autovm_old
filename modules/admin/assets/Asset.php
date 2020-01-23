<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@app/modules/admin/assets/source';

    public $css = [
		'css/simple-alert.css',
        'css/style.css',
    ];

    public $js = [
        'js/simple-alert.js',
        'js/main.js',
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\FontAwesomeAsset',
    ];
}
