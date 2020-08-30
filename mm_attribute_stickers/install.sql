DROP TABLE IF EXISTS `ma_mm_attribute_sticker`;
CREATE TABLE `ma_mm_attribute_sticker` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `language_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY(`product_id`),
    FOREIGN KEY (`product_id`)  REFERENCES `ma_product`  (`product_id`)  ON DELETE CASCADE,
    FOREIGN KEY (`language_id`) REFERENCES `ma_language` (`language_id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `ma_mm_product_to_sticker`;
CREATE TABLE `ma_mm_product_to_sticker` (
    `attribute_sticker_id` int(11) NOT NULL,
    `name` varchar(255) DEFAULT NULL,
    `image` varchar(255) DEFAULT NULL,
    `text` varchar(255) DEFAULT NULL,
    `sort_order` int(3) DEFAULT 0,
    FOREIGN KEY (`attribute_sticker_id`)  REFERENCES `ma_mm_attribute_sticker` (`id`) ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
