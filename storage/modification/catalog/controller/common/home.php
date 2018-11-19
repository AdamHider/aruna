<?php
class ControllerCommonHome extends Controller {
	public function index() {
 
 $data['content_home'] = $this->load->controller('extension/soconfig/content_home'); 
 
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink($this->config->get('config_url'), 'canonical');
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
 
 $this->load->language('extension/soconfig/soconfig'); 
 $data['objlang'] = $this->language; 
 
		$data['header'] = $this->load->controller('common/header');

		 
 $soconfig = $this->soconfig; 
 $platforms_mobile = $soconfig->get_settings('platforms_mobile'); 
 if($this->session->data['device']=='mobile' && $platforms_mobile) $this->response->redirect($this->url->link('extension/mobile/home')); 
 else $this->response->setOutput($this->load->view('common/home', $data)); 
 
	}
}
