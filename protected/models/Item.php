<?php

/**
 * This is the model class for table "items".
 *
 * The followings are the available columns in table 'items':
 * @property integer $id
 * @property string $created
 * @property string $edited
 * @property integer $active
 * @property string $title
 * @property integer $type_id
 * @property string $body
 * @property string $media_descr
 * @property string $media_data
 * @property string $start
 * @property string $end
 * @property string $address
 * @property string $lat
 * @property string $lng
 */
class Item extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('active, title, type_id', 'required'),
			array('active, type_id', 'numerical', 'integerOnly'=>true),
			array('title, media_data, address, lat, lng', 'length', 'max'=>256),
			array('edited, media_descr, start, end, body', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, created, edited, active, title, type_id, body, media_descr, media_data, start, end, address, lat, lng', 'safe', 'on'=>'search'),
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
			'tags' => array(self::MANY_MANY, 'Tag', 'items_tags(item_id, tag_id)'),
			'persons' => array(self::MANY_MANY, 'Person', 'items_persons(item_id, person_id)'),
			'type'=>array(self::BELONGS_TO, 'Type', 'type_id'),

			'images' => array(
				self::HAS_MANY,
				'Image',
				'obj_id',
		   	),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'created' => 'Дата создания',
			'edited' => 'Дата редактирования',
			'active' => 'Активно',
			'title' => 'Заголовок',
			'type_id' => 'Тип',
			'body' => 'Текст',
			'media_descr' => 'Описание медиаданных',
			'media_data' => 'Медиаданные (видео или аудио)',
			'start' => 'Начало события',
			'end' => 'Конец события',
			'address' => 'Адрес',
			'lat' => 'Lat',
			'lng' => 'Lng',
			'persons' => 'Люди',
			'tags' => 'Теги',
			'images' => 'Изображения',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('edited',$this->edited,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('media_descr',$this->media_descr,true);
		$criteria->compare('media_data',$this->media_data,true);
		$criteria->compare('start',$this->start,true);
		$criteria->compare('end',$this->end,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('lng',$this->lng,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Item the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function renderTags()
	{
		$d = array();
		if (is_array($this->tags) && count($this->tags))
			foreach ($this->tags as $t)
				$d[] = $t->title;
		return implode(', ',$d);
	}

	public function renderPersons()
	{
		$d = array();
		if (is_array($this->persons) && count($this->persons))
			foreach ($this->persons as $t)
				$d[] = $t->name.' '.$t->lastname;
		return implode(', ',$d);
	}

	public function renderMedia()
	{
		$data = '';
		if ($this->type_id == 2) {
			$data = '<img src="/uploads/img/'.$this->media_data.'" width="200" />';
		} elseif ($this->type_id == 3) {
			$data = '<audio controls style="width:400px"><source src="/uploads/audio/'.$this->media_data.'"></audio>';
		} elseif ($this->type_id == 4) {
			$data = '<video controls style="width:400px"><source src="/uploads/video/'.$this->media_data.'"></video>';
		}

		return $data;
	}

	public function renderMediasmall()
	{
		$data = '';
		if ($this->type_id == 2) {
			$data = '<img src="/uploads/img/'.$this->media_data.'" width="200" />';
		} elseif ($this->type_id == 3) {
			$data = '<audio controls style="width:200px"><source src="/uploads/audio/'.$this->media_data.'"></audio>';
		} elseif ($this->type_id == 4) {
			$data = '<video controls style="width:200px"><source src="/uploads/video/'.$this->media_data.'"></video>';
		}

		return $data;
	}

	public function renderImages()
	{
		$data = '';
		
		if ($this->images[0]) {
			foreach ($this->images as $i) {
				$data .= CHtml::link(CHtml::image($i->getUrlOriginal(), $i->path, array('width'=>200, 'style'=>'margin-right: 20px')), $i->getUrlOriginal(), array("class"=>"fancybox", "rel"=>"fancybox"));
			}
		}

		return $data;
	}
}
