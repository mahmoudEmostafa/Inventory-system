CREATE TABLE stock_transfers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_warehouse_id BIGINT UNSIGNED NOT NULL,
    destination_warehouse_id BIGINT UNSIGNED NOT NULL,
    date DATE NOT NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_transfers_source_warehouse
        FOREIGN KEY (source_warehouse_id)
        REFERENCES warehouses(id),

    CONSTRAINT fk_transfers_destination_warehouse
        FOREIGN KEY (destination_warehouse_id)
        REFERENCES warehouses(id),

    CONSTRAINT fk_transfers_user
        FOREIGN KEY (created_by)
        REFERENCES users(id)
);