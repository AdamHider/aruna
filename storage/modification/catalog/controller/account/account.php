<?php
class ControllerAccountAccount extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);


				if (isset($this->session->data['error_warning'])) {
					$data['error_warning'] = $this->session->data['error_warning'];

					unset($this->session->data['error_warning']);
				}
			
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		} 
		
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		
		$data['credit_cards'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/credit_card/*.php');
		
		foreach ($files as $file) {
			$code = basename($file, '.php');
			
			if ($this->config->get('payment_' . $code . '_status') && $this->config->get('payment_' . $code . '_card')) {
				$this->load->language('extension/credit_card/' . $code, 'extension');

				$data['credit_cards'][] = array(
					'name' => $this->language->get('extension')->get('heading_title'),
					'href' => $this->url->link('extension/credit_card/' . $code, '', true)
				);
			}
		}
		
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		
		if ($this->config->get('total_reward_status')) {
			$data['reward'] = $this->url->link('account/reward', '', true);
		} else {
			$data['reward'] = '';
		}		
		
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);
		
		$this->load->model('account/customer');
		
		$affiliate_info = $this->model_account_customer->getAffiliate($this->customer->getId());
		
		if (!$affiliate_info) {	
			$data['affiliate'] = $this->url->link('account/affiliate/add', '', true);
		} else {
			$data['affiliate'] = $this->url->link('account/affiliate/edit', '', true);
		}
		
		if ($affiliate_info) {		
			$data['tracking'] = $this->url->link('account/tracking', '', true);
		} else {
			$data['tracking'] = '';
		}
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
 
 /*[xml] so dashboard*/ 
 if (isset($this->request->get['page'])) { 
 $page = $this->request->get['page']; 
 } else { 
 $page = 1; 
 } 
 $data['orders'] = array(); 
 
 $this->load->model('account/order'); 
 
 $order_total = $this->model_account_order->getTotalOrders(); 
 
 $results = $this->model_account_order->getOrders(($page - 1) * 10, 10); 
 
 foreach ($results as $result) { 
 $product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']); 
 $voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']); 
 
 $data['orders'][] = array( 
 'order_id' => $result['order_id'], 
 'name' => $result['firstname'] . ' ' . $result['lastname'], 
 'status' => $result['status'], 
 'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])), 
 'products' => ($product_total + $voucher_total), 
 'total' => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']), 
 'view' => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], true), 
 ); 
 } 
 
 $pagination = new Pagination(); 
 $pagination->total = $order_total; 
 $pagination->page = $page; 
 $pagination->limit = 10; 
 $pagination->url = $this->url->link('account/order', 'page={page}', true); 
 
 $data['pagination'] = $pagination->render(); 
 
 $data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10)); 
 
 $data['continue'] = $this->url->link('account/account', '', true); 
 $data['totalOrder'] = !empty($data['orders']) ? count($data['orders']) : 0; 
 
 // Wishlist 
 $this->load->model('account/transaction'); 
 $this->load->model('account/wishlist'); 
 $this->load->model('account/download'); 
 
 if ($this->customer->isLogged()) { 
 
 $data['totalwishlist'] = $this->model_account_wishlist->getTotalWishlist(); 
 $data['text_wishlist'] = $this->language->get('text_wishlist'); 
 } 
 //Download 
 $data['totalDownload'] = $this->model_account_download->getTotalDownloads(); 
 $data['totalTransactions'] = $this->model_account_transaction->getTotalTransactions(); 
 
 $this->load->model('setting/module'); 
 $this->load->model('design/layout'); 
 $data['modules'] = array(); 
 
 $layout_id = 0; 
 if (isset($this->request->get['route'])) { 
 $route = (string)$this->request->get['route']; 
 } else { 
 $route = 'common/home'; 
 } 
 
 if (!$layout_id) { 
 $layout_id = $this->model_design_layout->getLayout($route); 
 } 
 
 $modules = $this->model_design_layout->getLayoutModules($layout_id, 'column_right'); 
 if(empty($modules)) $modules = $this->model_design_layout->getLayoutModules($layout_id, 'column_left'); 
 
 foreach ($modules as $module) { 
 $part = explode('.', $module['code']); 
 
 if (isset($part[0]) && $part[0]=='so_dashboard' && $this->config->get('module_' . $part[0] . '_status')) { 
 $module_data = $this->load->controller('extension/module/' . $part[0]); 
 
 if ($module_data) { 
 $data['modules'][] = $module_data; 
 } 
 } 
 if (isset($part[1]) && $part[0]=='so_dashboard') { 
 $setting_info = $this->model_setting_module->getModule($part[1]); 
 if ($setting_info && $setting_info['status']) { 
 $data['modules'] = $setting_info; 
 
 } 
 } 
 } 
 
 /*[endxml] so dashboard*/ 
 
		 
 $this->response->setOutput($this->load->view('account/accountDashboard.', $data)) 
 ;
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
