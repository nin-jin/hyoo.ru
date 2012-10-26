<?php

class so_gist_database
implements \ArrayAccess
{
    use so_meta;
    use so_database;
    
    var $name= 'so_gist_database';
    var $mainField= 'so_gist_uri';
    var $fields= array();
    var $indexes= array();
    
}