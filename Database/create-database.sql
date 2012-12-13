SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `minimalprice` ;
CREATE SCHEMA IF NOT EXISTS `minimalprice` DEFAULT CHARACTER SET utf8 ;
USE `minimalprice` ;

-- -----------------------------------------------------
-- Table `minimalprice`.`bus_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`bus_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`bus_interface` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `bus_interface_name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 17
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`chassis_size_standart`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`chassis_size_standart` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`chassis_size_standart` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `computer_case_size_name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`motherboard_form_factor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`motherboard_form_factor` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`motherboard_form_factor` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `motherboard_form_factor_name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 27
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`category_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`category_image` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`category_image` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`category` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`category` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category` VARCHAR(45) NOT NULL ,
  `parent_category_id` INT NULL ,
  `category_image_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `category_UNIQUE` (`category` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_categories_categoriesImages1` (`category_image_id` ASC) ,
  CONSTRAINT `fk_categories_categoriesImages1`
    FOREIGN KEY (`category_image_id` )
    REFERENCES `minimalprice`.`category_image` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`manufacturer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`manufacturer` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`manufacturer` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `url` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `manufacturer_name` (`name` ASC) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 464
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`product` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`product` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `category_id` INT UNSIGNED NOT NULL ,
  `series` VARCHAR(45) NOT NULL ,
  `model` VARCHAR(45) NOT NULL ,
  `code` VARCHAR(45) NOT NULL ,
  `manufacturer_id` INT UNSIGNED NOT NULL ,
  `description` VARCHAR(200) NOT NULL ,
  `weight_kg` DECIMAL(7,4) NOT NULL ,
  `width_mm` INT NOT NULL ,
  `height_mm` INT NOT NULL ,
  `depth_mm` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `model_UNIQUE` (`model` ASC) ,
  UNIQUE INDEX `code_UNIQUE` (`code` ASC) ,
  INDEX `fk_products_categories1` (`category_id` ASC) ,
  INDEX `fk_products_manufacturers1` (`manufacturer_id` ASC) ,
  CONSTRAINT `fk_products_categories1`
    FOREIGN KEY (`category_id` )
    REFERENCES `minimalprice`.`category` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_products_manufacturers1`
    FOREIGN KEY (`manufacturer_id` )
    REFERENCES `minimalprice`.`manufacturer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`chassis`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`chassis` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`chassis` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `motherboard_form_factor_id` INT UNSIGNED NOT NULL ,
  `computer_case_size_id` INT UNSIGNED NOT NULL ,
  `front_usb_connector_count` INT NOT NULL ,
  `weight_kg` DECIMAL(7,3) NOT NULL ,
  `color` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `motherboard_form_factor_id` (`motherboard_form_factor_id` ASC) ,
  INDEX `computer_case_size_id` (`computer_case_size_id` ASC) ,
  INDEX `fk_chassis_product1` (`product_id` ASC) ,
  CONSTRAINT `computer_cases_ibfk_2`
    FOREIGN KEY (`motherboard_form_factor_id` )
    REFERENCES `minimalprice`.`motherboard_form_factor` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `computer_cases_ibfk_3`
    FOREIGN KEY (`computer_case_size_id` )
    REFERENCES `minimalprice`.`chassis_size_standart` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_chassis_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`fan_size`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`fan_size` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`fan_size` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `diameter_mm` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `cooling_fan_size_diameter_mm` (`diameter_mm` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`drive_bay_width`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`drive_bay_width` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`drive_bay_width` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `drive_bay_width_name` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`expansion_slots`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`expansion_slots` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`expansion_slots` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `expansion_slot_name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 29
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`ram_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`ram_type` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`ram_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `memory_type_name` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`gpu_chip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`gpu_chip` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`gpu_chip` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `manufacturer_id` INT UNSIGNED NOT NULL ,
  `core_name` VARCHAR(45) NOT NULL ,
  `benchmark` INT NOT NULL ,
  `memory_mb` INT NOT NULL ,
  `ram_type_id` INT UNSIGNED NOT NULL ,
  `memory_frequency_mhz` INT NOT NULL ,
  `engine_frequency_mhz` INT NOT NULL ,
  `directx_version` VARCHAR(45) NOT NULL ,
  `opengl_version` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_gpu_chip_manufacturer1` (`manufacturer_id` ASC) ,
  UNIQUE INDEX `core_name_UNIQUE` (`core_name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_gpu_chip_ram_type1` (`ram_type_id` ASC) ,
  CONSTRAINT `fk_gpu_chip_manufacturer1`
    FOREIGN KEY (`manufacturer_id` )
    REFERENCES `minimalprice`.`manufacturer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_gpu_chip_ram_type1`
    FOREIGN KEY (`ram_type_id` )
    REFERENCES `minimalprice`.`ram_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 22
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`hdd`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`hdd` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`hdd` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `bus_interface_id` INT UNSIGNED NOT NULL ,
  `drive_bay_width_id` INT UNSIGNED NOT NULL ,
  `cache_kb` INT NOT NULL ,
  `capacity_gb` INT NOT NULL ,
  `spindle_speed_rpm` INT NOT NULL ,
  `transfer_speed_mbs` INT NOT NULL ,
  `average_latency_msec` INT NOT NULL ,
  `system_type_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `bus_interface_id` (`bus_interface_id` ASC) ,
  INDEX `drive_bay_width_id` (`drive_bay_width_id` ASC) ,
  INDEX `fk_hdd_product1` (`product_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `hard_drives_ibfk_2`
    FOREIGN KEY (`bus_interface_id` )
    REFERENCES `minimalprice`.`bus_interface` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `hard_drives_ibfk_3`
    FOREIGN KEY (`drive_bay_width_id` )
    REFERENCES `minimalprice`.`drive_bay_width` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_hdd_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`chassis_has_drive_bay_width`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`chassis_has_drive_bay_width` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`chassis_has_drive_bay_width` (
  `chassis_id` INT UNSIGNED NOT NULL ,
  `drive_bay_width_id` INT UNSIGNED NOT NULL ,
  `count` INT NOT NULL ,
  PRIMARY KEY (`chassis_id`, `drive_bay_width_id`) ,
  INDEX `computer_case_id` (`chassis_id` ASC) ,
  INDEX `drive_bay_width_id` (`drive_bay_width_id` ASC) ,
  CONSTRAINT `l_computer_cases_drive_bay_widths_ibfk_1`
    FOREIGN KEY (`chassis_id` )
    REFERENCES `minimalprice`.`chassis` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `l_computer_cases_drive_bay_widths_ibfk_2`
    FOREIGN KEY (`drive_bay_width_id` )
    REFERENCES `minimalprice`.`drive_bay_width` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`sound_channel_standard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`sound_channel_standard` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`sound_channel_standard` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `sound_channel_standard_name` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`sound_chip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`sound_chip` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`sound_chip` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `manufacturer_id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `sound_channel_standard_id` INT UNSIGNED NOT NULL ,
  `output_to_noise_ratio_dB` INT NOT NULL ,
  `input_to_noise_ratio_dB` INT NOT NULL ,
  `frequency_response_min_hz` INT NOT NULL ,
  `frequency_response_max_hz` INT NOT NULL ,
  `digital_to_analog_converter_bits` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `sound_chip_name` (`name` ASC) ,
  INDEX `sound_channel_standard_id` (`sound_channel_standard_id` ASC) ,
  INDEX `fk_sound_chip_manufacturer1` (`manufacturer_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `sound_chips_ibfk_2`
    FOREIGN KEY (`sound_channel_standard_id` )
    REFERENCES `minimalprice`.`sound_channel_standard` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_sound_chip_manufacturer1`
    FOREIGN KEY (`manufacturer_id` )
    REFERENCES `minimalprice`.`manufacturer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`nb_chip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`nb_chip` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`nb_chip` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `manufacturer_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `motherboard_nb_chipset_name` (`name` ASC) ,
  INDEX `fk_nb_chip_manufacturer1` (`manufacturer_id` ASC) ,
  CONSTRAINT `fk_nb_chip_manufacturer1`
    FOREIGN KEY (`manufacturer_id` )
    REFERENCES `minimalprice`.`manufacturer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 233
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`sb_chip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`sb_chip` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`sb_chip` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `manufacturer_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `motherboard_sb_chipset_name` (`name` ASC) ,
  INDEX `fk_sb_chip_manufacturer1` (`manufacturer_id` ASC) ,
  CONSTRAINT `fk_sb_chip_manufacturer1`
    FOREIGN KEY (`manufacturer_id` )
    REFERENCES `minimalprice`.`manufacturer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 79
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`cpu_socket`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`cpu_socket` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`cpu_socket` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `processor_socket_name` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 48
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`lan_chip`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`lan_chip` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`lan_chip` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `manufacturer_id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `lan_chip_name` (`name` ASC) ,
  INDEX `fk_lan_chip_manufacturer1` (`manufacturer_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_lan_chip_manufacturer1`
    FOREIGN KEY (`manufacturer_id` )
    REFERENCES `minimalprice`.`manufacturer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 28
DEFAULT CHARACTER SET = utf8
COMMENT = '			';


-- -----------------------------------------------------
-- Table `minimalprice`.`ram_form_factor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`ram_form_factor` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`ram_form_factor` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`psu_connector`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`psu_connector` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`psu_connector` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`motherboard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`motherboard` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`motherboard` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `motherboard_form_factor_id` INT UNSIGNED NOT NULL ,
  `cpu_socket_id` INT UNSIGNED NOT NULL ,
  `gpu_chip_id` INT UNSIGNED NULL ,
  `ram_form_factor_id` INT UNSIGNED NOT NULL ,
  `ram_type_id` INT UNSIGNED NOT NULL ,
  `nb_chip_id` INT UNSIGNED NOT NULL ,
  `sb_chip_id` INT UNSIGNED NOT NULL ,
  `lan_chip_id` INT UNSIGNED NOT NULL ,
  `sound_chip_id` INT UNSIGNED NOT NULL ,
  `front_usb_header_count` INT NOT NULL ,
  `psu_connector_id` INT UNSIGNED NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `motherboard_nb_chipset_id` (`nb_chip_id` ASC) ,
  INDEX `motherboard_sb_chipset_id` (`sb_chip_id` ASC) ,
  INDEX `processor_socket_id` (`cpu_socket_id` ASC) ,
  INDEX `memory_type_id` (`ram_type_id` ASC) ,
  INDEX `motherboard_form_factor_id` (`motherboard_form_factor_id` ASC) ,
  INDEX `gpu_id` (`gpu_chip_id` ASC) ,
  INDEX `lan_chip_id` (`lan_chip_id` ASC) ,
  INDEX `sound_chip_id` (`sound_chip_id` ASC) ,
  INDEX `fk_motherboards_ram_form_factor1` (`ram_form_factor_id` ASC) ,
  INDEX `fk_motherboard_psu_connector1` (`psu_connector_id` ASC) ,
  INDEX `fk_motherboard_product1` (`product_id` ASC) ,
  CONSTRAINT `motherboards_ibfk_10`
    FOREIGN KEY (`sound_chip_id` )
    REFERENCES `minimalprice`.`sound_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_2`
    FOREIGN KEY (`nb_chip_id` )
    REFERENCES `minimalprice`.`nb_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_3`
    FOREIGN KEY (`sb_chip_id` )
    REFERENCES `minimalprice`.`sb_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_4`
    FOREIGN KEY (`cpu_socket_id` )
    REFERENCES `minimalprice`.`cpu_socket` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_5`
    FOREIGN KEY (`ram_type_id` )
    REFERENCES `minimalprice`.`ram_type` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_6`
    FOREIGN KEY (`motherboard_form_factor_id` )
    REFERENCES `minimalprice`.`motherboard_form_factor` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_7`
    FOREIGN KEY (`gpu_chip_id` )
    REFERENCES `minimalprice`.`gpu_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `motherboards_ibfk_9`
    FOREIGN KEY (`lan_chip_id` )
    REFERENCES `minimalprice`.`lan_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_motherboards_ram_form_factor1`
    FOREIGN KEY (`ram_form_factor_id` )
    REFERENCES `minimalprice`.`ram_form_factor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_motherboard_psu_connector1`
    FOREIGN KEY (`psu_connector_id` )
    REFERENCES `minimalprice`.`psu_connector` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_motherboard_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`motherboard_has_bus_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`motherboard_has_bus_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`motherboard_has_bus_interface` (
  `motherboard_id` INT UNSIGNED NOT NULL ,
  `bus_interface_id` INT UNSIGNED NOT NULL ,
  `bus_interface_count` INT NOT NULL ,
  PRIMARY KEY (`motherboard_id`, `bus_interface_id`) ,
  INDEX `motherboard_id` (`motherboard_id` ASC) ,
  INDEX `bus_interface_id` (`bus_interface_id` ASC) ,
  CONSTRAINT `l_motherboard_bus_interfaces_ibfk_1`
    FOREIGN KEY (`motherboard_id` )
    REFERENCES `minimalprice`.`motherboard` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `l_motherboard_bus_interfaces_ibfk_2`
    FOREIGN KEY (`bus_interface_id` )
    REFERENCES `minimalprice`.`bus_interface` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`motherboard_has_expansion_slot`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`motherboard_has_expansion_slot` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`motherboard_has_expansion_slot` (
  `motherboard_id` INT UNSIGNED NOT NULL ,
  `expansion_slot_id` INT UNSIGNED NOT NULL ,
  `expansion_slot_count` INT NOT NULL ,
  PRIMARY KEY (`motherboard_id`, `expansion_slot_id`) ,
  INDEX `motherboard_id` (`motherboard_id` ASC) ,
  INDEX `expansion_slot_id` (`expansion_slot_id` ASC) ,
  CONSTRAINT `l_motherboards_expansion_slots_ibfk_1`
    FOREIGN KEY (`motherboard_id` )
    REFERENCES `minimalprice`.`motherboard` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `l_motherboards_expansion_slots_ibfk_2`
    FOREIGN KEY (`expansion_slot_id` )
    REFERENCES `minimalprice`.`expansion_slots` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`ram`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`ram` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`ram` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `memory_type_id` INT UNSIGNED NOT NULL ,
  `ram_form_factor_id` INT UNSIGNED NOT NULL ,
  `speed_mhz` INT NOT NULL ,
  `size_mb` INT NOT NULL ,
  `cl_cas` VARCHAR(45) NOT NULL ,
  `voltage` DECIMAL(4,2) NOT NULL ,
  `error_checking` TINYINT(1) NOT NULL ,
  `kit` INT NOT NULL ,
  `channel` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `memory_type_id` (`memory_type_id` ASC) ,
  INDEX `fk_memories_ram_form_factor1` (`ram_form_factor_id` ASC) ,
  INDEX `fk_ram_product1` (`product_id` ASC) ,
  CONSTRAINT `memories_ibfk_2`
    FOREIGN KEY (`memory_type_id` )
    REFERENCES `minimalprice`.`ram_type` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_memories_ram_form_factor1`
    FOREIGN KEY (`ram_form_factor_id` )
    REFERENCES `minimalprice`.`ram_form_factor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ram_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`optical_disk_format`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`optical_disk_format` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`optical_disk_format` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `optical_disk_format_name` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`optical_drive`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`optical_drive` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`optical_drive` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `bus_interface_id` INT UNSIGNED NOT NULL ,
  `drive_bay_width_id` INT UNSIGNED NOT NULL ,
  `buffer_kb` INT NOT NULL ,
  `system_type_id` INT NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `bus_interface_id` (`bus_interface_id` ASC) ,
  INDEX `drive_bay_width_id` (`drive_bay_width_id` ASC) ,
  INDEX `fk_optical_drive_product1` (`product_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `optical_drives_ibfk_2`
    FOREIGN KEY (`bus_interface_id` )
    REFERENCES `minimalprice`.`bus_interface` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `optical_drives_ibfk_3`
    FOREIGN KEY (`drive_bay_width_id` )
    REFERENCES `minimalprice`.`drive_bay_width` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_optical_drive_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`peripheral_interface` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `peripheral_interface_name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`psu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`psu` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`psu` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `motherboard_form_factor_id` INT UNSIGNED NOT NULL ,
  `fan_size_id` INT UNSIGNED NOT NULL ,
  `total_power_w` INT NOT NULL ,
  `energy_efficiency_percentage` INT NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `motherboard_form_factor_id` (`motherboard_form_factor_id` ASC) ,
  INDEX `cooling_fan_size_id` (`fan_size_id` ASC) ,
  UNIQUE INDEX `motherboard_form_factor_id_UNIQUE` (`motherboard_form_factor_id` ASC) ,
  UNIQUE INDEX `fan_size_id_UNIQUE` (`fan_size_id` ASC) ,
  INDEX `fk_psu_product1` (`product_id` ASC) ,
  CONSTRAINT `power_supplies_ibfk_2`
    FOREIGN KEY (`motherboard_form_factor_id` )
    REFERENCES `minimalprice`.`motherboard_form_factor` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `power_supplies_ibfk_4`
    FOREIGN KEY (`fan_size_id` )
    REFERENCES `minimalprice`.`fan_size` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_psu_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`cpu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`cpu` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`cpu` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `cpu_socket_id` INT UNSIGNED NOT NULL ,
  `gpu_chip_id` INT UNSIGNED NULL ,
  `benchmark` INT NOT NULL ,
  `cores` INT NOT NULL ,
  `frequency_mhz` INT UNSIGNED NOT NULL ,
  `instruction_set_bits` INT NOT NULL ,
  `cache_l1_kb` INT NOT NULL ,
  `cache_l2_kb` INT NOT NULL ,
  `cache_l3_kb` INT NOT NULL ,
  `turbo_frequency_mhz` INT NOT NULL ,
  `threads` INT NOT NULL ,
  `power_consumption_w` DECIMAL(3) NOT NULL ,
  `launch_date` DATETIME NOT NULL ,
  `bus_core_ratio` INT NOT NULL ,
  `bus_speed_mhz` INT NOT NULL ,
  `max_tdp` INT NOT NULL ,
  `max_ram_mb` INT NOT NULL ,
  `ram_channels` INT NOT NULL ,
  `ram_bandwidth` INT NOT NULL ,
  `max_cpus_configuration` INT NOT NULL ,
  `technology_nm` INT NOT NULL ,
  `is_fan_included` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `processor_id` (`id` ASC) ,
  INDEX `processor_socket_id` (`cpu_socket_id` ASC) ,
  INDEX `fk_processor_gpu1` (`gpu_chip_id` ASC) ,
  INDEX `fk_cpu_product1` (`product_id` ASC) ,
  CONSTRAINT `processors_ibfk_2`
    FOREIGN KEY (`cpu_socket_id` )
    REFERENCES `minimalprice`.`cpu_socket` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_processor_gpu1`
    FOREIGN KEY (`gpu_chip_id` )
    REFERENCES `minimalprice`.`gpu_chip` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cpu_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`sound_card`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`sound_card` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`sound_card` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `expansion_slot_id` INT UNSIGNED NOT NULL ,
  `sound_chip_id` INT UNSIGNED NOT NULL ,
  `system_type_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `expansion_slot_id` (`expansion_slot_id` ASC) ,
  INDEX `sound_chip_id` (`sound_chip_id` ASC) ,
  INDEX `fk_sound_card_product1` (`product_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `sound_cards_ibfk_2`
    FOREIGN KEY (`expansion_slot_id` )
    REFERENCES `minimalprice`.`expansion_slots` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `sound_cards_ibfk_3`
    FOREIGN KEY (`sound_chip_id` )
    REFERENCES `minimalprice`.`sound_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_sound_card_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`gpu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`gpu` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`gpu` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `gpu_chip_id` INT UNSIGNED NOT NULL ,
  `expansion_slot_id` INT UNSIGNED NOT NULL ,
  `ram_type_id` INT UNSIGNED NOT NULL ,
  `benchmark` INT NOT NULL ,
  `memory_mb` INT NOT NULL ,
  `engine_frequency_mhz` INT NOT NULL ,
  `memory_frequency_mhz` INT NOT NULL ,
  `max_displays` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `expansion_slot_id` (`expansion_slot_id` ASC) ,
  INDEX `gpu_id` (`gpu_chip_id` ASC) ,
  INDEX `memory_type_id` (`ram_type_id` ASC) ,
  INDEX `fk_gpu_product1` (`product_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `video_adapters_ibfk_2`
    FOREIGN KEY (`expansion_slot_id` )
    REFERENCES `minimalprice`.`expansion_slots` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `video_adapters_ibfk_3`
    FOREIGN KEY (`gpu_chip_id` )
    REFERENCES `minimalprice`.`gpu_chip` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `video_adapters_ibfk_5`
    FOREIGN KEY (`ram_type_id` )
    REFERENCES `minimalprice`.`ram_type` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk_gpu_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`optical_drive_has_optical_disk_format`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`optical_drive_has_optical_disk_format` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`optical_drive_has_optical_disk_format` (
  `optical_drive_id` INT UNSIGNED NOT NULL ,
  `optical_disk_format_id` INT UNSIGNED NOT NULL ,
  `write_speed` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`optical_drive_id`, `optical_disk_format_id`) ,
  INDEX `fk_optical_drives_has_optical_disk_formats_optical_disk_forma1` (`optical_disk_format_id` ASC) ,
  INDEX `fk_optical_drives_has_optical_disk_formats_optical_drives1` (`optical_drive_id` ASC) ,
  CONSTRAINT `fk_optical_drives_has_optical_disk_formats_optical_drives1`
    FOREIGN KEY (`optical_drive_id` )
    REFERENCES `minimalprice`.`optical_drive` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_optical_drives_has_optical_disk_formats_optical_disk_forma1`
    FOREIGN KEY (`optical_disk_format_id` )
    REFERENCES `minimalprice`.`optical_disk_format` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`chassis_has_fan_size`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`chassis_has_fan_size` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`chassis_has_fan_size` (
  `chassis_id` INT UNSIGNED NOT NULL ,
  `fan_size_id` INT UNSIGNED NOT NULL ,
  `fans_count` INT NOT NULL ,
  PRIMARY KEY (`chassis_id`, `fan_size_id`) ,
  INDEX `fk_computer_cases_has_fan_sizes_fan_sizes1` (`fan_size_id` ASC) ,
  INDEX `fk_computer_cases_has_fan_sizes_computer_cases1` (`chassis_id` ASC) ,
  CONSTRAINT `fk_computer_cases_has_fan_sizes_computer_cases1`
    FOREIGN KEY (`chassis_id` )
    REFERENCES `minimalprice`.`chassis` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_computer_cases_has_fan_sizes_fan_sizes1`
    FOREIGN KEY (`fan_size_id` )
    REFERENCES `minimalprice`.`fan_size` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`motherboard_has_peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`motherboard_has_peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`motherboard_has_peripheral_interface` (
  `motherboard_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  `count` INT NOT NULL ,
  PRIMARY KEY (`motherboard_id`, `peripheral_interface_id`) ,
  INDEX `fk_motherboards_has_peripheral_interfaces_peripheral_interfac1` (`peripheral_interface_id` ASC) ,
  INDEX `fk_motherboards_has_peripheral_interfaces_motherboards1` (`motherboard_id` ASC) ,
  CONSTRAINT `fk_motherboards_has_peripheral_interfaces_motherboards1`
    FOREIGN KEY (`motherboard_id` )
    REFERENCES `minimalprice`.`motherboard` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_motherboards_has_peripheral_interfaces_peripheral_interfac1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`user_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`user_type` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`user_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`country` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`country` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`user` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_type_id` INT UNSIGNED NOT NULL ,
  `country_id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  INDEX `fk_users_userTypes1` (`user_type_id` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  INDEX `fk_users_countries1` (`country_id` ASC) ,
  CONSTRAINT `fk_users_userTypes1`
    FOREIGN KEY (`user_type_id` )
    REFERENCES `minimalprice`.`user_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_countries1`
    FOREIGN KEY (`country_id` )
    REFERENCES `minimalprice`.`country` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`ads`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`ads` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`ads` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `place` INT NOT NULL ,
  `url` VARCHAR(200) NOT NULL ,
  `buyer` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `place_UNIQUE` (`place` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`parser_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`parser_template` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`parser_template` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `value` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`shop`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`shop` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`shop` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `url` VARCHAR(200) NOT NULL ,
  `referral_url` VARCHAR(200) NOT NULL ,
  `country_id` INT UNSIGNED NOT NULL ,
  `parser_template_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) ,
  INDEX `fk_shops_countries1` (`country_id` ASC) ,
  INDEX `fk_shop_parser_template1` (`parser_template_id` ASC) ,
  CONSTRAINT `fk_shops_countries1`
    FOREIGN KEY (`country_id` )
    REFERENCES `minimalprice`.`country` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_shop_parser_template1`
    FOREIGN KEY (`parser_template_id` )
    REFERENCES `minimalprice`.`parser_template` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`psu_protection`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`psu_protection` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`psu_protection` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`psu_has_psu_protection`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`psu_has_psu_protection` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`psu_has_psu_protection` (
  `psu_id` INT UNSIGNED NOT NULL ,
  `psu_protection_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`psu_id`, `psu_protection_id`) ,
  INDEX `fk_psus_has_psu_protections_psu_protections1` (`psu_protection_id` ASC) ,
  INDEX `fk_psus_has_psu_protections_psus1` (`psu_id` ASC) ,
  CONSTRAINT `fk_psus_has_psu_protections_psus1`
    FOREIGN KEY (`psu_id` )
    REFERENCES `minimalprice`.`psu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_psus_has_psu_protections_psu_protections1`
    FOREIGN KEY (`psu_protection_id` )
    REFERENCES `minimalprice`.`psu_protection` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`currency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`currency` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`currency` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `symbol` VARCHAR(2) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `symbol_UNIQUE` (`symbol` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`shop_has_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`shop_has_product` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`shop_has_product` (
  `shop_id` INT UNSIGNED NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  `cost` DECIMAL(7,2) NOT NULL ,
  `amount` INT NOT NULL ,
  `warranty` INT NOT NULL ,
  `url` VARCHAR(200) NOT NULL ,
  `currency_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`shop_id`, `product_id`) ,
  INDEX `fk_shops_has_products_products1` (`product_id` ASC) ,
  INDEX `fk_shops_has_products_shops1` (`shop_id` ASC) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) ,
  INDEX `fk_shops_has_products_currency1` (`currency_id` ASC) ,
  CONSTRAINT `fk_shops_has_products_shops1`
    FOREIGN KEY (`shop_id` )
    REFERENCES `minimalprice`.`shop` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_shops_has_products_products1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_shops_has_products_currency1`
    FOREIGN KEY (`currency_id` )
    REFERENCES `minimalprice`.`currency` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`accessory`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`accessory` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`accessory` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_accessory_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_accessory_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`technology` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`gpu_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`gpu_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`gpu_has_technology` (
  `gpu_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`gpu_id`, `technology_id`) ,
  INDEX `fk_video_adapters_has_technologies_technologies1` (`technology_id` ASC) ,
  INDEX `fk_video_adapters_has_technologies_video_adapters1` (`gpu_id` ASC) ,
  CONSTRAINT `fk_video_adapters_has_technologies_video_adapters1`
    FOREIGN KEY (`gpu_id` )
    REFERENCES `minimalprice`.`gpu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_video_adapters_has_technologies_technologies1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`sound_chip_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`sound_chip_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`sound_chip_has_technology` (
  `sound_chip_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`sound_chip_id`, `technology_id`) ,
  INDEX `fk_sound_cards_has_technologies_technologies1` (`technology_id` ASC) ,
  INDEX `fk_sound_cards_has_technologies_sound_chips1` (`sound_chip_id` ASC) ,
  UNIQUE INDEX `sound_chip_id_UNIQUE` (`sound_chip_id` ASC) ,
  CONSTRAINT `fk_sound_cards_has_technologies_technologies1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sound_cards_has_technologies_sound_chips1`
    FOREIGN KEY (`sound_chip_id` )
    REFERENCES `minimalprice`.`sound_chip` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`gpu_has_peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`gpu_has_peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`gpu_has_peripheral_interface` (
  `gpu_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`gpu_id`, `peripheral_interface_id`) ,
  INDEX `fk_video_adapters_has_peripheral_interfaces_peripheral_interf1` (`peripheral_interface_id` ASC) ,
  INDEX `fk_video_adapters_has_peripheral_interfaces_video_adapters1` (`gpu_id` ASC) ,
  CONSTRAINT `fk_video_adapters_has_peripheral_interfaces_video_adapters1`
    FOREIGN KEY (`gpu_id` )
    REFERENCES `minimalprice`.`gpu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_video_adapters_has_peripheral_interfaces_peripheral_interf1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`sound_card_has_peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`sound_card_has_peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`sound_card_has_peripheral_interface` (
  `sound_card_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`sound_card_id`, `peripheral_interface_id`) ,
  INDEX `fk_sound_cards_has_peripheral_interfaces_peripheral_interfaces1` (`peripheral_interface_id` ASC) ,
  INDEX `fk_sound_cards_has_peripheral_interfaces_sound_cards1` (`sound_card_id` ASC) ,
  CONSTRAINT `fk_sound_cards_has_peripheral_interfaces_sound_cards1`
    FOREIGN KEY (`sound_card_id` )
    REFERENCES `minimalprice`.`sound_card` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sound_cards_has_peripheral_interfaces_peripheral_interfaces1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`cpu_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`cpu_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`cpu_has_technology` (
  `cpu_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`cpu_id`, `technology_id`) ,
  INDEX `fk_processor_has_technology_technology1` (`technology_id` ASC) ,
  INDEX `fk_processor_has_technology_processor1` (`cpu_id` ASC) ,
  CONSTRAINT `fk_processor_has_technology_processor1`
    FOREIGN KEY (`cpu_id` )
    REFERENCES `minimalprice`.`cpu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_processor_has_technology_technology1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`lan_card`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`lan_card` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`lan_card` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `expansion_slots_id` INT UNSIGNED NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_lan_card_expansion_slots1` (`expansion_slots_id` ASC) ,
  INDEX `fk_lan_card_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_lan_card_expansion_slots1`
    FOREIGN KEY (`expansion_slots_id` )
    REFERENCES `minimalprice`.`expansion_slots` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lan_card_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`psu_has_psu_connector`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`psu_has_psu_connector` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`psu_has_psu_connector` (
  `psu_id` INT UNSIGNED NOT NULL ,
  `psu_connector_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`psu_id`, `psu_connector_id`) ,
  INDEX `fk_psu_has_psu_connector_psu_connector1` (`psu_connector_id` ASC) ,
  INDEX `fk_psu_has_psu_connector_psu1` (`psu_id` ASC) ,
  CONSTRAINT `fk_psu_has_psu_connector_psu1`
    FOREIGN KEY (`psu_id` )
    REFERENCES `minimalprice`.`psu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_psu_has_psu_connector_psu_connector1`
    FOREIGN KEY (`psu_connector_id` )
    REFERENCES `minimalprice`.`psu_connector` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`chassis_has_peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`chassis_has_peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`chassis_has_peripheral_interface` (
  `chassis_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`chassis_id`, `peripheral_interface_id`) ,
  INDEX `fk_chassis_has_peripheral_interface_peripheral_interface1` (`peripheral_interface_id` ASC) ,
  INDEX `fk_chassis_has_peripheral_interface_chassis1` (`chassis_id` ASC) ,
  CONSTRAINT `fk_chassis_has_peripheral_interface_chassis1`
    FOREIGN KEY (`chassis_id` )
    REFERENCES `minimalprice`.`chassis` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chassis_has_peripheral_interface_peripheral_interface1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`motherboard_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`motherboard_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`motherboard_has_technology` (
  `motherboard_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`motherboard_id`, `technology_id`) ,
  INDEX `fk_motherboard_has_technology_technology1` (`technology_id` ASC) ,
  INDEX `fk_motherboard_has_technology_motherboard1` (`motherboard_id` ASC) ,
  CONSTRAINT `fk_motherboard_has_technology_motherboard1`
    FOREIGN KEY (`motherboard_id` )
    REFERENCES `minimalprice`.`motherboard` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_motherboard_has_technology_technology1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`lan_card_has_peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`lan_card_has_peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`lan_card_has_peripheral_interface` (
  `lan_card_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`lan_card_id`, `peripheral_interface_id`) ,
  INDEX `fk_lan_card_has_peripheral_interface_peripheral_interface1` (`peripheral_interface_id` ASC) ,
  INDEX `fk_lan_card_has_peripheral_interface_lan_card1` (`lan_card_id` ASC) ,
  CONSTRAINT `fk_lan_card_has_peripheral_interface_lan_card1`
    FOREIGN KEY (`lan_card_id` )
    REFERENCES `minimalprice`.`lan_card` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lan_card_has_peripheral_interface_peripheral_interface1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`hdd_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`hdd_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`hdd_has_technology` (
  `hdd_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`hdd_id`, `technology_id`) ,
  INDEX `fk_hdd_has_technology_technology1` (`technology_id` ASC) ,
  INDEX `fk_hdd_has_technology_hdd1` (`hdd_id` ASC) ,
  CONSTRAINT `fk_hdd_has_technology_hdd1`
    FOREIGN KEY (`hdd_id` )
    REFERENCES `minimalprice`.`hdd` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_hdd_has_technology_technology1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`optical_drive_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`optical_drive_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`optical_drive_has_technology` (
  `optical_drive_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`optical_drive_id`, `technology_id`) ,
  INDEX `fk_optical_drive_has_technology_technology1` (`technology_id` ASC) ,
  INDEX `fk_optical_drive_has_technology_optical_drive1` (`optical_drive_id` ASC) ,
  CONSTRAINT `fk_optical_drive_has_technology_optical_drive1`
    FOREIGN KEY (`optical_drive_id` )
    REFERENCES `minimalprice`.`optical_drive` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_optical_drive_has_technology_technology1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`fan`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`fan` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`fan` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fan_size_id` INT UNSIGNED NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  `psu_connector_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_fan_fan_size1` (`fan_size_id` ASC) ,
  INDEX `fk_fan_product1` (`product_id` ASC) ,
  INDEX `fk_fan_psu_connector1` (`psu_connector_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_fan_fan_size1`
    FOREIGN KEY (`fan_size_id` )
    REFERENCES `minimalprice`.`fan_size` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fan_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_fan_psu_connector1`
    FOREIGN KEY (`psu_connector_id` )
    REFERENCES `minimalprice`.`psu_connector` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`monitor_video_format`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`monitor_video_format` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`monitor_video_format` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`monitor_backlight_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`monitor_backlight_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`monitor_backlight_technology` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`monitor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`monitor` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`monitor` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `monitor_video_format_id` INT UNSIGNED NOT NULL ,
  `monitor_backlight_technology_id` INT UNSIGNED NOT NULL ,
  `psu_connector_id` INT UNSIGNED NOT NULL ,
  `screen_size` DECIMAL(5,2) NOT NULL ,
  `pixel_size_mm` DECIMAL(6,4) NOT NULL ,
  `panel_type` VARCHAR(45) NOT NULL ,
  `resolution` VARCHAR(45) NOT NULL ,
  `brightness_cdm` INT NOT NULL ,
  `response_time_ms` INT NOT NULL ,
  `static_contrast_ratio` INT NOT NULL ,
  `dynamic_constrast_ratio` INT NOT NULL ,
  `viewing_angle_horizontal` INT NOT NULL ,
  `viewing_angle_vertical` INT NOT NULL ,
  `power_compsumption_w` INT NOT NULL ,
  `power_standby_w` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_monitor_product1` (`product_id` ASC) ,
  INDEX `fk_monitor_monitor_video_format1` (`monitor_video_format_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_monitor_monitor_backlight_technology1` (`monitor_backlight_technology_id` ASC) ,
  INDEX `fk_monitor_psu_connector1` (`psu_connector_id` ASC) ,
  CONSTRAINT `fk_monitor_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_monitor_monitor_video_format1`
    FOREIGN KEY (`monitor_video_format_id` )
    REFERENCES `minimalprice`.`monitor_video_format` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_monitor_monitor_backlight_technology1`
    FOREIGN KEY (`monitor_backlight_technology_id` )
    REFERENCES `minimalprice`.`monitor_backlight_technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_monitor_psu_connector1`
    FOREIGN KEY (`psu_connector_id` )
    REFERENCES `minimalprice`.`psu_connector` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`monitor_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`monitor_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`monitor_has_technology` (
  `monitor_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`monitor_id`, `technology_id`) ,
  INDEX `fk_monitor_has_technology_technology1` (`technology_id` ASC) ,
  INDEX `fk_monitor_has_technology_monitor1` (`monitor_id` ASC) ,
  CONSTRAINT `fk_monitor_has_technology_monitor1`
    FOREIGN KEY (`monitor_id` )
    REFERENCES `minimalprice`.`monitor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_monitor_has_technology_technology1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`monitor_has_peripheral_interface`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`monitor_has_peripheral_interface` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`monitor_has_peripheral_interface` (
  `monitor_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`monitor_id`, `peripheral_interface_id`) ,
  INDEX `fk_monitor_has_peripheral_interface_peripheral_interface1` (`peripheral_interface_id` ASC) ,
  INDEX `fk_monitor_has_peripheral_interface_monitor1` (`monitor_id` ASC) ,
  CONSTRAINT `fk_monitor_has_peripheral_interface_monitor1`
    FOREIGN KEY (`monitor_id` )
    REFERENCES `minimalprice`.`monitor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_monitor_has_peripheral_interface_peripheral_interface1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`mouse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`mouse` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`mouse` (
  `id` INT UNSIGNED NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  `resolution` VARCHAR(45) NOT NULL ,
  `connectivity_type_id` INT UNSIGNED NOT NULL ,
  `is_rechargable` TINYINT(1) NOT NULL ,
  `is_retractable_cord` TINYINT(1) NOT NULL ,
  `is_sleep_mode` TINYINT(1) NOT NULL ,
  `battery_working_time_h` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_mouse_product1` (`product_id` ASC) ,
  INDEX `fk_mouse_peripheral_interface1` (`peripheral_interface_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_mouse_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mouse_peripheral_interface1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`button`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`button` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`button` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `description` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`mouse_has_button`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`mouse_has_button` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`mouse_has_button` (
  `mouse_id` INT UNSIGNED NOT NULL ,
  `button_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`mouse_id`, `button_id`) ,
  INDEX `fk_mouse_has_button_button1` (`button_id` ASC) ,
  INDEX `fk_mouse_has_button_mouse1` (`mouse_id` ASC) ,
  CONSTRAINT `fk_mouse_has_button_mouse1`
    FOREIGN KEY (`mouse_id` )
    REFERENCES `minimalprice`.`mouse` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mouse_has_button_button1`
    FOREIGN KEY (`button_id` )
    REFERENCES `minimalprice`.`button` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`package_content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`package_content` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`package_content` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`product_has_package_content`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`product_has_package_content` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`product_has_package_content` (
  `product_id` INT UNSIGNED NOT NULL ,
  `package_content_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`product_id`, `package_content_id`) ,
  INDEX `fk_product_has_package_content_package_content1` (`package_content_id` ASC) ,
  INDEX `fk_product_has_package_content_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_product_has_package_content_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_has_package_content_package_content1`
    FOREIGN KEY (`package_content_id` )
    REFERENCES `minimalprice`.`package_content` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`mouse_has_technology`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`mouse_has_technology` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`mouse_has_technology` (
  `mouse_id` INT UNSIGNED NOT NULL ,
  `technology_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`mouse_id`, `technology_id`) ,
  INDEX `fk_mouse_has_technology_technology1` (`technology_id` ASC) ,
  INDEX `fk_mouse_has_technology_mouse1` (`mouse_id` ASC) ,
  CONSTRAINT `fk_mouse_has_technology_mouse1`
    FOREIGN KEY (`mouse_id` )
    REFERENCES `minimalprice`.`mouse` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_mouse_has_technology_technology1`
    FOREIGN KEY (`technology_id` )
    REFERENCES `minimalprice`.`technology` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`lan_chip_has_connectivity_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`lan_chip_has_connectivity_type` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`lan_chip_has_connectivity_type` (
  `lan_chip_id` INT UNSIGNED NOT NULL ,
  `connectivity_type_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`lan_chip_id`, `connectivity_type_id`) ,
  INDEX `fk_lan_chip_has_connectivity_type_lan_chip1` (`lan_chip_id` ASC) ,
  CONSTRAINT `fk_lan_chip_has_connectivity_type_lan_chip1`
    FOREIGN KEY (`lan_chip_id` )
    REFERENCES `minimalprice`.`lan_chip` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `minimalprice`.`keyboard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`keyboard` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`keyboard` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `connectivity_type_id` INT UNSIGNED NOT NULL ,
  `mouse_id` INT UNSIGNED NULL ,
  `battery_working_time_h` INT NOT NULL ,
  `is_iluminated` TINYINT(1) NOT NULL ,
  `is_washable` TINYINT(1) NOT NULL ,
  `is_rechargable` TINYINT(1) NOT NULL ,
  `is_slim_buttons` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_keyboard_product1` (`product_id` ASC) ,
  INDEX `fk_keyboard_mouse1` (`mouse_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_keyboard_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_keyboard_mouse1`
    FOREIGN KEY (`mouse_id` )
    REFERENCES `minimalprice`.`mouse` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`keyboard_has_button`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`keyboard_has_button` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`keyboard_has_button` (
  `keyboard_id` INT UNSIGNED NOT NULL ,
  `button_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`keyboard_id`, `button_id`) ,
  INDEX `fk_keyboard_has_button_button1` (`button_id` ASC) ,
  INDEX `fk_keyboard_has_button_keyboard1` (`keyboard_id` ASC) ,
  CONSTRAINT `fk_keyboard_has_button_keyboard1`
    FOREIGN KEY (`keyboard_id` )
    REFERENCES `minimalprice`.`keyboard` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_keyboard_has_button_button1`
    FOREIGN KEY (`button_id` )
    REFERENCES `minimalprice`.`button` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`card_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`card_type` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`card_type` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`card_reader`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`card_reader` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`card_reader` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  `peripheral_interface_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_card_reader_product1` (`product_id` ASC) ,
  INDEX `fk_card_reader_peripheral_interface1` (`peripheral_interface_id` ASC) ,
  CONSTRAINT `fk_card_reader_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_card_reader_peripheral_interface1`
    FOREIGN KEY (`peripheral_interface_id` )
    REFERENCES `minimalprice`.`peripheral_interface` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`card_reader_has_card_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`card_reader_has_card_type` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`card_reader_has_card_type` (
  `card_reader_id` INT UNSIGNED NOT NULL ,
  `card_type_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`card_reader_id`, `card_type_id`) ,
  INDEX `fk_card_reader_has_card_type_card_type1` (`card_type_id` ASC) ,
  INDEX `fk_card_reader_has_card_type_card_reader1` (`card_reader_id` ASC) ,
  CONSTRAINT `fk_card_reader_has_card_type_card_reader1`
    FOREIGN KEY (`card_reader_id` )
    REFERENCES `minimalprice`.`card_reader` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_card_reader_has_card_type_card_type1`
    FOREIGN KEY (`card_type_id` )
    REFERENCES `minimalprice`.`card_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`computer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`computer` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`computer` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_computer_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_computer_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`computer_has_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`computer_has_product` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`computer_has_product` (
  `computer_id` INT UNSIGNED NOT NULL ,
  `product_id` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`computer_id`, `product_id`) ,
  INDEX `fk_computer_has_product_product1` (`product_id` ASC) ,
  INDEX `fk_computer_has_product_computer1` (`computer_id` ASC) ,
  CONSTRAINT `fk_computer_has_product_computer1`
    FOREIGN KEY (`computer_id` )
    REFERENCES `minimalprice`.`computer` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_computer_has_product_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`product_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`product_image` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`product_image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `url_UNIQUE` (`url` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `minimalprice`.`product_has_product_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `minimalprice`.`product_has_product_image` ;

CREATE  TABLE IF NOT EXISTS `minimalprice`.`product_has_product_image` (
  `product_id` INT UNSIGNED NOT NULL ,
  `product_image_id` INT NOT NULL ,
  PRIMARY KEY (`product_id`, `product_image_id`) ,
  INDEX `fk_product_has_product_image_product_image1` (`product_image_id` ASC) ,
  INDEX `fk_product_has_product_image_product1` (`product_id` ASC) ,
  CONSTRAINT `fk_product_has_product_image_product1`
    FOREIGN KEY (`product_id` )
    REFERENCES `minimalprice`.`product` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_has_product_image_product_image1`
    FOREIGN KEY (`product_image_id` )
    REFERENCES `minimalprice`.`product_image` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE USER `minimalprice` IDENTIFIED BY 'password';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
