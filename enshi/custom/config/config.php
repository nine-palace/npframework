<?php
// Configure::setDatasource('host', 'bdm-015.hichina.com');
// Configure::setDatasource('user', 'bdm0150588');
// Configure::setDatasource('pwd', 'h0m6z6u5t3');
Configure::setDatasource('dbName', 'enshi');
Configure::setCustom('system_site_name', '恩施同乡在线');
Configure::setCustom('types', array('news' => '新闻', 'introduction' => '介绍', 'photo' => '图片', 'product' => '产品'));
Configure::setCustom('username', 'enshiadmin');
Configure::setCustom('userpass', 'enshipassword');
Configure::$autoAssignLanguage = false;
?>