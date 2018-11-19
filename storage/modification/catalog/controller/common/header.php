<?php
class ControllerCommonHeader extends Controller {
	public function index() {
 
 /*======Show Themeconfig=======*/ 
 $data['soconfig'] = $this->soconfig; 
 $this->load->language('extension/soconfig/compare'); 
 $data['objlang'] = $this->language; 
 $data['lang_id'] = $this->config->get('config_language_id'); 
 $data['theme_directory'] = $this->config->get('theme_default_directory'); 
 $data['url_layoutbox'] = isset($this->request->get['layoutbox']) ? $this->request->get['layoutbox'] : '' ; 
 $data['url_pattern'] = isset($this->request->get['pattern']) ? $this->request->get['pattern'] : '' ; 
 $data['account_fb'] = isset($this->request->get['account_fb']) ? $this->request->get['account_fb'] : '' ; 
 $data['compare'] = $this->url->link('product/compare', '', true); 
 
 // add position 
 $data['content_menu1'] = $this->load->controller('extension/soconfig/content_menu1'); 
 $data['content_menu2'] = $this->load->controller('extension/soconfig/content_menu2'); 
 $data['header_block'] = $this->load->controller('extension/soconfig/header_block'); 
 $data['header_block2'] = $this->load->controller('extension/soconfig/header_block2'); 
 $data['search_block'] = $this->load->controller('extension/soconfig/search_block'); 
 
 // For page specific css 
 if (isset($this->request->get['route'])) $data['class'] = str_replace('/', '-', $this->request->get['route']); 
 else $data['class'] = 'common-home'; 
 
 //Decodes HTML Entities 
 $data['selector_body'] = html_entity_decode($data['soconfig']->get_settings('selector_body'), ENT_QUOTES, 'UTF-8'); 
 $data['selector_menu'] = html_entity_decode($data['soconfig']->get_settings('selector_menu'), ENT_QUOTES, 'UTF-8'); 
 $data['selector_heading'] = html_entity_decode($data['soconfig']->get_settings('selector_heading'), ENT_QUOTES, 'UTF-8'); 
 $data['mselector_body'] = html_entity_decode($data['soconfig']->get_settings('mselector_body'), ENT_QUOTES, 'UTF-8'); 
 $data['mselector_menu'] = html_entity_decode($data['soconfig']->get_settings('mselector_menu'), ENT_QUOTES, 'UTF-8'); 
 $data['mselector_heading'] = html_entity_decode($data['soconfig']->get_settings('mselector_heading'), ENT_QUOTES, 'UTF-8'); 
 
 if (!defined ('OWL_CAROUSEL')){ 
 $this->document->addStyle('catalog/view/javascript/soconfig/css/owl.carousel.css'); 
 $this->document->addScript('catalog/view/javascript/soconfig/js/owl.carousel.js'); 
 define( 'OWL_CAROUSEL', 1 ); 
 } 
 
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

 $this->document->addStyle('catalog/view/javascript/so_sociallogin/css/so_sociallogin.css');
 $this->load->model('setting/setting');
 $this->load->model('tool/image');
 $setting = $this->model_setting_setting->getSetting('so_sociallogin');
 
 if (isset($setting['so_sociallogin_enable']) && $setting['so_sociallogin_enable'] && $this->config->get('so_sociallogin_enable')) {
 if(isset($this->session->data['route']))
 {
 $location = $this->url->link($this->session->data['route'], "", 'SSL');
 }
 else
 {
 $location = $this->url->link("account/account", "", 'SSL');
 }
 
 /* Facebook Library */
 require_once (DIR_SYSTEM.'library/so_social/Facebook/autoload.php');
 
 $fb = new Facebook\Facebook ([
 'app_id' => $setting['so_sociallogin_fbapikey'], 
 'app_secret' => $setting['so_sociallogin_fbsecretapi'],
 'default_graph_version' => 'v2.4',
 ]);

 $helper = $fb->getRedirectLoginHelper();

 try {
 $accessToken = $helper->getAccessToken();
 } catch(Facebook\Exceptions\FacebookResponseException $e) {
 // When Graph returns an error
 //echo 'Graph returned an error: ' . $e->getMessage();
 //exit;
 } catch(Facebook\Exceptions\FacebookSDKException $e) {
 // When validation fails or other local issues
 //echo 'Facebook SDK returned an error: ' . $e->getMessage();
 //exit;
 } 
 
 $data['fblink'] = $helper->getLoginUrl($this->url->link('extension/module/so_sociallogin/FacebookLogin', '', 'SSL'), array('public_profile','email'));
 /* Facebook Login link code */
 
 /* Google Libery file inculde */
 require_once DIR_SYSTEM.'library/so_social/src/Google_Client.php';
 require_once DIR_SYSTEM.'library/so_social/src/contrib/Google_Oauth2Service.php';
 
 /* Google Login link code */
 $gClient = new Google_Client();
 $gClient->setApplicationName($setting['so_sociallogin_googletitle']);
 $gClient->setClientId($setting['so_sociallogin_googleapikey']);
 $gClient->setClientSecret($setting['so_sociallogin_googlesecretapi']);
 $gClient->setRedirectUri($this->url->link('extension/module/so_sociallogin/GoogleLogin', '', 'SSL'));
 $google_oauthV2 = new Google_Oauth2Service($gClient);
 $data['googlelink'] = $gClient->createAuthUrl();
 
 /* Twitter Login */ 
 $data['twitlink'] = $this->url->link('extension/module/so_sociallogin/TwitterLogin', '', 'SSL');
 
 /* Linkedin Login */
 $data['linkdinlink'] = $this->url->link('extension/module/so_sociallogin/LinkedinLogin', '', 'SSL');
 
 /* Get Image */
 $sociallogin_width = 130;
 $sociallogin_height = 35;
 if (isset($setting['so_sociallogin_width']) && is_numeric($setting['so_sociallogin_width'])) {
 $sociallogin_width = $setting['so_sociallogin_width'];
 }
 if (isset($setting['so_sociallogin_height']) && is_numeric($setting['so_sociallogin_height'])) {
 $sociallogin_height = $setting['so_sociallogin_height'];
 }
 if ($setting['so_sociallogin_fbimage']) {
 $fbicon = $this->model_tool_image->resize($setting['so_sociallogin_fbimage'], $sociallogin_width, $sociallogin_height);
 } else {
 $fbicon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
 }
 
 if ($setting['so_sociallogin_twitimage']) {
 $twiticon = $this->model_tool_image->resize($setting['so_sociallogin_twitimage'], $sociallogin_width, $sociallogin_height);
 } else {
 $twiticon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
 }
 
 if ($setting['so_sociallogin_googleimage']) {
 $googleicon = $this->model_tool_image->resize($setting['so_sociallogin_googleimage'], $sociallogin_width, $sociallogin_height);
 } else {
 $googleicon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
 }
 
 if ($setting['so_sociallogin_linkdinimage']) {
 $linkdinicon = $this->model_tool_image->resize($setting['so_sociallogin_linkdinimage'], $sociallogin_width, $sociallogin_height);
 } else {
 $linkdinicon = $this->model_tool_image->resize('placeholder.png', $sociallogin_width, $sociallogin_height);
 }
 
 $data['iconwidth'] = $sociallogin_width;
 $data['iconheight'] = $sociallogin_height;
 $data['status'] = $setting['so_sociallogin_enable'];
 $data['fbimage'] = $fbicon;
 $data['twitimage'] = $twiticon;
 $data['googleimage'] = $googleicon;
 $data['linkdinimage'] = $linkdinicon;
 
 $data['setting'] = $setting;
 
 $this->load->language('extension/module/so_sociallogin');
 $data['text_colregister'] = $this->language->get('text_colregister');
 $data['text_create_account'] = $this->language->get('text_create_account');
 $data['link_forgot_password'] = $this->url->link('account/forgotten', '', true);
 $data['text_forgot_password'] = $this->language->get('text_forgot_password');
 $data['text_title_popuplogin'] = $this->language->get('text_title_popuplogin');
 $data['text_title_login_with_social'] = $this->language->get('text_title_login_with_social');
 }
 

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
		
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', true);
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', true);
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['order'] = $this->url->link('account/order', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', true);
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');
		
		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

			/***** Show browse seller link in header ******/
			$this->load->language('account/ptsregister');
			 $data['direction'] = $this->language->get('direction');
			 $data['module_purpletree_multivendor_browse_sellers'] = $this->config->get('module_purpletree_multivendor_browse_sellers');
			$data['text_browse_sellers'] = $this->language->get('text_browse_sellers');
			$data['browse_seller_link'] = $this->url->link('extension/account/purpletree_multivendor/sellers');
			
		$data['menu'] = $this->load->controller('common/menu');

 
 $this->load->language('extension/soconfig/somobile'); 
 $data['menu_search'] = $this->url->link('product/search', '', true); 
 $data['mobile'] = $this->soconfig; 
 $http = $_SERVER["HTTPS"] ? 'https://' : 'http://'; 
 $data['actual_link'] = $http."$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
 $data['text_items'] = sprintf($this->language->get('text_itemcount'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0)); 
 if($this->session->data['device']=='mobile'){ 
 $data['home'] = $this->url->link('extension/mobile/home'); 
 }else{ 
 $data['home'] = $this->url->link('common/home'); 
 } 
 
 
		return $this->load->view('common/header', $data);
	}
}
