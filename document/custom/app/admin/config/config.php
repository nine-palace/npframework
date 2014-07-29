<?php
Configure::setCustom('admin_login_view', 'freelanceSuite');
Configure::setRouter('controller', 'article');

Configure::setUserPurview('func_admin_add@article');
Configure::setUserPurview('func_admin_delete@article');
Configure::setUserPurview('func_admin_modify@article');
Configure::setUserPurview('func_admin_select@article');
Configure::setUserPurview('func_admin_add@category');
Configure::setUserPurview('func_admin_delete@category');
Configure::setUserPurview('func_admin_modify@category');
Configure::setUserPurview('func_admin_select@category');
Configure::setUserPurview('func_admin_add@account');
Configure::setUserPurview('func_admin_delete@account');
Configure::setUserPurview('func_admin_modify@account');
Configure::setUserPurview('func_admin_select@account');

Configure::setCustom('func_admin_menus', array('article', 'category', 'account'));
Configure::setCustom('default_password', '123456');