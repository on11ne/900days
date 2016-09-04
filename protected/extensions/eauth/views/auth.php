<!--
<div class="services">
	<ul class="auth-services clear">
		<?php
        /*
		foreach ($services as $name => $service) {
			echo '<li class="auth-service ' . $service->id . '">';
			$html = '<span class="auth-icon ' . $service->id . '"><i></i></span>';
			$html .= '<span class="auth-title">' . $service->title . '</span>';
			$html = CHtml::link($html, array($action, 'service' => $name), array(
				'class' => 'auth-link ' . $service->id,
			));
			echo $html;
			echo '</li>';
		}
        */
		?>
	</ul>
</div>
-->

<div class="social-links services">
    <span class="auth-service odnoklassniki" style="display: inline-block; margin-right: 10px; margin-left: 170px;">
        <a href="/site/socialauth/service/odnoklassniki" class="od-link odnoklassniki auth-link">
            <img src="images/elements/od-icon.png">
        </a>
    </span>
    <span class="auth-service vkontakte" style="display: inline-block; margin-right: 10px;">
        <a href="/site/socialauth/service/vkontakte" class="vk-link vkontakte auth-link">
            <img src="images/elements/vk-icon.png">
        </a>
    </span>
    <span class="auth-service facebook" style="display: inline-block;">
        <a href="/site/socialauth/service/facebook" class="fb-link facebook auth-link">
            <img src="images/elements/fb-icon.png">
        </a>
    </span>
</div>