<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $name
 * @property string|null $description
 * @property float|null $price
 * @property int|null $active
 * @property int|null $is_customer_consumable
 * @property int|null $category_id
 * @property int|null $company_id
 * @property int|null $cost_center_id
 * @property string|null $nfse_description
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id'], 'required'],
            [['product_id', 'active', 'is_customer_consumable', 'category_id', 'company_id', 'cost_center_id'], 'integer'],
            [['price'], 'number'],
            [['description', 'nfse_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['product_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID (API)',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'active' => 'Active',
            'is_customer_consumable' => 'Consumer Consumable',
            'category_id' => 'Category ID',
            'company_id' => 'Company ID',
            'cost_center_id' => 'Cost Center ID',
            'nfse_description' => 'NFSe Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
