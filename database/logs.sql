CREATE TABLE `logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(100) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `view_date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_id` varchar(100) NOT NULL,
  `view_count` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `logs_image_id_IDX` (`image_id`,`user_agent`,`ip_address`) USING HASH
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;