<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $product_id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property integer $currency_id
 * @property string $created_at
 * @property string $modified_at
 * @property string $deleted_date
 * @property string $image_ext
 * @property string $image
 *
 * The followings are the available model relations:
 * @property Orders[] $orders
 * @property Currency $currency
 */
class Products extends CActiveRecord
{
    public $image;
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Products the static model class
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
        return 'products';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name, description', 'required', 'message' => 'Необходимо заполнить'),
            array('currency_id', 'required' ,'message' => 'Необходимо выбрать валюту'),
            array('currency_id', 'numerical', 'integerOnly' => true, 'message' => 'Необходимо заполнить'),
            array('name', 'length', 'max' => 255, 'message' => 'Максимальный размер 255 символов'),
            array('price', 'length', 'max' => 19, 'message' => 'Максимальный размер 19 символов'),
            array('price', 'match', 'pattern' => '/^\d{0,19}(\.\d{1,4})?$/', 'message' => 'Неверный формат цены'),
            array('created_at, modified_at', 'safe'),
            array('image', 'file', 'types' => 'jpg,jpeg,gif,png', 'allowEmpty' => true, 'maxSize' => 3145728, 'tooLarge' => 'Максимальный размер файла 3Mb', 'on' => 'upload'),

            array('product_id, name, description, price', 'safe', 'on' => 'search'),
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
            'orders' => array(self::HAS_MANY, 'Orders', 'product_id'),
            'currency' => array(self::BELONGS_TO, 'Currency', 'currency_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'product_id' => 'ID',
            'name' => 'Пакет услуг',
            'description' => 'Описание',
            'price' => 'Цена',
            'currency_id' => 'Валюта',
            'created_at' => 'Создан',
            'modified_at' => 'Обновлен',
            'image' => 'Изображение',
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

        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('price', $this->price, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}
