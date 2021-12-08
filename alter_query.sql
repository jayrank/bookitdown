-- Changes made in the database, should be mentioned here.
-- After altering anything in database, copy the query generated by MySQL and paste it here along with date, user name and database name
-- For e.g.
-- 2021-09-30 Name DB-Name
-- ALTER TABLE....

-- 2021-11-10 Ketan scheduledown
ALTER TABLE `invoice_items` ADD `item_tax_rate` VARCHAR(255) NULL AFTER `appointment_services_id`, ADD `item_tax_amount` VARCHAR(255) NULL AFTER `item_tax_rate`;

ALTER TABLE `inventory_products` ADD `average_price` FLOAT NULL DEFAULT '0' AFTER `initial_stock`;

ALTER TABLE `inventory_order_logs` ADD `average_price` FLOAT NULL DEFAULT '0' AFTER `stock_on_hand`;

ALTER TABLE `invoice_items` ADD `item_tax_id` INT(11) NOT NULL DEFAULT '0' AFTER `appointment_services_id`;