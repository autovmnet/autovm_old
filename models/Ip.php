<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ip".
 *
 * @property string $id
 * @property string $server_id
 * @property string $ip
 * @property string $gateway
 * @property string $netmask
 * @property string $mac_address
 * @property integer $is_public
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_dhcp
 *
 * @property Server $server
 * @property VpsIp[] $vpsIps
 */
class Ip extends \yii\db\ActiveRecord
{
    const IS_PUBLIC = 1;
    const IS_NOT_PUBLIC = 2;

    const IS_DHCP = 1;
    const IS_NOT_DHCP = 2;

    const IS_MAIN = 1;
    const IS_SECOND = 2;

    public $to;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ip';
    }

    public function search($params)
    {
        $this->scenario='search';
        $query = Ip::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['server_id', 'ip', 'gateway', 'netmask'], 'required'],
            [['ip'], 'unique'],
            [['server_id', 'is_public', 'is_dhcp', 'network', 'created_at', 'updated_at'], 'integer'],
            [['ip','to'], 'string', 'max' => 45],
            [['ip', 'to', 'gateway', 'netmask'], 'match', 'pattern' => '#^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})$#'],
            [['gateway', 'netmask', 'mac_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'server_id' => Yii::t('app', 'Server ID'),
            'ip' => Yii::t('app', 'Ip'),
            'to' => Yii::t('app', 'To'),
            'gateway' => Yii::t('app', 'Gateway'),
            'netmask' => Yii::t('app', 'Netmask'),
            'mac_address' => Yii::t('app', 'Mac Address'),
            'is_public' => Yii::t('app', 'Is Public'),
            'network' => Yii::t('app', 'Network'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'is_dhcp' => Yii::t('app', 'Is Dhcp'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServer()
    {
        return $this->hasOne(Server::className(), ['id' => 'server_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpsIps()
    {
        return $this->hasMany(VpsIp::className(), ['ip_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\IpQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\IpQuery(get_called_class());
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['ip', 'gateway', 'to', 'netmask', 'mac_address', 'is_public', 'is_dhcp', 'network'],
            'search'=>[]
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getIsPublic()
    {
        return $this->is_public == self::IS_PUBLIC;
    }

    public function getIsMain()
    {
        return $this->network == self::IS_MAIN;
    }

    public static function getPublicYesNo()
    {
        return [
            self::IS_PUBLIC => Yii::t('app', 'Yes'),
            self::IS_NOT_PUBLIC => Yii::t('app', 'No'),
        ];
    }

    public static function getNetworks()
    {
        return [
            self::IS_MAIN => Yii::t('app', 'Main Network'),
            self::IS_SECOND => Yii::t('app', 'Second Network'),
        ];
    }

    public static function getDhcpYesNo()
    {
        return [
            self::IS_NOT_DHCP => Yii::t('app', 'No'),
            self::IS_DHCP => Yii::t('app', 'Yes'),
        ];
    }
}
