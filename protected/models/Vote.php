<?php

/**
 * This is the model class for table "vote".
 *
 * The followings are the available columns in table 'vote':
 * @property integer $id
 * @property integer $user_id
 * @property integer $restaurant_id
 * @property integer $rating
 * @property string $create_datetime
 */
class Vote extends CActiveRecord
{
	public $old_rating_value;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Vote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, restaurant_id, rating', 'required'),
			array('user_id, restaurant_id', 'numerical', 'integerOnly'=>true),
			array('rating', 'numerical', 'integerOnly'=>true,  'min'=>1, 'max'=>5),
			array('create_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, restaurant_id, rating, create_datetime', 'safe', 'on'=>'search'),
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
				'user' => array(self::BELONGS_TO, 'User', 'user_id'),
				'restaurant' => array(self::BELONGS_TO, 'Restaurant', 'restaurant_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'restaurant_id' => 'Restaurant',
			'rating' => 'Rating',
			'create_datetime' => 'Create Datetime',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('restaurant_id',$this->restaurant_id);
		$criteria->compare('rating',$this->rating);
		$criteria->compare('create_datetime',$this->create_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getLastVotes()
	{
		$criteria = new CDbCriteria(array(
				'limit'=> 5,
				'offset'=> 0,
				'order'=>'create_datetime DESC',
				'with'=>array('user','restaurant'),
		));
	
		$lastVotesDataProvider = new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
				'pagination'=>false,
		));
	
		return $lastVotesDataProvider->getData();
	}
}