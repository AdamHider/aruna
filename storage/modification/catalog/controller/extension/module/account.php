<?php
class ControllerExtensionModuleAccount extends Controller {
	public function index() {
$data['sellercontact'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerContactlist', '', true);
		$this->load->language('extension/module/account');

		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['forgotten'] = $this->url->link('account/forgotten', '', true);
		$data['account'] = $this->url->link('account/account', '', true);
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['address'] = $this->url->link('account/address', '', true);
		$data['wishlist'] = $this->url->link('account/wishlist');
		$data['order'] = $this->url->link('account/order', '', true);
		$data['download'] = $this->url->link('account/download', '', true);
		$data['reward'] = $this->url->link('account/reward', '', true);
		$data['return'] = $this->url->link('account/return', '', true);
		$data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['recurring'] = $this->url->link('account/recurring', '', true);

				$this->load->language('account/ptsregister');
				$data['sellercontact'] = $this->url->link('extension/account/purpletree_multivendor/sellercontact/customerContactlist', '', true);
				$data['sellercontact_module'] =$this->config->get('module_purpletree_multivendor_seller_contact');	
		$data['purpletree_module_status'] =$this->config->get('module_purpletree_multivendor_status');	
			

		return $this->load->view('extension/module/account', $data);
	}
}