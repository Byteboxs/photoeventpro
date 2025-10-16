-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema photoeventpro
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema photoeventpro
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `photoeventpro` ;
USE `photoeventpro` ;

-- -----------------------------------------------------
-- Table `photoeventpro`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`document_type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`document_type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(128) NOT NULL,
  `codigo` VARCHAR(5) NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `roles_id` INT NOT NULL,
  `document_type_id` INT NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `primer_nombre` VARCHAR(255) NOT NULL,
  `segundo_nombre` VARCHAR(255) NULL,
  `primer_apellido` VARCHAR(255) NOT NULL,
  `segundo_apellido` VARCHAR(255) NULL,
  `direccion` VARCHAR(255) NULL,
  `telefono` VARCHAR(20) NULL,
  `numero_identificacion` VARCHAR(20) NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_users_roles_idx` (`roles_id` ASC) VISIBLE,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  INDEX `fk_users_document_type1_idx` (`document_type_id` ASC) VISIBLE,
  UNIQUE INDEX `numero_identificacion_UNIQUE` (`numero_identificacion` ASC) VISIBLE,
  CONSTRAINT `fk_users_roles`
    FOREIGN KEY (`roles_id`)
    REFERENCES `photoeventpro`.`roles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_document_type1`
    FOREIGN KEY (`document_type_id`)
    REFERENCES `photoeventpro`.`document_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`customers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`customers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `notas` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_customers_users1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_customers_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `photoeventpro`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`employes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`employes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `cargo` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_employes_users1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_employes_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `photoeventpro`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`institutions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`institutions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `direccion` VARCHAR(255) NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`projects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`projects` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `institution_id` INT NOT NULL,
  `location_id` INT NOT NULL,
  `nombre_evento` VARCHAR(255) NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `fecha_fin` DATE NOT NULL,
  `status` ENUM('programado', 'activo', 'finalizado', 'cancelado') NOT NULL DEFAULT 'programado',
  `descripcion` TEXT NULL,
  `hora_ceremonia` TIME NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_proyectos_instituciones1_idx` (`institution_id` ASC) VISIBLE,
  CONSTRAINT `fk_proyectos_instituciones1`
    FOREIGN KEY (`institution_id`)
    REFERENCES `photoeventpro`.`institutions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`locations_for_institutions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`locations_for_institutions` (
  `location_id` INT NOT NULL,
  `institucion_id` INT NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`location_id`, `institucion_id`),
  INDEX `fk_locations_has_instituciones_instituciones1_idx` (`institucion_id` ASC) VISIBLE,
  INDEX `fk_locations_has_instituciones_locations1_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `fk_locations_has_instituciones_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `photoeventpro`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_locations_has_instituciones_instituciones1`
    FOREIGN KEY (`institucion_id`)
    REFERENCES `photoeventpro`.`institutions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`billing_information`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`billing_information` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NOT NULL,
  `document_type_id` INT NOT NULL,
  `tipo_persona` ENUM('natural', 'juridica') NOT NULL DEFAULT 'natural',
  `razon_social` VARCHAR(255) NOT NULL,
  `nit` VARCHAR(128) NOT NULL,
  `direccion_facturacion` VARCHAR(255) NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_billing_information_customers1_idx` (`customer_id` ASC) VISIBLE,
  INDEX `fk_billing_information_document_type1_idx` (`document_type_id` ASC) VISIBLE,
  CONSTRAINT `fk_billing_information_customers1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `photoeventpro`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_billing_information_document_type1`
    FOREIGN KEY (`document_type_id`)
    REFERENCES `photoeventpro`.`document_type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`shipping_information`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`shipping_information` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NOT NULL,
  `nombre_contacto` VARCHAR(255) NOT NULL,
  `direccion_envio` VARCHAR(255) NOT NULL,
  `telefono_contacto` VARCHAR(20) NULL,
  `instrucciones_adicionales` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_informacion_envio_customers1_idx` (`customer_id` ASC) VISIBLE,
  CONSTRAINT `fk_informacion_envio_customers1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `photoeventpro`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`categorias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NULL,
  `status` ENUM('activo', 'inactivo') NULL DEFAULT 'activo',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`services` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `categoria_id` INT NOT NULL,
  `nombre` VARCHAR(45) NOT NULL,
  `descripcion` TEXT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `max_fotos` INT NOT NULL,
  `min_fotos` INT NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `image` VARCHAR(256) NULL DEFAULT 'default_product.jpg',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nombre_UNIQUE` (`nombre` ASC) VISIBLE,
  INDEX `fk_services_categorias1_idx` (`categoria_id` ASC) VISIBLE,
  CONSTRAINT `fk_services_categorias1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `photoeventpro`.`categorias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`customers_events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`customers_events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `fk_projects_has_customers_customers1_idx` (`customer_id` ASC) VISIBLE,
  INDEX `fk_projects_has_customers_projects1_idx` (`project_id` ASC) VISIBLE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_projects_has_customers_projects1`
    FOREIGN KEY (`project_id`)
    REFERENCES `photoeventpro`.`projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_has_customers_customers1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `photoeventpro`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`pictures`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`pictures` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customers_events_id` INT NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `fecha_captura` DATETIME NOT NULL,
  `status` ENUM('disponible', 'seleccionada', 'impresa') NOT NULL DEFAULT 'disponible',
  PRIMARY KEY (`id`),
  INDEX `fk_pictures_customers_events1_idx` (`customers_events_id` ASC) VISIBLE,
  CONSTRAINT `fk_pictures_customers_events1`
    FOREIGN KEY (`customers_events_id`)
    REFERENCES `photoeventpro`.`customers_events` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`purchase_orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`purchase_orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NOT NULL,
  `salesperson_id` INT NOT NULL,
  `billing_information_id` INT NOT NULL,
  `project_id` INT NOT NULL,
  `shipping_information_id` INT NOT NULL,
  `fecha_orden` DATETIME NOT NULL,
  `total_bruto` DECIMAL NOT NULL,
  `total_neto` DECIMAL NOT NULL,
  `estado_pago` ENUM('pendiente', 'pagado', 'cancelado') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  INDEX `fk_purchase_orders_customers1_idx` (`customer_id` ASC) VISIBLE,
  INDEX `fk_purchase_orders_billing_information1_idx` (`billing_information_id` ASC) VISIBLE,
  INDEX `fk_purchase_orders_shipping_information1_idx` (`shipping_information_id` ASC) VISIBLE,
  INDEX `fk_purchase_orders_projects1_idx` (`project_id` ASC) VISIBLE,
  INDEX `fk_purchase_orders_users1_idx` (`salesperson_id` ASC) VISIBLE,
  CONSTRAINT `fk_purchase_orders_customers1`
    FOREIGN KEY (`customer_id`)
    REFERENCES `photoeventpro`.`customers` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_purchase_orders_billing_information1`
    FOREIGN KEY (`billing_information_id`)
    REFERENCES `photoeventpro`.`billing_information` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_purchase_orders_shipping_information1`
    FOREIGN KEY (`shipping_information_id`)
    REFERENCES `photoeventpro`.`shipping_information` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_purchase_orders_projects1`
    FOREIGN KEY (`project_id`)
    REFERENCES `photoeventpro`.`projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_purchase_orders_users1`
    FOREIGN KEY (`salesperson_id`)
    REFERENCES `photoeventpro`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`order_details`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`order_details` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `purchase_order_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `dispatch_status` ENUM('pendiente', 'en_preparacion', 'enviado', 'entregado') NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  INDEX `fk_order_details_purchase_orders1_idx` (`purchase_order_id` ASC) VISIBLE,
  INDEX `fk_order_details_services1_idx` (`service_id` ASC) VISIBLE,
  CONSTRAINT `fk_order_details_purchase_orders1`
    FOREIGN KEY (`purchase_order_id`)
    REFERENCES `photoeventpro`.`purchase_orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_details_services1`
    FOREIGN KEY (`service_id`)
    REFERENCES `photoeventpro`.`services` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`selected_pictures`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`selected_pictures` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `picture_id` INT NOT NULL,
  `order_detail_id` INT NOT NULL,
  `order_index` INT NULL,
  INDEX `fk_services_has_pictures_pictures1_idx` (`picture_id` ASC) VISIBLE,
  PRIMARY KEY (`id`),
  INDEX `fk_selected_pictures_order_details1_idx` (`order_detail_id` ASC) VISIBLE,
  CONSTRAINT `fk_services_has_pictures_pictures1`
    FOREIGN KEY (`picture_id`)
    REFERENCES `photoeventpro`.`pictures` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_selected_pictures_order_details1`
    FOREIGN KEY (`order_detail_id`)
    REFERENCES `photoeventpro`.`order_details` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`configurations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`configurations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `config_key` VARCHAR(255) NOT NULL,
  `config_value` TEXT NULL,
  `config_group` VARCHAR(255) NULL,
  `data_type` ENUM('string', 'integer', 'decimal', 'boolean', 'json', 'date', 'array') NULL,
  `is_editable` TINYINT NULL DEFAULT 1,
  `description` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `config_key_UNIQUE` (`config_key` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`project_salespeople`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`project_salespeople` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `users_id` INT NOT NULL,
  `projects_id` INT NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_users_has_projects_projects1_idx` (`projects_id` ASC) VISIBLE,
  INDEX `fk_users_has_projects_users1_idx` (`users_id` ASC) VISIBLE,
  CONSTRAINT `fk_users_has_projects_users1`
    FOREIGN KEY (`users_id`)
    REFERENCES `photoeventpro`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_projects_projects1`
    FOREIGN KEY (`projects_id`)
    REFERENCES `photoeventpro`.`projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`payments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`payments` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `purchase_orders_id` INT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `payment_method` ENUM('tarjeta', 'transferencia', 'efectivo', 'qr', 'otro') NOT NULL,
  `payment_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('pendiente', 'completado', 'fallido', 'reembolsado') NOT NULL DEFAULT 'completado',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_payments_purchase_orders1_idx` (`purchase_orders_id` ASC) VISIBLE,
  CONSTRAINT `fk_payments_purchase_orders1`
    FOREIGN KEY (`purchase_orders_id`)
    REFERENCES `photoeventpro`.`purchase_orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`projects_has_services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`projects_has_services` (
  `id` INT NOT NULL,
  `services_id` INT NULL,
  `projects_id` INT NULL,
  `precio_venta_publico` VARCHAR(45) NULL,
  PRIMARY KEY (`services_id`, `projects_id`, `id`),
  INDEX `fk_projects_has_services_services1_idx` (`services_id` ASC) VISIBLE,
  INDEX `fk_projects_has_services_projects1_idx` (`projects_id` ASC) VISIBLE,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE,
  CONSTRAINT `fk_projects_has_services_projects1`
    FOREIGN KEY (`projects_id`)
    REFERENCES `photoeventpro`.`projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_has_services_services1`
    FOREIGN KEY (`services_id`)
    REFERENCES `photoeventpro`.`services` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `photoeventpro`.`services_has_projects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `photoeventpro`.`services_has_projects` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `services_id` INT NOT NULL,
  `projects_id` INT NOT NULL,
  `precio_venta_publico` DECIMAL(10,2) NOT NULL,
  `status` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`, `services_id`, `projects_id`),
  INDEX `fk_services_has_projects_projects1_idx` (`projects_id` ASC) VISIBLE,
  INDEX `fk_services_has_projects_services1_idx` (`services_id` ASC) VISIBLE,
  CONSTRAINT `fk_services_has_projects_services1`
    FOREIGN KEY (`services_id`)
    REFERENCES `photoeventpro`.`services` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_services_has_projects_projects1`
    FOREIGN KEY (`projects_id`)
    REFERENCES `photoeventpro`.`projects` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
