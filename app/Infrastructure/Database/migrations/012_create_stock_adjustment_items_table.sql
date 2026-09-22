CREATE TABLE stock_adjustment_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    adjustment_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    system_quantity DECIMAL(15,3) NOT NULL,
    actual_quantity DECIMAL(15,3) NOT NULL,
    difference DECIMAL(15,3) NOT NULL,

    CONSTRAINT fk_adjustment_items_adjustment
        FOREIGN KEY (adjustment_id)
        REFERENCES stock_adjustments(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_adjustment_items_product
        FOREIGN KEY (product_id)
        REFERENCES products(id)
);