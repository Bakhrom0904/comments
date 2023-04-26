<?php

namespace frontend\models;

use Yii;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $message
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Comments extends \yii\db\ActiveRecord
{
    public $verifyCode;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_MODERATION = 'moderation';
    const STATUS_DEFAULT=0;
    const STATUS_ACCEPT=1;
    const STATUS_CANCEL=2;
    

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['status'], 'integer'],
            ['email','email'],
            [['name','phone','email','message'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],
            ['verifyCode', 'captcha'],
            ['message', 'filter', 'filter' => 'strip_tags'],
            ['name','match','pattern'=>'/[a-zA-Zа-яА-Я‘]+/'],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'message' => 'Message',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert)
    {

        if ($this->isNewRecord){
            $this->created_at = time(); 
        }else{
            $this->updated_at = time();
        }

        $this->phone= preg_replace('/[^0-9]+/', '', $this->phone);

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE =>['name','email','phone','message','verifyCode'],
            self::SCENARIO_MODERATION=>['name','email','phone','message'],
        ];
    }



}
