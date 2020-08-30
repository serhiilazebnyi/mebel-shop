<?php

class ModelExtensionModuleMmAttrStickers extends Model
{
    public function getProductAttrStickers($product_id)
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mm_attribute_sticker mas LEFT JOIN " . DB_PREFIX . "mm_product_to_sticker mps on mas.id = mps.attribute_sticker_id WHERE mas.product_id = '" . (int)$product_id . "'");

        if (!empty($query->rows)) {
            foreach ($query->rows as $result) {
                $attributeStickersData[$result['language_id']][] = array(
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
