<?php
class ModelExtensionModuleMmProductDay extends Model {
    public function getCategories() {
        $query = $this->db->query("SELECT c.category_id as id, cd.name FROM " . DB_PREFIX . "category c
        LEFT JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id WHERE c.parent_id = 0 AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
    }

    public function getProductsDay()
    {
        $query = $this->db->query("SELECT mpd.product_id, pd.name, mpd.category_id, mpd.show_on FROM " . DB_PREFIX . "mm_product_day mpd
        LEFT JOIN " . DB_PREFIX . "product_description pd ON mpd.product_id = pd.product_id WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->rows;
    }

    public function formProductsDay()
    {
        $dates = array();

        for ($i=0; $i < 7; $i++) {
            $dates[] = date('d.m', time() + 86400 * $i);
        }

        $categories = $this->getCategories();
        $products = $this->getProductsDay();

        $dateCategories = array();
        $populatedCategories = array();

        foreach ($dates as $date) {
            foreach ($categories as $key => $category) {
                foreach ($products as $product) {
                    $dateParts = explode('-', $product['show_on']);
                    $showOn = $dateParts[2] . '.' . $dateParts[1];
                    if ($category['id'] == $product['category_id'] && $date == $showOn) {
                        $category['product'] = $product;
                    }
                }
                $dateCategories[$date][] = $category;
            }
        }

        return $dateCategories;
    }

    public function deleteExpiredProductsDay($today)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "mm_product_day WHERE `show_on` < '" . $today . "'");
    }

    public function addProductsDay($data = array())
    {
        $sql = "INSERT INTO " . DB_PREFIX . "mm_product_day (`product_id`, `category_id`, `show_on`) VALUES ";
        foreach ($data as $date => $products) {
            $showOn = date('Y-m-d', strtotime($date . '.2020'));
            foreach ($products as $categoryId => $product) {
                foreach ($product as $productId) {
                    if (!empty($productId)) {
                        $sql .= "(" . (int)$productId . ", " . (int)$categoryId . ", '" . $showOn . "'), ";
                    }
                }
            }
        }

        // $sql = substr($sql, 0, -2);
        $sql .= "('', '', '')";
        $this->db->query($sql);
    }

    public function getProductsByCategoryId($category_id, $filter_name = null) {

        $sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p2c.category_id = '" . (int)$category_id . "'";

        if (!empty($filter_name)) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($filter_name) . "%'";
		}

        $sql .= " ORDER BY pd.name ASC";

		$query = $this->db->query($sql);

		return $query->rows;
	}
}
