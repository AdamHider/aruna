<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

 
 if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) { 
 $data['base'] = $this->config->get('config_ssl'); 
 } else { 
 $data['base'] = $this->config->get('config_url'); 
 } 
 
 $this->load->model('setting/setting'); 
 $data['cfp_setting'] = $this->model_setting_setting->getSetting('module_so_call_for_price'); 
 
 if (!defined ('FANCYBOX')) 
 { 
 $this->document->addStyle('catalog/view/javascript/so_call_for_price/css/jquery.fancybox.css'); 
 $this->document->addScript('catalog/view/javascript/so_call_for_price/js/jquery.fancybox.js'); 
 define( 'FANCYBOX', 1 ); 
 } 
 if (!defined ('so_call_for_price')){ 
 
 $this->document->addStyle('catalog/view/javascript/so_call_for_price/css/style.css'); 
 $this->document->addScript('catalog/view/javascript/so_call_for_price/js/script.js'); 
 define( 'so_call_for_price', 1 ); 
 } 
 
		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

 
 if ((float)$product_info['price']) { 
 $price_0 = $product_info['price']; 
 } 
 else { 
 $price_0 = false; 
 } 
 
					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

 
 $option_data = array(); 
 $image_data = array(); 
 if ($this->config->get('module_so_color_swatches_pro_status')) { 
 $data['color_swatch_status'] = $this->config->get('module_so_color_swatches_pro_status'); 
 $data['enable_product_list'] = $this->config->get('module_so_color_swatches_pro_enable_product_list'); 
 $data['width_product_list'] = (int)$this->config->get('module_so_color_swatches_pro_width_product_list'); 
 if ($data['width_product_list'] == 0) { 
 $data['width_product_list'] = 15; 
 } 
 $data['height_product_list'] = (int)$this->config->get('module_so_color_swatches_pro_height_product_list'); 
 if ($data['height_product_list'] == 0) { 
 $data['height_product_list'] = 15; 
 } 
 $data['colorswatch_type'] = $this->config->get('module_so_color_swatches_pro_type'); 
 $this->document->addStyle('catalog/view/javascript/so_color_swatches_pro/css/style.css'); 
 
 $this->load->model('extension/module/so_color_swatches_pro'); 
 $options = $this->model_extension_module_so_color_swatches_pro->getProductOptions($product_info['product_id']); 
 foreach ($options as $option) { 
 $product_option_value_data = array(); 
 foreach ($option['product_option_value'] as $option_value) { 
 if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) { 
 $p_image = $this->model_extension_module_so_color_swatches_pro->getProductImages($product_info['product_id'], $option_value['option_value_id']); 
 if (isset($p_image['image']) && $p_image['image']) { 
 $pimage = $this->model_tool_image->resize($p_image['image'], $setting['width'], $setting['height']); 
 } else { 
 $pimage = ''; 
 } 
 if (isset($p_image['product_image_id']) && $p_image['product_image_id']) { 
 $product_image_id = $p_image['product_image_id']; 
 } 
 else { 
 $product_image_id = ''; 
 } 
 $product_option_value_data[] = array( 
 'product_option_value_id' => $option_value['product_option_value_id'], 
 'option_value_id' => $option_value['option_value_id'], 
 'name' => $option_value['name'], 
 'image' => $this->model_tool_image->resize($option_value['image'], $data['width_product_list'], $data['height_product_list']), 
 'price' => $price, 
 'price_prefix' => $option_value['price_prefix'], 
 'color_image' => $pimage, 
 'product_image_id' => $product_image_id 
 ); 
 } 
 } 
 $option_data[] = array( 
 'product_option_id' => $option['product_option_id'], 
 'product_option_value' => $product_option_value_data, 
 'option_id' => $option['option_id'], 
 'name' => $option['name'], 
 'type' => $option['type'], 
 'value' => $option['value'], 
 'required' => $option['required'] 
 ); 
 } 
 } 
 
					$data['products'][] = array(
						'product_id'  => $product_info['product_id'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'tax'         => $tax,
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}