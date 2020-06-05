<?php

$string = '{"user":"ssdf@sdaf.xde"}';
$a = json_decode($string);
var_dump($a["user"]);

