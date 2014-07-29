<?php
Configure::setCustom('admin_login_view', 'freelanceSuite');
Configure::setCustom('username', 'yuzhihua');
Configure::setCustom('userpass', 'yuzhihua');
Configure::setRouter('controller', 'article');
Configure::setCustom('func_admin_article', true);
Configure::setCustom('func_admin_photo', false);
Configure::setCustom('func_admin_category', true);
Configure::setCustom('func_admin_category_add', true);
Configure::setCustom('func_admin_category_delete', false);
Configure::setCustom('func_admin_article_add', true);
Configure::setCustom('func_admin_article_delete', true);
Configure::setCustom('func_admin_basic', false);

Configure::setCustom('func_admin_menus', array('basic', 'article', 'category'));