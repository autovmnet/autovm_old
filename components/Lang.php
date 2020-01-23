<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Lang extends Component
{
	public $langs = ['en' => 'English', 'fa' => 'Persian', 'tr' => 'Turkish', 'ch' => 'China'];
	public $rtl = ['fa'];

	public function init()
	{
		$session = Yii::$app->getSession();

		$language = $session->get('language');

		$allowed = in_array($language, array_keys($this->langs));

		if ($allowed) {
			Yii::$app->language = $language;
		} else {
			Yii::$app->language = Yii::$app->setting->language;
		}
	}

	public function getIsRtl()
	{
		return in_array(Yii::$app->language, $this->rtl);
	}
}
