<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Авторизуйтесь</h1>

<?php

/** @var TbActiveForm $form */
$form = $this->beginWidget(
    'booster.widgets.TbActiveForm',
    array(
        'id' => 'verticalForm',
        'htmlOptions' => array('class' => 'well'), // for inset effect
    )
);

echo $form->textFieldGroup($model, 'username');
echo $form->passwordFieldGroup($model, 'password');
echo $form->checkboxGroup($model, 'rememberMe');
$this->widget(
    'booster.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Войти')
);

$this->endWidget();
unset($form);

?>
