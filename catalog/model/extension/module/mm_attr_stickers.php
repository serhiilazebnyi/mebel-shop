<?php

class ModelExtensionModuleMmAttrStickers extends Model
{
    public function getProductAttrStickers($product_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mm_attribute_sticker mas LEFT JOIN " . DB_PREFIX . "mm_product_to_sticker mps on mas.id = mps.attribute_sticker_id WHERE mas.product_id = '" . (int)$product_id . "' AND mas.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `sort_order` ASC");

        if (!empty($query->rows)) {
            foreach ($query->rows as $result) {
                $attributeStickersData[] = array(
                    'name' => $result['name'],
                    'image' => $result['image'],
                    'text' => $result['text'],
                    'sort_order' => $result['sort_order'],
                );
            }

    		return $attributeStickersData;
        }
    }
}
