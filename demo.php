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
//(new Stats($client))->update("ProfilePage","AccessTime",1);
//var_dump((new Stats($client))->get("ProfilePage","AccessTime"));

//test auto complete

(new AutoComplete($client))->addOrUpdateContact("hjyang","jim");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jima");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jimb");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jimc");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jimd");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jime");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jime");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jse");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jfme");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jide");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jige");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jame");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jide");
(new AutoComplete($client))->addOrUpdateContact("hjyang","jide");
print_r((new AutoComplete($client))->fetchAutoCompleteList("hjyang","jim"));
print_r((new AutoComplete($client))->fetchAutoCompleteList("hjyang","j"));
print_r((new AutoComplete($client))->fetchAutoCompleteList("hjyang","ji"));



