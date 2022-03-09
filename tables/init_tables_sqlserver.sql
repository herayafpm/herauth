-- sqlserver
CREATE TABLE [herauth_group] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [name] [varchar](255) NULL,
	[description] [text] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

-- sqlserver
CREATE TABLE [herauth_account_model] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [model_name] [varchar](255) NULL,
    [model] [varchar](255) NULL,
    [jenis] [varchar](255) NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

-- sqlserver
CREATE TABLE [herauth_account] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [username] [varchar](255) NULL,
    [password] [varchar](255) NULL,
    [model_name] [varchar](255) NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_user] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [id_account] [varchar](255) NULL,
    [name] [varchar](255) NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

-- sqlserver
CREATE TABLE [herauth_account_group] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [account_id] [int] NULL,
    [group_id] [int] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

-- sqlserver
CREATE TABLE [herauth_permission] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [name] [varchar](255) NULL,
	[description] [text] NULL,
	[must_login] [int] DEFAULT 1,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);


-- sqlserver
CREATE TABLE [herauth_group_permission] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [group_id] [int] NULL,
    [permission_id] [int] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_account_permission] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [account_id] [int] NULL,
    [permission_id] [int] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_request_log] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [username] [varchar](255) NULL,
    [client] [text] NULL,
    [path] [varchar](255) NULL,
    [method] [varchar](255) NULL,
    [ip] [varchar](255) NULL,
    [user_agent] [text] NULL,
    [status_code] [int] NULL,
    [status_message] [text] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_client] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [client_key] [varchar](255) NULL,
    [name] [varchar](255) NULL,
    [expired] [datetime] NULL,
    [hit_limit] [int] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_client_whitelist] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [client_id] [int] NULL,
    [whitelist_name] [varchar](255) NULL,
    [whitelist_type] [varchar](255) NULL,
    [whitelist_key] [varchar](255) NULL,
    [created_at] [datetime] DEFAULT NOW(),
	[updated_at] [datetime] DEFAULT NOW(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_client_permission] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [client_id] [int] NULL,
    [permission_id] [int] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_notifications] (
    [notif_id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [account_id] [int] NULL,
    [notif_judul] [TEXT] NULL,
    [notif_isi] [TEXT] NULL,
    [notif_url] [varchar](255) NULL,
    [notif_read] [int] DEFAULT 0,
    [notif_app] [varchar](255) NULL,
    [notif_created_at] [datetime] DEFAULT GETDATE(),
	[notif_updated_at] [datetime] DEFAULT GETDATE(),
	[notif_deleted_at] [datetime] NULL
);

CREATE TABLE [herauth_database_log] (
    [id] [int] PRIMARY KEY IDENTITY(1,1) NOT NULL,
    [username] [varchar](255) NULL,
    [client] [text] NULL,
    [table_name] [varchar](255) NULL,
    [table_id] [int] NULL,
    [jenis] [varchar](255) NULL,
    [data_before] [TEXT] NULL,
    [data_after] [TEXT] NULL,
    [result] [int] NULL,
    [log_message] [TEXT] NULL,
    [created_at] [datetime] DEFAULT GETDATE(),
	[updated_at] [datetime] DEFAULT GETDATE(),
	[deleted_at] [datetime] NULL
);

