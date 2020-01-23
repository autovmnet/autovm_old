<?php
namespace app\modules\site\assets;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
	public $sourcePath = '@app/modules/site/assets/source';

    public $css = [
		'css/simple-alert.css',
        'css/materialize.min.css',
        'js/plugins/morris/morris.css',
        'css/style.css',
    ];

    public $js = [
        'js/simple-alert.js',
        'js/materialize.min.js',
        'js/raphael.js',
        'js/plugins/morris/morris.js',
        'js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'app\assets\FontAwesomeAsset',
    ];
}
