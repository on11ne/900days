<?php

class LikeCommand extends CConsoleCommand {

	public function actionVK()	{

		die;

		$items = Item::model()->findAll('soc_net="vkontakte"');

		foreach ($items as $i) {

			$oid = $i->user->soc_id;

			if (strstr($i->soc_link, 'w=wall-'))
				$oid = -$oid;

			$url = "https://api.vk.com/method/likes.getList?type=post&owner_id=".$oid."&item_id=".$i->soc_id;
			$data = file_get_contents($url);
			$res = json_decode($data);

			if (!empty($res->error))
				continue;

			$likes = (int)($res->response->count);

			$i->likes_vk = $likes;
			$i->likes = $i->likes_vk + $i->likes_fb + $i->likes_ok + $i->likes_ig + $i->likes_site;

			$i->save();

			sleep(2);
		}
	}

	public function actionFB() {

		die;

		$items = Item::model()->findAll('soc_net="facebook"');

		foreach ($items as $i) {

			print_r($i);
			die;

			// https://graph.facebook.com/POST_ID/likes?summary=true&access_token=XXXXXXXXXXXX
		}

	}


	public function actionTW() {

		die;

		$items = Item::model()->findAll('soc_net="twitter"');

		foreach ($items as $i) {

			print_r($i);
			die;
		}

	}

	public function actionIG() {

		die;

		$items = Item::model()->findAll('soc_net="instagram"');

		foreach ($items as $i) {

			$url = "http://api.instagram.com/oembed?url=".$i->soc_link;
			$data = @file_get_contents($url);

			if (strlen(trim($data)) < 1)
				continue;

			$res = json_decode($data);

			$mid = $res->media_id;

			$url = "https://api.instagram.com/v1/media/".$mid."?access_token=1467429339.2fea1cd.89c274c027eb4216bee74613fb58255c";
			$data = @file_get_contents($url);

			if (strlen(trim($data)) < 1)
				continue;

			$res = json_decode($data);

			if (empty($res->data) || empty($res->data->likes))
				continue;

			$likes = (int)($res->data->likes->count);

			$i->likes_ig = $likes;
			$i->likes = $i->likes_vk + $i->likes_fb + $i->likes_ok + $i->likes_ig + $i->likes_site;

			$i->save();

			sleep(2);
		}

	}
}
