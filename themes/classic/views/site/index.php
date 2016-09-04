API<br /><br />
/site/login - авторизация<br />
/site/register - регистрация<br />
/site/forgot_password - напомнить пароль<br /><br />

Авторизация через социальные сети<br /><br />

http://blokada.e-produce.ru/site/social/service/facebook <br />
http://blokada.e-produce.ru/site/social/service/vkontakte <br />
http://blokada.e-produce.ru/site/social/service/odnoklassniki <br /><br />


Ваш текущий статус на сайте: 
<?php if (Yii::app()->user->isGuest): ?>
Гость
<?php else: ?>
Пользователь (<a href="/site/logout">Выход</a>)
<?php endif; ?>