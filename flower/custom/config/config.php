<?php
Configure::setDatasource('dbName', 'flower');
Configure::setDatasource('pwd', '');
Configure::$defaultLanguage = 'Chinese';
Configure::$multiLanguage = false;
Configure::setCustom('func_admin_category_two', true);
Configure::setCustom('languages', array('chinese' => '简体中文', 'english' => 'English'));
Configure::setCustom('content_types', array('article' => 'article', 'introduction' => 'introduction', 'photo' => 'photo'));