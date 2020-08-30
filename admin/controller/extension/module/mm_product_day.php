<?php

class ControllerExtensionModuleMmProductDay extends Controller
{
	private $error = array();

	public function index()
    {
		$this->load->language('extension/module/mm_product_day');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

        $this->load->model('extension/module/mm_product_day');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('mm_product_day', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

            if (!empty($this->request->post['product_id'])) {
                $this->model_extension_module_mm_product_day->addProductsDay($this->request->post['product_id']);
            }
            $this->model_extension_module_mm_product_day->deleteExpiredProductsDay(date('Y-m-d'));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/mm_product_day', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/mm_product_day', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/mm_product_day', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/mm_product_day', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('catalog/product');

		$data['products'] = array();

		if (!empty($this->request->post['product'])) {
			$products = $this->request->post['product'];
		} elseif (!empty($module_info['product'])) {
			$products = $module_info['product'];
		} else {
			$products = array();
		}

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}

		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 4;
		}

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

        $data['dates'] = $this->model_extension_module_mm_product_day->formProductsDay();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/mm_product_day', $data));
	}

	protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/mm_product_day')) {
        	$this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
        	$this->error['name'] = $this->language->get('error_name');
        }

        if (!$this->request->post['width']) {
        	$this->error['width'] = $this->language->get('error_width');
        }

        if (!$this->request->post['height']) {
        	$this->error['height'] = $this->language->get('error_height');
        }

        return !$this->error;
	}

    public function autocomplete()
    {
        $json = array();
        if (isset($this->request->get['category_id'])) {
            $categoryId = $this->request->get['category_id'];
        } else {
            $this->response->addHeader('Content-Type: application/json');
    		$this->response->setOutput(json_encode($json));
        }

        if (isset($this->request->get['filter_name'])) {
            $filterName = $this->request->get['filter_name'];
        } else {
            $filterName = null;
        }

        $this->load->model('extension/module/mm_product_day');
        $products = $this->model_extension_module_mm_product_day->getProductsByCategoryId($categoryId, $filterName);

        foreach ($products as $product) {
            $json[] = array(
                'product_id' => $product['product_id'],
                'name'       => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'))
            );
        }

        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }
}
