<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $order_id
 * @property string $email
 * @property integer $product_id
 * @property string $price
 * @property integer $currency_id
 * @property string $created_at
 * @property string $modified_at
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Currency $currency
 * @property Products $product
 */
class Orders extends CActiveRecord
{
    public $from_date;
    public $to_date;

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Orders the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'orders';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email, product_id, created_at, from_date, to_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'currency' => array(self::BELONGS_TO, 'Currency', 'currency_id'),
            'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'order_id' => 'ID',
            'email' => 'E-mail',
            'product_id' => 'Пакет услуг',
            'price' => 'Стоимость',
            'currency_id' => 'Валюта',
            'created_at' => 'Дата покупки',
            'modified_at' => 'Обновлено',
            'status' => 'Статус',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        if (!empty($this->from_date) && empty($this->to_date)) {
            $criteria->condition = "created_at >= '$this->from_date'";
        } elseif (!empty($this->to_date) && empty($this->from_date)) {
            $criteria->condition = "created_at <= '$this->to_date 23:59:59'";
        } elseif(!empty($this->to_date) && !empty($this->from_date)) {
            $criteria->condition = "created_at  >= '$this->from_date' AND created_at <= '$this->to_date 23:59:59'";
        }

        if ($this->product_id) {
            $criteria->having = 'product_id=' . $this->product_id;
        }
        if ($this->email) {
            $criteria->having = "email LIKE '%" . addslashes($this->email) . "%'";
        }

        $criteria->with = Products::model()->findAll();
        $criteria->with = Currency::model()->findAll();

        $pagination = new CPagination;
        $pagination->pageSize = Yii::app()->params['pageSize'];

        $result = new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => $pagination,
        ]);

        $data = $result->getData();

        if (!isset($data[0])) {
            return $result;
        }

        $currencyList = [];
        $defaultCurrencyCode = '';
        $currency = Currency::model()->findAll();

        foreach ($currency as $val) {
            $currencyList[$val->currency_id] = [
                'ratio' => $val->ratio,
                'code' => $val->code,
            ];
            if ($val->ratio == 1) {
                $defaultCurrencyCode = $val->code;
            }
        }

        // count total in default currency
        $total = 0;
        foreach ($data as $key=>$val) {
            $price = $val->getPrice();

            if ($currencyList[$val->currency_id]['ratio'] != 1) {
                $price = $currencyList[$val->currency_id]['ratio'] * $price;
            }

            $total += $price;
        }
        $total = $total . ' ' . $defaultCurrencyCode . ' (Default currency)';

        // clone Order object
        // erase data
        $new = clone $data[0];

        foreach ($new->product->getAttributes() as $attribute=>$val) {
            $new->product->setAttribute($attribute, '');
        }
        foreach ($new->currency->getAttributes() as $attribute=>$val) {
            $new->currency->setAttribute($attribute, '');
        }
        foreach ($new->getAttributes() as $attribute=>$val) {
            $new->setAttribute($attribute, '');
        }

        $new->price = $total;
        $new->product->price = '<b>Итого:</b>';

        //add to last row of table
        array_push($data, $new);

        $result->setData($data);

        return $result;

    }

    public function getPrice() {
        return $this->price;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public static function getTotal($ids)
    {
        $results = Orders::model()->findAll('order_id IN (' . join(',', $ids) . ')');

        $total = 0;
        foreach ($results as $key=>$val) {
            $total += $val->price;
        }

        return $total;
    }
}
