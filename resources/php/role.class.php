<?php

require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/table.class.php");
require_once($_SERVER['DOCUMENT_ROOT'].URI."/resources/php/DB.class.php");

class Role extends Table {

    //Role Constants
    const ADMINISTRATOR = 1;
    const EDITOR = 2;
    const ASSOC_EDITOR = 3;
    const REVIEWER = 4;

    protected $table = "role";

}
