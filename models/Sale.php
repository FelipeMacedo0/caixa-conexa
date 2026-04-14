<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "sale".
 *
 * @property int $id
 * @property int $sale_id
 * @property string|null $status
 * @property float|null $discount_value
 * @property float|null $amount
 * @property float|null $quantity
 * @property string|null $notes
 * @property int|null $product_id
 * @property int|null $customer_id
 * @property int|null $requester_id
 * @property int|null $seller_id
 * @property int|null $contract_id
 * @property int|null $recurring_sale_id
 * @property float|null $original_amount
 * @property string|null $reference_date
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Sale extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sale_id'], 'required'],
            [['sale_id', 'product_id', 'customer_id', 'requester_id', 'seller_id', 'contract_id', 'recurring_sale_id'], 'integer'],
            [['discount_value', 'amount', 'quantity', 'original_amount'], 'number'],
            [['notes'], 'string'],
            [['reference_date', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 50],
            [['sale_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sale_id' => 'Sale ID (API)',
            'status' => 'Status',
            'discount_value' => 'Discount Value',
            'amount' => 'Amount',
            'quantity' => 'Quantity',
            'notes' => 'Notes',
            'product_id' => 'Product ID',
            'customer_id' => 'Customer ID',
            'requester_id' => 'Requester ID',
            'seller_id' => 'Seller ID',
            'contract_id' => 'Contract ID',
            'recurring_sale_id' => 'Recurring Sale ID',
            'original_amount' => 'Original Amount',
            'reference_date' => 'Reference Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Product]].
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['product_id' => 'product_id']);
    }

    /**
     * Gets query for [[Customer]].
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['customer_id' => 'customer_id']);
    }
}
