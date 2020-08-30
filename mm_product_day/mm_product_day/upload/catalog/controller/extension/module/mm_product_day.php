<?php
/**
 * Copyright © Serhii Lazebnyi, serhii.lazebnyi@gmail.com
 * All rights reserved.
 */

/**
 * Catalog Controller for Mir Matrasov Product of the Day module
 */
class ControllerExtensionModuleMmProductDay extends Controller {
	public function index() {
		$this->load->language('extension/module/mm_product_day');
		$this->load->model('catalog/product');
        $this->load->model('catalog/category');
		$this->load->model('tool/image');
		$this->load->model('extension/module/mm_product_day');

        $data = array();

        $date = date('Y-m-d');
        $productsDay = $this->model_extension_module_mm_product_day->getTodayProductsDay($date);

        if (!empty($productsDay)) {
            foreach ($productsDay as $product) {

                if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 150, 150);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 150, 150);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product['special']) {
					$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $product['rating'];
				} else {
					$rating = false;
				}

                $data['products'][$product['category']][] = array(
                    'product_id'  => $product['product_id'],
                    'thumb'       => $image,
                    'name'        => $product['name'],
                    'description' => utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
                    'price'       => $price,
                    'special'     => $special,
                    'tax'         => $tax,
                    'rating'      => $rating,
                    'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                );
            }

            if ($data['products']) {
    			return $this->load->view('extension/module/mm_product_day', $data);
    		}
        }
	}
}
