<?php

class SearchCommand extends CConsoleCommand {

	public $added = 0;
	
	public function actionProcess()	{

		$socials = [

		    'instagram' => [
		        'method' => 'searchInstagram',
		        'options' => [
		            //'access_token' => '1647213531.85676c7.137eb12ea632461a8427a21bff4a0a55',
		            'access_token' => '1647213531.7e4184e.065d8427f6f847399dc5801bed700001',
		        ]
		    ],

		    'vk' => [
		        'method' => 'searchVK',
		        'options' => [
		            'access_token' => '62c2beba5cf3e3a16140fb4fe7784782c2ed5bdb54ad2eda5331eddcad1d2c69910dfb67256a5670fed91'
		        ]
		    ],
		];

		$search = new SocialSearch();

		$tag = "#ЛЕНТАЛЕТО";

		foreach ($socials as $key => $social) {
	        $results = call_user_func([$search, $social['method']], $tag, isset($social['options']) ? $social['options'] : null);

	        switch ($key) {
	            case 'instagram':

	            	$data = $results['entry_data']['TagPage'][0]['tag']['media']['nodes'];

	                if (!empty($data)) {

	                    foreach ($data as $row) {


	                    	$row['mid'] = $row['id'].'_'.$row['owner']['id'];

	                    	

	                        $this->onSave($key, [
	                            'tag'           => $tag,
	                            'post_id'       => $row['mid'],
	                            'caption_id'    => '',
	                            'post_text'     => $row['caption'],
	                            'post_image'    => $row['display_src'],
	                            'post_time'     => $row['date'],
	                            'post_link'     => 'https://www.instagram.com/p/'.$row['code'].'/',
	                            'user_id'       => $row['owner']['id'],
//	                            'user_name'     => $data['full_name'],
//	                            'user_nickname' => $data['username'],
	                            'likes'   	    => $row['likes']['count'],
	                            'code'			=> $row['code'],
	                        ]);


	                        /*$this->onSave($key, [
	                            'tag'           => $tag,
	                            'post_id'       => $row['id'],
	                            'caption_id'    => $row['caption']['id'],
	                            'post_text'     => $row['caption']['text'],
	                            'post_image'    => $row['images']['standard_resolution']['url'],
	                            'post_time'     => $row['created_time'],
	                            'post_link'     => $row['link'],
	                            'user_id'       => $row['caption']['from']['id'],
	                            'user_name'     => $row['caption']['from']['full_name'],
	                            'user_nickname' => $row['caption']['from']['username'],
	                            'user_avatar'   => $row['caption']['from']['profile_picture'],
	                            'likes'   	    => $row['likes']['count'],
	                        ]);*/
	                    }
	                }

	           		break;

				case 'vk':

	                if (!empty($results['response'])) {

	                    foreach (array_slice($results['response'], 1) as $row) {

	                    	//print_r($row);
	                    	//die;

	                        if ($row['from_id'] > 0) {
	                            $user = $row['user'];
	                            $isGroup = false;
	                        } else {
	
	                        	if (!empty($row['group'])) {
		                            $user = $row['group'];
		                            $isGroup = true;
								} else {
									$user = $row['user'];
		                            $isGroup = false;
								}
	                        }

	                        if (isset($row['attachments'])) {

	                            $attachments = array_column(array_filter($row['attachments'], function($v)
	                            {
	                                return $v['type'] == 'photo';
	                            }), 'photo');
	                        }

	                        $oid = $isGroup ? $user['gid'] : $user['uid'];

							if (strstr($i->soc_link, 'w=wall-'))
								$oid = -$oid;

	                        $url = "https://api.vk.com/method/likes.getList?type=post&owner_id=".$oid."&item_id=".$row['id'];
							$data = file_get_contents($url);
							$res = json_decode($data);

							if (!empty($res->error))
								$likes = 0;
							else
								$likes = (int)($res->response->count);


							if ($isGroup)
								$post_link = "http://vk.com/feed?w=wall-".$oid."_".$row['id'];
							else
								$post_link = "http://vk.com/feed?w=wall".$oid."_".$row['id'];

	                        $this->onSave($key, [
	                            'tag' => $tag,
	                            'post_id'   => $row['id'],
	                            'post_text' => $row['text'],
	                            'post_image' => !empty($attachments) ? $attachments[0]['src_big'] : null,
	                            'post_time' => $row['date'],
	                            'user_id' => $isGroup ? $user['gid'] : $user['uid'],
	                            'user_name' => $isGroup ? $user['name'] : ($user['first_name'] . ' ' . $user['last_name']),
	                            'user_nickname' => $user['screen_name'],
	                            'user_avatar' => $isGroup ? $user['photo_big'] : $user['photo_medium_rec'],
	                            'user_is_group' => $isGroup,
	                            'post_link' => $post_link,
	                            'likes' => $likes,
	                        ]);
	                    }
	                }
	
		            break;

				default:
		            break;
	        }

	    }

	    echo $this->added;
	}

	function onSave($social, $data) {

		$dt1 = $data['post_time'];
		$dt2 = mktime(0, 0, 0, 3, 15, 2016);

		if (empty($data['post_image']))
			return;

		//print_r($data);
		//return;

		$item = Item::model()->findByAttributes(array(
			'soc_net' => $social,
			'soc_id' => $data['post_id'],
		));

		//print_r($item);

		if (!$item) {

			$dres = $this->get_web_page($data['post_image']);
			$dres['ext'] = $dres['url'];

			if (strstr($dres['ext'], "?"))
			{
				$tkk = explode("?", $dres['ext']);

				$ext = pathinfo(strtolower($tkk[0]), PATHINFO_EXTENSION);

				if ($ext == "jpg" || $ext == "png" || $ext == "gif" || $ext == "jpeg")
				{
					$dres['ext'] = $tkk[0];
				}
			}

			$dres['ext'] = pathinfo($dres['ext'], PATHINFO_EXTENSION);

			$imgurl = $dres['url'];

			$image_name = uniqid() . "." . $dres['ext'];

			@mkdir(Yii::getPathOfAlias('webroot').'/../uploads/items/original');
			$folder = Yii::getPathOfAlias('webroot').'/../uploads/items/original/'.$image_name;
			file_put_contents($folder, $dres['content']);

			try {

				$image = Yii::app()->image->load($folder);

				$w = $image->__get('width');
                $h = $image->__get('height');

				$image->resize(411, 253, ImageConv::WIDTH)->crop(411, 253);

				$folder2 = Yii::getPathOfAlias('webroot').'/../uploads/items/thumb/'.$image_name;
				$image->save($folder2);




				$image = Yii::app()->image->load($folder);

				$w = $image->__get('width');
                $h = $image->__get('height');

				if ($w == $h) 
				    	$image->resize(411, 411)->crop(411, 351);
				else if ($w / $h > 411 / 351)
				     $image->resize(411, 351, ImageConv::HEIGHT)->crop(411, 351);
				else
				     $image->resize(411, 351, ImageConv::WIDTH)->crop(411, 351);

				$folder2 = Yii::getPathOfAlias('webroot').'/../uploads/items/thumb2/'.$image_name;
				$image->save($folder2);


			} catch (Exception $e) {
				return;
			}



			if ($social == 'instagram') {

				$url = 'https://www.instagram.com/p/'.$data['code'].'/';
              	$dat = file_get_contents($url);

            	$reg = '#<script type="text/javascript">window._sharedData = {(.+?)};</script>#';

		    	preg_match_all($reg, $dat, $result);

		    	$res = $result[1][0];
		    	$res = '{'.$res.'}';
		    	$ret = json_decode($res, true);

		    	$ret = $ret['entry_data']['PostPage'][0]['media']['owner'];

		    	$data['user_name'] = $ret['full_name'];
                $data['user_nickname'] = $ret['username'];
			}




			$item = new Item();

			$item->soc_id = $data['post_id'];
			$item->caption_id = $data['caption_id'];
			$item->soc_link = $data['post_link'];
			$item->soc_date = date('Y-m-d H:i:s', $data['post_time']);
			$item->likes = $data['likes'];
			$item->comment = $data['post_text'];
			$item->soc_net = $social;
			$item->img = $image_name;
			$item->user_id = $data['user_id'];
			$item->name = $data['user_name'];
			$item->nickname = $data['user_nickname'];
			$item->status = 0;

			if (empty($item->name))
				$item->name = $item->nickname;

			$item->likes = $data['likes'];

			if (!$item->save())
				echo CHtml::errorSummary($item);

			$this->added++;

			//die;
		}
		else {             //-------------------- Апдейт лайков ------------

			//print_r($data);

			$item = Item::model()->find('soc_net=:soc_net and soc_id=:post_id', array(':post_id'=>$data['post_id'], ':soc_net'=>$social));
					//print_r($item->likes);
			
			$item->likes = $data['likes'];

			if (empty($item->name))
				$item->name = $item->nickname;

			if (!$item->save())
				echo CHtml::errorSummary($item);
		}
						  //------------------------------------------------
	}

	
	public function actionLikes()
	{
		$items = Item::model()->findAll();

		foreach ($items as $i) {

			$url = "http://api.instagram.com/oembed?url=".$i->soc_link;

			$data = @file_get_contents($url);

			if (strlen(trim($data)) < 1)
				continue;

			$res = json_decode($data);

			$mid = $res->media_id;

			$url = "https://api.instagram.com/v1/media/".$mid."?access_token=1647213531.85676c7.137eb12ea632461a8427a21bff4a0a55";

			$data = @file_get_contents($url);

			if (strlen(trim($data)) < 1)
				continue;

			$res = json_decode($data);

			if (empty($res->data) || empty($res->data->likes))
				continue;

			$likes = (int)($res->data->likes->count);

			$i->likes = $likes;

			$i->save();

			echo $i->id.PHP_EOL;

			sleep(1);
		}
	}

	function get_web_page( $url ) {
	    $res = array();
	    $options = array( 
	        CURLOPT_RETURNTRANSFER => true,     // return web page 
	        CURLOPT_HEADER         => false,    // do not return headers 
	        CURLOPT_FOLLOWLOCATION => true,     // follow redirects 
	        CURLOPT_USERAGENT      => "spider", // who am i 
	        CURLOPT_AUTOREFERER    => true,     // set referer on redirect 
	        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect 
	        CURLOPT_TIMEOUT        => 120,      // timeout on response 
	        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects 
	    ); 
	    $ch      = curl_init( $url ); 
	    curl_setopt_array( $ch, $options ); 
	    $content = curl_exec( $ch ); 
	    $err     = curl_errno( $ch ); 
	    $errmsg  = curl_error( $ch ); 
	    $header  = curl_getinfo( $ch ); 
	    curl_close( $ch ); 

	    $res['content'] = $content;     
	    $res['url'] = $header['url'];
	    return $res; 
	}
}