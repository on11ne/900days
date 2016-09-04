<?php

Yii::app()->clientScript->registerScript('ckeditor', "
init_CKEditor('Item_media_descr');
init_CKEditor('Item_body');
");

?>

<?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'item-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

<p class="help-block">Поля, помеченные <span class="required">*</span>, обязательны к заполнению.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->checkBoxGroup($model,'active',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5')))); ?>

	<?php echo $form->textFieldGroup($model,'title',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>256)))); ?>

	<?php echo $form->dropDownListGroup(
			$model,
			'type_id',
			array(
				'wrapperHtmlOptions' => array(
					'class' => 'col-sm-5',
				),
				'widgetOptions' => array(
					'data' => CHtml::listData(Type::model()->findAll(), 'id', 'title'),
					'htmlOptions' => array(),
				)
			)
		);
	?>

	<?php echo $form->textAreaGroup($model,'body', array('widgetOptions'=>array('htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'span8')))); ?>

	<?php echo $form->textAreaGroup($model,'media_descr', array('widgetOptions'=>array('htmlOptions'=>array('rows'=>6, 'cols'=>50, 'class'=>'span8')))); ?>

	<?php if ($model->type_id != 1):?>

	<hr/>

	<?php if ($model->media_data): ?>
    <div class="form-group">
    	<?php if ($model->type_id == 4):?>
    	<a href="/uploads/video/<?php echo $model->media_data;?>">Скачать видео</a>
    	<?php elseif ($model->type_id == 3):?>
    	<a href="/uploads/audio/<?php echo $model->media_data;?>">Скачать аудио</a>
    	<?php elseif ($model->type_id == 2):?>
    	<?php echo CHtml::image('/uploads/img/'.$model->media_data, "Изображение", array('width'=>200)); ?>
    	<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php echo $form->fileFieldGroup($model, 'media_data',
		array(
			'wrapperHtmlOptions' => array(
				'class' => 'col-sm-5',
			),
		));
	?>

	<?php endif; ?>

	<?php echo $form->datePickerGroup($model,'start',array('widgetOptions'=>array('options'=>array(),'htmlOptions'=>array('class'=>'span5', 'data-date-format' => "yyyy-mm-dd")), 'prepend'=>'<i class="glyphicon glyphicon-calendar"></i>', 'append'=>'Выберите дату')); ?>

	<?php echo $form->datePickerGroup($model,'end',array('widgetOptions'=>array('options'=>array(),'htmlOptions'=>array('class'=>'span5', 'data-date-format' => "yyyy-mm-dd")), 'prepend'=>'<i class="glyphicon glyphicon-calendar"></i>', 'append'=>'Выберите дату')); ?>

	<?php echo $form->textFieldGroup($model,'address',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>256)))); ?>

	<?php echo $form->textFieldGroup($model,'lat',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>256)))); ?>

	<?php echo $form->textFieldGroup($model,'lng',array('widgetOptions'=>array('htmlOptions'=>array('class'=>'span5','maxlength'=>256)))); ?>


	<hr/>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'images'); ?>
		<ul class="images-list">
			<?php foreach ($model->images as $img): ?>
				<li>
					<?= CHtml::link(CHtml::image($img->getUrlOriginal(), $img->path, array('width'=>100)), $img->getUrlOriginal(), array("class"=>"fancybox", "rel"=>"fancybox")); ?>
					<?= CHtml::link(CHtml::encode('Удалить'), array('image/delete', 'id' => $img->id), array('class' => 'js-delete-photo')); ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<label for="BlogItem_images">Добавить:</label>

		<?php $this->widget('CMultiFileUpload', array(
				'name' => 'image',
				'accept' => 'jpeg|jpg|gif|png',
				'duplicate' => 'Дублирующиеся фото',
				'denied' => 'Только изображения',
				'htmlOptions' => array('multiple' => 'multiple'),
			    'max'=>10,
			)); ?>
		<?php echo $form->error($model, 'image'); ?>
	</div>


	<hr>

	<div class="form-group">
	<?php echo $form->labelEx($model, 'persons'); ?>

	<?php $this->widget('booster.widgets.TbSelect2',array(
		'asDropDownList' => true,
		'model'=>$model,
		'attribute'=>'persons',
		'options'  => array(
			'placeholder' => $model->getAttributeLabel('persons'),
			'tokenSeparators' => array(',', ' '),
			'width'=>'100%',
			'allowClear'=>true,
		),

		'data'=> CHtml::listData(Person::model()->findAll(),'id','lastname','name'),
		'htmlOptions'=>array(
			'multiple'=>'multiple',
		),
	));
	?>
	</div>

	<hr>

	<div class="form-group">
	<?php echo $form->labelEx($model, 'tags'); ?>

	<?php $this->widget('booster.widgets.TbSelect2',array(
		'asDropDownList' => true,
		'model'=>$model,
		'attribute'=>'tags',
		'options'  => array(
			'placeholder' => $model->getAttributeLabel('tags'),
			'tokenSeparators' => array(',', ' '),
			'width'=>'100%',
			'allowClear'=>true,
		),

		'data'=> CHtml::listData(Tag::model()->findAll(),'id','title'),
		'htmlOptions'=>array(
			'multiple'=>'multiple',
		),
	));
	?>
	</div>

	<hr>

<div class="form-actions">
	<?php $this->widget('booster.widgets.TbButton', array(
			'buttonType'=>'submit',
			'context'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
