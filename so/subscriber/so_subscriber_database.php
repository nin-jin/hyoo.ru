<?php

class so_subscriber_database
implements \ArrayAccess
{
    use so_meta;
    use so_database;
    
    var $name= 'so_subscriber_database';
    var $mainField= 'so_subscriber_uri';
    var $fields= array( 'event' );
    var $indexes= array();
}