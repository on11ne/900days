<?php

class UsersService extends CActiveRecord
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'users_service';
	}

	public function rules()
	{
		return array(
			array('id, service, service_id', 'required'),
			array('id', 'length', 'max' => 11),
			array('service, service_id', 'length', 'max' => 100),
            array('service, service_id', 'filter', 'filter' => 'trim'),
            array('service, service_id', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify')),
            array('service, service_id', 'filter', 'filter' => 'strip_tags'),

			array('id, service, service_id', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'service' => 'Service',
			'service_id' => 'Service',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('service', $this->service, true);
		$criteria->compare('service_id', $this->service_id, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}