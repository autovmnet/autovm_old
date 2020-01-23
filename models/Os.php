<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "os".
 *
 * @property string $id
 * @property string $name
 * @property string $type
 * @property string $operation_system
 * @property string $username
 * @property string $password
 * @property string $adapter
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Vps[] $vps
 */
class Os extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'os';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'operation_system', 'guest', 'username', 'password', 'adapter'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name', 'type', 'operation_system', 'guest', 'username', 'password', 'adapter'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
			'username' => Yii::t('app', 'Username'),
			'password' => Yii::t('app', 'Password'),
            'adapter' => Yii::t('app', 'Adapter'),
            'guest' => Yii::t('app', 'Guest'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'operation_system' => Yii::t('app', 'Operating System'),

        ];
    }
   

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['os_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\OsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\OsQuery(get_called_class());
    }

    public static function getOperationSystem()
    {
     	$list =  array(
                'windows 2003 32 bit',
                'windows 2008 32 bit',
                'windows 2008 64 bit',
                'windows 2012 64 bit',
                'windows 2016 64 bit',
                'windows 2019 64 bit',
                'windows 7 32 bit',
                'windows 7 64 bit',
                'windows 8 32 bit',
                'windows 8 64 bit',
                'ubuntu 16.04 32 bit',
                'ubuntu 16.04 64 bit',
                'ubuntu 18.04 64 bit',
                'ubuntu 19.04 64 bit',
                'centos 7 64 bit',  
                'centos 8 64 bit',           
                'debian 8.5 32 bit',
                'debian 8.5 64 bit',
                'debian 9.6 32 bit',
                'debian 9.6 64 bit',   
                'debian 9.9 32 bit',
                'debian 9.9 64 bit',
                'debian 10 64 bit',             
            );

	    return array_combine($list, $list);
    }
    
    public static function getAdapters()
    {
        return [
            'e1000' => 'e1000',  
            'vmxnet3' => 'vmxnet3',  
            'e1000e' => 'e1000e',  
        ];
    }
    
    public static function getGuests()
    {
        return [
            'debian6Guest' => 'Debian 6 32 bit',
            'debian6_64Guest' => 'Debian 6 64 bit',
            'debian8Guest' => 'Debian 8 32 bit',
            'debian8_64Guest' => 'debian 8 64 bit',
            'debian9Guest' => 'Debian 9 32 bit',
            'debian9_64Guest' => 'Debian 9 64 bit',
            'debian10Guest' => 'Debian 10 32 bit',
            'debian10_64Guest' => 'Debian 10 64 bit',
            'centos7_64Guest' => 'Centos 7 64 bit',
            'centos8_64Guest' => 'Centos 8 64 bit',
            'ubuntuGuest' => 'Ubuntu 32 bit',
            'ubuntu64Guest' => 'Ubuntu 64 bit',
            'winNetEnterpriseGuest' => 'Windows 2003 32 bit',
            'winNetEnterprise64Guest' => 'Windows 2003 64 bit',
            'winLonghornGuest' => 'Windows 2008 32 bit',
            'winLonghorn64Guest' => 'Windows 2008 64 bit',
            'windows8Server64Guest' => 'Windows 2012 64 bit',
            'windows9Server64Guest' => 'Windows 2016 64 bit',
            'windows7Guest' => 'Windows 7 32 bit',
            'windows7_64Guest' => 'Windows 7 64 bit',
            'windows8Guest' => 'Windows 8 32 bit',
            'windows8_64Guest' => 'Windows 8 64 bit',
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
        ];
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'type', 'username', 'password', 'adapter', 'guest', 'status'],
        ];
    }
        
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
 
