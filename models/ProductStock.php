<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "product_stock".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $sale_id
 * @property int $qtd
 * @property string|null $observation
 * @property string|null $deleted_at
 * @property string|null $update_at
 * @property string|null $created_at
 */
class ProductStock extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['qtd'], 'required'],
            [['product_id', 'sale_id', 'qtd'], 'integer'],
            [['observation'], 'string'],
            [['deleted_at', 'update_at', 'created_at'], 'safe'],
            [['qtd'], 'compare', 'compareValue' => 0, 'operator' => '!=', 'message' => 'A quantidade não pode ser igual a zero.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'sale_id' => 'Sale ID',
            'qtd' => 'Qtd',
            'observation' => 'Observation',
            'deleted_at' => 'Deleted At',
            'update_at' => 'Update At',
            'created_at' => 'Created At',
        ];
    }
}
