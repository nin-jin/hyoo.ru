<?php

class so_storage_index
implements \ArrayAccess
{
    use so_meta;
    use so_database;
    
    var $name= 'so_storage_list';
    var $mainField= 'so_storage_version';
    var $fields= array();
    var $indexes= array();
    
}