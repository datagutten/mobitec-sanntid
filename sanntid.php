#!/usr/bin/php
<?php

use datagutten\mobitec_sis\MobitecSanntid;

require 'vendor/autoload.php';
$config = require 'config.php';
//$mobitec = new MobitecSerial($config['serial_port']);
$sanntid = new MobitecSanntid($config);
while (true)
{
    $sanntid->departure_output($config['stop_id'], $config['destination']);
    sleep($config['refresh_time']);
}