<?php

require_once "autoload.php";

$config = require "./src/Config.php";
$client = new Client($config);

//test log
//(new Log($client))->common("hjyang","test debug","debug");
//(new Log($client))->common("hjyang","test debug","debug");
//(new Log($client))->common("hjyang","test debug","debug");
//(new Log($client))->common("hjyang","test debug","debug");
//(new Log($client))->common("hjyang","test debug","debug");

//test counter
//(new Counter($client))->update("hits");
//var_dump((new Counter($client))->getCounter("hits",1));


//test stats
(new Stats($client))->update("ProfilePage","AccessTime",1);
var_dump((new Stats($client))->get("ProfilePage","AccessTime"));