<?php

	/*
		Plugin Name: WordPress rippleSidebar Eklentisi
		Plugin URI: https://www.ugurdalkiran.com
		Version: 1.0
		Author: ugurdalkiran
		Description: WordPress için geliştirilmiş bir eklentidir.
	*/

	add_action('widgets_init', 'widgetFunc');

	function widgetFunc(){ register_widget('rippleBox'); }

	class rippleBox extends WP_Widget{

		public function __construct(){

			parent::__construct('classname', 'Ripple Kutusu', array(
				'classname' => 'classname',
				'description' => 'Ripple Kutusu oluşturmanızı sağlar.'
			));

		}

		public function form($instance){ ?>

			<p>
				<label>Başlık:</label>
				<input class="widefat" type="text" name="<?php echo $this->get_field_name('baslik') ?>" value="<?php echo $instance['baslik'] ?>">
			<p>
				<input <?php echo $instance['koyuRenk'] == 'Evet' ? 'checked="true"' : '' ?> class="checkbox" type="checkbox" id="<?php echo $this->get_field_name('koyuRenk') ?>" name="<?php echo $this->get_field_name('koyuRenk') ?>" value="Evet">
				<label for="<?php echo $this->get_field_name('koyuRenk') ?>">Koyu renk olarak göster</label>
			</p>

		<?php }

		public function update($new_instance, $old_instance){ return $new_instance; }

		public function widget($args, $instance){

			$page = json_decode($this->connection('https://api.bitturk.com/v1/ticker'));

			$data['alis'] = trim($page[16]->ask);
			$data['sati'] = trim($page[16]->low);

			?>

			<link href="https://fonts.googleapis.com/css?family=Baloo+Bhai&display=swap&subset=latin-ext" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap&subset=latin-ext" rel="stylesheet">
			<style>
				.ozelSidebarBaslik{font:normal 400 20px 'Baloo Bhai';color:#fff;background:#2ecc71;padding:10px 20px;border-radius:8px 8px 0px 0px}
				.ozelSidebarContent{background:#f7f7f7;padding:10px;border-radius:0px 0px 8px 8px}
				.ozelElemanlar{display:flex}
				.ozelElemanTek{background:#fff;box-shadow:0px 1px 2px 0px rgba(0,0,0, 0.12);padding:10px;border-radius:8px;display:flex;flex-direction:column;flex:1;align-items:center}
				.ozelElemanTek:last-child{margin-left:10px}
				.ozelElemanTek em{font:normal 400 13px 'Roboto';color:#999}
				.ozelElemanTek b{font:normal 700 20px 'Roboto';color:#2ecc71;margin-top:2px}
				.ozelSidebarContent p{font:normal 400 13px 'Roboto';color:#999;text-align:center;margin:0px;margin-top:10px}
				.ozelSidebarContent a{color:#3498db;text-decoration:none}
				.ozelSidebarContent a:hover{color:#2980b9;text-decoration:underline}

				<?php if ( isset($instance['koyuRenk']) && $instance['koyuRenk'] == 'Evet' ){ ?>
				.ozelSidebarBaslik{background:#333}
				.ozelSidebarContent{background:#555}
				.ozelElemanTek{background:#383838;box-shadow:0px 1px 2px 0px rgba(0,0,0, 0.04)}
				<?php } ?>
			</style>
			<div class="ozelSidebar">
				<div class="ozelSidebarBaslik"><?php echo $instance['baslik'] ?></div>
				<div class="ozelSidebarContent">
					<div class="ozelElemanlar">
						<div class="ozelElemanTek">
							<em>Alış:</em>
							<b><?php echo $data['alis'] ?> TL</b>
						</div>
						<div class="ozelElemanTek">
							<em>Satış:</em>
							<b><?php echo $data['sati'] ?> TL</b>
						</div>
					</div>
					<p>bitturk.com sitesi üzerinden saatlik Ripple durumunu takip edebilirsiniz.</p>
					<p><a target="_blank" href="https://twitter.com/BitturkRipple">@BitturkRipple</a></p>
				</div>
			</div>

		<?php }

		## CUSTOM

		private function connection($url){

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com.tr/');
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$chData = curl_exec($ch);
			curl_close($ch);
			return $chData;

		}

	}

?>