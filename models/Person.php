<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property int|null $person_id
 * @property int $is_foreign
 * @property string $name
 * @property string|null $rg
 * @property string|null $issuing_authority
 * @property string|null $cpf
 * @property string|null $birth_date
 * @property string|null $marital_status
 * @property string|null $sex
 * @property string|null $nationality
 * @property string|null $place_of_birth
 * @property string|null $notes
 * @property int $is_company_partner
 * @property int $is_guarantor
 * @property string|null $profession
 * @property string|null $cell_number
 * @property string|null $job_title
 * @property string|null $photo
 * @property string|null $resume
 * @property int $is_individual_customer
 * @property int $has_login_access
 * @property int $is_active
 * @property int|null $customer_id
 * @property int|null $company_id
 * @property string $created_at
 * @property string $updated_at
 */
class Person extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['person_id', 'is_foreign', 'is_company_partner', 'is_guarantor', 'is_individual_customer', 'has_login_access', 'is_active', 'customer_id', 'company_id'], 'integer'],
            [['birth_date', 'created_at', 'updated_at'], 'safe'],
            [['marital_status', 'sex', 'notes', 'resume'], 'string'],
            [['name', 'photo'], 'string', 'max' => 255],
            [['rg', 'cell_number'], 'string', 'max' => 20],
            [['issuing_authority'], 'string', 'max' => 50],
            [['cpf'], 'string', 'max' => 14],
            [['nationality', 'place_of_birth', 'profession', 'job_title'], 'string', 'max' => 100],
            [['is_foreign', 'is_company_partner', 'is_guarantor', 'is_individual_customer', 'has_login_access'], 'default', 'value' => 0],
            [['is_active'], 'default', 'value' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'person_id' => 'Person ID',
            'is_foreign' => 'Is Foreign',
            'name' => 'Name',
            'rg' => 'RG',
            'issuing_authority' => 'Issuing Authority',
            'cpf' => 'CPF',
            'birth_date' => 'Birth Date',
            'marital_status' => 'Marital Status',
            'sex' => 'Sex',
            'nationality' => 'Nationality',
            'place_of_birth' => 'Place Of Birth',
            'notes' => 'Notes',
            'is_company_partner' => 'Is Company Partner',
            'is_guarantor' => 'Is Guarantor',
            'profession' => 'Profession',
            'cell_number' => 'Cell Number',
            'job_title' => 'Job Title',
            'photo' => 'Photo',
            'resume' => 'Resume',
            'is_individual_customer' => 'Is Individual Customer',
            'has_login_access' => 'Has Login Access',
            'is_active' => 'Is Active',
            'customer_id' => 'Customer ID',
            'company_id' => 'Company ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
