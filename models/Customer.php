<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $name
 * @property string|null $trade_name
 * @property string|null $pronunciation
 * @property string|null $field_of_activity
 * @property string|null $notes
 * @property string|null $cell_number
 * @property string|null $website
 * @property int|null $has_login_access
 * @property string|null $login
 * @property string|null $password
 * @property int|null $company_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Customer extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'has_login_access', 'company_id'], 'integer'],
            [['notes'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'trade_name', 'pronunciation', 'field_of_activity', 'cell_number', 'website', 'login', 'password'], 'string', 'max' => 255],
            [['customer_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID (API)',
            'name' => 'Name',
            'trade_name' => 'Trade Name',
            'pronunciation' => 'Pronunciation',
            'field_of_activity' => 'Field Of Activity',
            'notes' => 'Notes',
            'cell_number' => 'Cell Number',
            'website' => 'Website',
            'has_login_access' => 'Has Login Access',
            'login' => 'Login',
            'password' => 'Password',
            'company_id' => 'Company ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
