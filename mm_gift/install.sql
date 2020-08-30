CREATE TABLE `ma_mm_gift` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `gift_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    KEY(`product_id`),
    FOREIGN KEY (`product_id`)  REFERENCES `ma_product`  (`product_id`)  ON DELETE CASCADE,
    FOREIGN KEY (`gift_id`)  REFERENCES `ma_product`  (`product_id`)  ON DELETE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO oc_mm_gift (product_id, gift_id) VALUES (40, 41), (40, 42), (43, 44);
