<?php

class so_permit_list_db
implements \ArrayAccess
{
    use so_meta;
    use so_database;
    
    var $name= 'so_permit_list';
    var $mainField= 'id';
    var $fields= array( 'so_permit_pattern', 'so_permit_action' );
    var $indexes= array(
        'so_permit_unique' => array(
            'type' => 'unique',
            'columns' => array( 'so_permit_pattern', 'so_permit_action' ),
        ),
    );
    
}