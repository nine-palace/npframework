<?php
Configure::setDatasource('dbName', 'hotel');
Configure::custom('websocket_host', '192.168.1.100');
Configure::custom('websocket_port', '8080');
Configure::custom('fields', array('name' => '姓名','ctfId' => '身份证'));