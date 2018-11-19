<?php
class ControllerCommonFooter extends Controller {
	public function index() {

					$data['seller_chat'] = '';
			if(NULL !== $this->config->get('module_purpletree_multivendor_status')){
			if($this->config->get('module_purpletree_multivendor_status')){
		
			}
			}
			
 
 /*======Show Themeconfig=======*/ 
 $data['soconfig'] = $this->soconfig; 
 $this->load->language('extension/soconfig/soconfig'); 
 $data['objlang'] = $this->language; 
 $data['lang_id'] = $this->config->get('config_language_id'); 
 $data['theme_directory'] = $this->config->get('theme_default_directory'); 
 $data['account_fb'] = isset($this->request->get['account_fb']) ? $this->request->get['account_fb'] : '' ; 
 $data['compare'] = $this->url->link('product/compare', '', true); 
 
 // add position 
 if( $this->soconfig->get_settings('typefooter') == 1) $data['footer_block1'] = $this->load->controller('extension/soconfig/footer_block1'); 
 else if( $this->soconfig->get_settings('typefooter') == 2) $data['footer_block2'] = $this->load->controller('extension/soconfig/footer_block2'); 
 else if( $this->soconfig->get_settings('typefooter') == 3) $data['footer_block3'] = $this->load->controller('extension/soconfig/footer_block3'); 
 else if( $this->soconfig->get_settings('typefooter') == 4) $data['footer_block4'] = $this->load->controller('extension/soconfig/footer_block4'); 
 else if( $this->soconfig->get_settings('typefooter') == 5) $data['footer_block5'] = $this->load->controller('extension/soconfig/footer_block5'); 
 else if( $this->soconfig->get_settings('typefooter') == 6) $data['footer_block6'] = $this->load->controller('extension/soconfig/footer_block6'); 
 else if( $this->soconfig->get_settings('typefooter') == 7) $data['footer_block7'] = $this->load->controller('extension/soconfig/footer_block7'); 
 else if( $this->soconfig->get_settings('typefooter') == 8) $data['footer_block8'] = $this->load->controller('extension/soconfig/footer_block8'); 
 else if( $this->soconfig->get_settings('typefooter') == 9) $data['footer_block9'] = $this->load->controller('extension/soconfig/footer_block9'); 
 else if( $this->soconfig->get_settings('typefooter') == 10) $data['footer_block10'] = $this->load->controller('extension/soconfig/footer_block10'); 
 else if( $this->soconfig->get_settings('typefooter') == 11) $data['footer_block11'] = $this->load->controller('extension/soconfig/footer_block11'); 
 else if( $this->soconfig->get_settings('typefooter') == 12) $data['footer_block12'] = $this->load->controller('extension/soconfig/footer_block12'); 
 else if( $this->soconfig->get_settings('typefooter') == 13) $data['footer_block13'] = $this->load->controller('extension/soconfig/footer_block13'); 
 else if( $this->soconfig->get_settings('typefooter') == 14) $data['footer_block14'] = $this->load->controller('extension/soconfig/footer_block14'); 
 else if( $this->soconfig->get_settings('typefooter') == 15) $data['footer_block15'] = $this->load->controller('extension/soconfig/footer_block15'); 
 else if( $this->soconfig->get_settings('typefooter') == 16) $data['footer_block16'] = $this->load->controller('extension/soconfig/footer_block16'); 
 else if( $this->soconfig->get_settings('typefooter') == 17) $data['footer_block17'] = $this->load->controller('extension/soconfig/footer_block17'); 
 else if( $this->soconfig->get_settings('typefooter') == 18) $data['footer_block18'] = $this->load->controller('extension/soconfig/footer_block18'); 
 else if( $this->soconfig->get_settings('typefooter') == 19) $data['footer_block19'] = $this->load->controller('extension/soconfig/footer_block19'); 
 else if( $this->soconfig->get_settings('typefooter') == 20) $data['footer_block20'] = $this->load->controller('extension/soconfig/footer_block20'); 
 
		$this->load->language('common/footer');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['sitemap'] = $this->url->link('information/sitemap');
		$data['tracking'] = $this->url->link('information/tracking');
		$data['manufacturer'] = $this->url->link('product/manufacturer');
		$data['voucher'] = $this->url->link('account/voucher', '', true);
		$data['affiliate'] = $this->url->link('affiliate/login', '', true);
		$data['special'] = $this->url->link('product/special');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			} else {
				$url = '';
			}

			if (isset($this->request->server['HTTP_REFERER'])) {
				$referer = $this->request->server['HTTP_REFERER'];
			} else {
				$referer = '';
			}

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}


 $data['so_countdown'] = $this->load->controller('extension/module/so_countdown');
 
		$data['scripts'] = $this->document->getScripts('footer');
		
 
 // Menu Mobile 
 $this->load->language('extension/soconfig/somobile'); 
 //Decodes HTML Entities 
 $data['customfooter_text'] = html_entity_decode($data['soconfig']->get_settings('customfooter_text'), ENT_QUOTES, 'UTF-8'); 
 
 
		return $this->load->view('common/footer', $data);
	}
}
