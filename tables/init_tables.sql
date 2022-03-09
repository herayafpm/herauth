-- mysql
CREATE TABLE herauth_group (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nama varchar(255) NULL,
	deskripsi text NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_account_model (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    model_name varchar(255) NULL,
    model varchar(255) NULL,
    jenis varchar(255) NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_account (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username varchar(255) NULL,
    password varchar(255) NULL,
    model_name varchar(255) NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_account_group (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    account_id int NULL,
    group_id int NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);


-- mysql
CREATE TABLE herauth_permission (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nama varchar(255) NULL,
	deskripsi text NULL,
    must_login int DEFAULT 1,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_group_permission (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    group_id int NULL,
    permission_id int NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_account_permission (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    account_id int NULL,
    permission_id int NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);


-- mysql
CREATE TABLE herauth_request_log (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username varchar(255) NULL,
    client text NULL,
    path varchar(255) NULL,
    method varchar(255) NULL,
    ip varchar(255) NULL,
    user_agent text NULL,
    status_code int NULL,
    status_message text NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_client (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    client_key varchar(255) NULL,
    nama varchar(255) NULL,
    expired datetime NULL,
    hit_limit int NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_client_whitelist (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    client_id int NULL,
    whitelist_name varchar(255) NULL,
    whitelist_type varchar(255) NULL,
    whitelist_key varchar(255) NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_client_permission (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    client_id int NULL,
    permission_id int NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);


-- mysql
CREATE TABLE herauth_notifications (
    notif_id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    account_id int NULL,
    notif_judul TEXT NULL,
    notif_isi TEXT NULL,
    notif_url varchar(255) NULL,
    notif_read int DEFAULT 0,
    notif_app varchar(255) NULL,
    notif_created_at datetime DEFAULT NOW(),
	notif_updated_at datetime DEFAULT NOW(),
	notif_deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_database_log (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username varchar(255) NULL,
    client text NULL,
    table_name varchar(255) NULL,
    table_id int NULL,
    jenis varchar(255) NULL,
    data_before TEXT NULL,
    data_after TEXT NULL,
    log_message TEXT NULL,
    result int NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);

-- mysql
CREATE TABLE herauth_user (
    id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
    username varchar(255) NULL,
    name varchar(255) NULL,
    created_at datetime DEFAULT NOW(),
	updated_at datetime DEFAULT NOW(),
	deleted_at datetime NULL
);