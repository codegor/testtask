CREATE TABLE test.logs (
	id BIGINT auto_increment NULL,
	ip_address varchar(100) NOT NULL,
	user_agent varchar(255) NOT NULL,
	view_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NULL,
	image_id VARCHAR(100) NOT NULL,
	view_count BIGINT UNSIGNED NOT NULL,
	CONSTRAINT logs_PK PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

