DROP TABLE IF EXISTS `ma_mm_product_day`;
CREATE TABLE `ma_mm_product_day` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    `show_on` date NOT NULL,
    PRIMARY KEY (`id`),
    KEY(`product_id`, `show_on`),
    FOREIGN KEY (`product_id`)  REFERENCES `ma_product`  (`product_id`)  ON DELETE CASCADE,
    FOREIGN KEY (`category_id`)  REFERENCES `ma_category`  (`category_id`)  ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
