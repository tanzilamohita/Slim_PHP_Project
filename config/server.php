<?php
session_start();
define('THEME','default');

R::setup('mysql:host=localhost;dbname=testDB','root','');
R::exec("SET SESSION time_zone = '+6:00'");
//R::debug(true);
?>
