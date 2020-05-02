<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "vps".
 *
 * @property string $id
 * @property string $user_id
 * @property string $server_id
 * @property string $datastore_id
 * @property string $os_id
 * @property string $plan_id
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property integer $plan_type
 * @property integer $vps_ram
 * @property integer $vps_cpu_mhz
 * @property integer $vps_cpu_core
 * property integer $vps_hard
 * property integer $vps_band_width
 * property integer $reset_at
 * @property integer $power
 * @property string $disk
 * @property Datastore $datastore
 * @property Os $os
 * @property Plan $plan
 * @property Server $server
 * @property User $user
 * @property VpsAction[] $vpsActions
 * @property VpsIp[] $vpsIps
 */
class Vps extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    const ONLINE = 1;
    const OFFLINE = 2;

    const CAN_SNAPSHOT = 1;
    const CAN_NOT_SNAPSHOT = 2;

    public $ip_id;
    public $view;

    public function afterFind()
    {
        $this->password = Yii::$app->security->decryptByPassword(base64_decode($this->password), Yii::$app->params['secret']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vps';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'server_id', 'datastore_id', 'ip_id', 'reset_at', 'snapshot'], 'required'],
            [['user_id', 'server_id', 'datastore_id','hard','vps_ram','vps_cpu_core','vps_cpu_mhz','vps_band_width', 'ip_id', 'os_id', 'plan_id', 'created_at', 'updated_at', 'status', 'reset_at', 'power', 'extra_bw', 'notify_at'], 'integer'],
            [['hostname', 'password', 'disk'], 'string'],
            [['extra_bw'], 'integer'],
            [['extra_bw'], 'default', 'value' => '0'],
            [['view'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'server_id' => Yii::t('app', 'Server ID'),
            'datastore_id' => Yii::t('app', 'Datastore ID'),
            'ip_id' => Yii::t('app', 'Ip ID'),
            'os_id' => Yii::t('app', 'Os ID'),
            'plan_id' => Yii::t('app', 'Plan ID'),
            'hostname' => Yii::t('app', 'Host Name'),
            'password' => Yii::t('app', 'Password'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'view' => Yii::t('app', 'Redirect to VM view for install OS'),
	    'reset_at' => Yii::t('app', 'Reset At'),
	    'power' => Yii::t('app', 'Power'),
            'disk' => Yii::t('app', 'Disk'),
            'snapshot' => Yii::t('app', 'Snapshot'),
            'extra_bw' => Yii::t('app', 'Extra Bandwidth'),
            'notify_at' => Yii::t('app', 'Notify At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatastore()
    {
        return $this->hasOne(Datastore::className(), ['id' => 'datastore_id']);
    }

    public function getEmail()
    {
        return $this->hasOne(UserEmail::className(), ['user_id' => 'user_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOs()
    {
        return $this->hasOne(Os::className(), ['id' => 'os_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlan()
    {
        return $this->hasOne(Plan::className(), ['id' => 'plan_id']);
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(VpsAction::className(), ['vps_id' => 'id']);
    }

    public function getSuspends()
    {
        return $this->hasMany(VpsAction::className(), ['vps_id' => 'id'])->suspend()->descending();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIps()
    {
        return $this->hasMany(Ip::className(), ['id' => 'ip_id'])->viaTable('vps_ip', ['vps_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIp()
    {
        return $this->hasOne(Ip::className(), ['id' => 'ip_id'])->viaTable('vps_ip', ['vps_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBandwidths()
    {
        return $this->hasMany(Bandwidth::className(), ['id' => 'vps_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\VpsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\VpsQuery(get_called_class());
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['server_id', 'datastore_id', 'ip_id', 'os_id', 'plan_id', 'hostname', 'password', 'status', 'reset_at', 'disk', 'snapshot', 'view', 'extra_bw', 'notify_at'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        $this->password = base64_encode(Yii::$app->security->encryptByPassword($this->password, Yii::$app->params['secret']));
        return parent::beforeSave($insert);
    }

    public function customSave()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$this->save(false)) {
                throw new \Exception('Cannot save vps');
            }

            $oldIp = VpsIp::find()->where(['vps_id' => $this->id])->one();

            if ($oldIp) {
                $oldIp->delete();
            }

            $ip = new VpsIp;
            $ip->vps_id = $this->id;
            $ip->ip_id = $this->ip_id;

            if (!$ip->save(false)) {
                throw new \Exception('Cannot save vps ip');
            }

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }

    public function getIsActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function getIsInactive()
    {
        return $this->status == self::STATUS_INACTIVE;
    }

    public function getIsOnline()
    {
    	return $this->power == self::ONLINE;
    }

    public function getIsOffline()
    {
    	return $this->power == self::OFFLINE;
    }

    public function getCanSnapshot()
    {
        return $this->snapshot == self::CAN_SNAPSHOT;
    }

    public function getCannotSnapshot()
    {
        return $this->snapshot == self::CAN_NOT_SNAPSHOT;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
        ];
    }

    public static function getSnapshotList()
    {
        return [
            self::CAN_SNAPSHOT => Yii::t('app', 'Yes'),
            self::CAN_NOT_SNAPSHOT => Yii::t('app', 'No'),
        ];
    }

    public static function getDisks()
    {
        return [
            'thin' => Yii::t('app', 'Thin Provision'),
            'think' => Yii::t('app', 'Think Provision Lazy Zeroed'),
            'think x' => Yii::t('app', 'Think Provision Eager Zeroed'),
        ];
    }

    public static function findByIp($ip)
    {
        return self::find()
            ->innerJoin('vps_ip a', 'a.vps_id = vps.id')
            ->innerJoin('ip b', 'b.id = a.ip_id')
            ->where(['b.ip' => $ip])
            ->one();
    }
}
