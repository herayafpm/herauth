INSERT INTO herauth_group (name,description) VALUES 
-- 1
('superadmin','Super Admin'),
-- 2
('admin','Admin'),
-- 3
('herauth', 'Authorize Herauth App Access');

INSERT INTO herauth_account_model (model_name,model,jenis) VALUES 
("pengguna","Raydragneel/Herauth/Models/HerauthUserModel","ci-4");

INSERT INTO herauth_account (username,password,model_name) VALUES 
-- superadmin (1)
('superadmin','$2y$10$T3VGpRktNJrJzVzwTsT0Z.ixlxcaolAbMpQaAbUKrdaxknBi8IRJq','pengguna'),
-- admin (2)
('admin','$2y$10$T3VGpRktNJrJzVzwTsT0Z.ixlxcaolAbMpQaAbUKrdaxknBi8IRJq','pengguna');

INSERT INTO herauth_user (id_account,name) VALUES 
-- superadmin (1)
(1,'Super Admin'),
-- admin (2)
(2,'Admin');

INSERT INTO herauth_account_group (account_id,group_id) VALUES 
-- superadmin (superadmin,admin)
(1,1), 
(1,2), 
(1,3),
-- admin (admin)
(2,2);

INSERT INTO herauth_permission (name,description,must_login) VALUES 
-- (1-2)
('auth.login','can login',0),
('user_account.get_profil','Get Data Profil',1),
-- Admin (3-6)
('account.view_index','View Index account',1),
('account.view_add','View add account',1),
('account.view_edit','View edit account',1),
('account.view_group','View account Group',1),
-- account API (7-14)
('account.post_datatable','Api post account datatable',1),
('account.post_add','Api post add account',1),
('account.post_edit','Api post edit account',1),
('account.post_delete','Api post delete account',1),
('account.post_purge','Api post purge account',1),
('account.post_restore','Api post restore account',1),
('account.get_groups','Api get account groups',1),
('account.post_save_group','Api post save account group',1),
-- Client (15 - 20)
('client.view_index','View Index client',1),
('client.view_add','View add client',1),
('client.view_edit','View edit client',1),
('client.view_whitelists','View whitelists client',1),
('client.view_permissions','View permissions client',1),
('client.view_group','View client Group',1),
-- Client API (21 - 30)
('client.post_datatable','Api post client datatable',1),
('client.post_add','Api post add client',1),
('client.post_edit','Api post edit client',1),
('client.post_delete','Api post delete client',1),
('client.post_purge','Api post purge client',1),
('client.post_restore','Api post restore client',1),
('client.post_regenerate_key','Api post regenerate key client',1),
('client.get_permissions','Api get permissions group',1),
('client.post_save_permissions','Api post save client permissions group',1),
('client.save_whitelists','Api post save client whitelists',1),
-- Group (31 - 35)
('group.view_index','View Index group',1),
('group.view_add','View add group',1),
('group.view_edit','View edit group',1),
('group.view_accounts','View accounts group',1),
('group.view_permissions','View permissions group',1),
-- Group API (36 - 47)
('group.post_datatable','Api post group datatable',1),
('group.post_add','Api post add group',1),
('group.post_edit','Api post edit group',1),
('group.post_delete','Api post delete group',1),
('group.post_purge','Api post purge group',1),
('group.post_restore','Api post restore group',1),
('group.get_accounts','Api get accounts group',1),
('group.post_delete_account_group','Api post delete accounts group',1),
('group.post_add_account_group','Api post add accounts group',1),
('group.get_permissions','Api get permissions',1),
('group.post_save_permissions','Api post save group permissions',1),
('group.get_groups','Api post groups',1),
-- Permission (48 - 50)
('permission.view_index','View Index permission',1),
('permission.view_add','View add permission',1),
('permission.view_edit','View edit permission',1),
-- Permission API (51 - 57)
('permission.get_permissions','Api get permissions',1),
('permission.post_datatable','Api post permission datatable',1),
('permission.post_add','Api post add permission',1),
('permission.post_edit','Api post edit permission',1),
('permission.post_delete','Api post delete permission',1),
('permission.post_purge','Api post purge permission',1),
('permission.post_restore','Api post restore permission',1),
-- Request Log (58)
('request_log.view_index','View Index request_log',1),
-- Request Log API (59)
('request_log.post_datatable','Api post Request log datatable',1),
-- User Account App (60)
('user_account.get_notifications','Can Get Notifications',1);

INSERT INTO herauth_group_permission (group_id,permission_id) VALUES 
(1,1),
(1,2),
-- Admin
(1,3),
(1,4),
(1,5),
(1,6),
-- Admin API
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
-- Client
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20),
-- Client API
(1,21),
(1,22),
(1,23),
(1,24),
(1,25),
(1,26),
(1,27),
(1,28),
(1,29),
(1,30),
-- Group
(1,31),
(1,32),
(1,33),
(1,34),
(1,35),
-- Group API
(1,36),
(1,37),
(1,38),
(1,39),
(1,40),
(1,41),
(1,42),
(1,43),
(1,44),
(1,45),
(1,46),
(1,47),
-- Permission
(1,48),
(1,49),
(1,50),
-- Permission API
(1,51),
(1,52),
(1,53),
(1,54),
(1,55),
(1,56),
(1,57),
-- Request Log
(1,58),
-- Request Log API
(1,59),
-- User Account
(1,60);

INSERT INTO herauth_client (client_key,name,expired,hit_limit) VALUES 
('384d8a1a-8cde-4a22-803a-5a8415b0ffd8','Testing',null,null);

INSERT INTO herauth_client_whitelist (client_id,whitelist_name,whitelist_type,whitelist_key) VALUES 
(1,'IP Local','ip','127.0.0.1'),
(1,'Android','android','cc17c3991115kkb0kkk9c3c919c1eb9939kb2035;com.app.yourapp'),
(1,'IOS','ios','com.app.yourapp');


INSERT INTO herauth_client_permission (client_id,permission_id) VALUES 
(1,1),
(1,2),
-- Admin
(1,3),
(1,4),
(1,5),
(1,6),
-- Admin API
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
-- Client
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20),
-- Client API
(1,21),
(1,22),
(1,23),
(1,24),
(1,25),
(1,26),
(1,27),
(1,28),
(1,29),
(1,30),
-- Group
(1,31),
(1,32),
(1,33),
(1,34),
(1,35),
-- Group API
(1,36),
(1,37),
(1,38),
(1,39),
(1,40),
(1,41),
(1,42),
(1,43),
(1,44),
(1,45),
(1,46),
(1,47),
-- Permission
(1,48),
(1,49),
(1,50),
-- Permission API
(1,51),
(1,52),
(1,53),
(1,54),
(1,55),
(1,56),
(1,57),
-- Request Log
(1,58),
-- Request Log API
(1,59),
-- User Account
(1,60);