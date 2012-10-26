<?php

trait so_database
#implements \ArrayAccess
{
    use so_registry;
    static $id_prop= 'file';
    
    #var $name= 'table';
    #var $mainField= 'uri';
    #var $fields= array();
    #var $indexes= array();
    
    var $file_value;
    function file_make( ){
        return false;
    }
    function file_store( $data ){
        return so_file::make( $data );
    }
    
    var $exists_value;
    function exists_make( ){
        $file= $this->file;
        return $file ? $file->exists : true;
    }
    
    var $dsn_value;
    function dsn_make( ){
        $file= $this->file;
        
        if( !$file )
            return "sqlite::memory:";
        
        $file->parent->exists= true;
        
        return "sqlite:{$file}";
    }
    
    var $emptyRecord_value;
    function emptyRecord_make( ){
        $row= array( $this->mainField => null );
        foreach( $this->fields as $field )
            $row[ $field ]= null;
        return $row;
    }
    
    var $connection_value;
    function connection_make( ){
        $connection= new \PDO( $this->dsn );
        $connection->query( "create table if not exists [{$this->name}] ( [{$this->mainField}] primary key )" );
        
        foreach( $this->fields as $field )
            $connection->query( "alter table [{$this->name}] add column [{$field}]" );
        
        foreach( $this->indexes as $key => $index ):
            $type= $index[ 'type' ];
            $columns= '[' . implode( '], [', $index[ 'columns' ] ) . ']';
            $connection->query( "create [{$type}] index if not exists [{$key}] on [{$this->name}] ( [$columns] )" );
        endforeach;
        
        return $connection;
    }
    
    function request( ){
        $connection= $this->connection;
        
        $i= 0;
        $query= '';
        foreach( func_get_args() as $val )
            $query.= ( ++$i % 2 ) ? $val : $connection->quote( $val );
        
        $result= $connection->query( $query );
        
        if( $result === false ):
            $error= $connection->errorInfo()[ 2 ];
            throw new \Exception( "[{$error}] by executing [{$query}]" );
        endif;
        
        return $result;
    }
    
    var $dump_value;
    function dump_make( ){
        if( !$this->exists )
            return array();
        
        $list= $this->request( "select * from [{$this->name}] order by _ROWID_ desc" )->fetchAll( \PDO::FETCH_ASSOC );
        $hash= array();
        
        foreach( $list as $record )
            $hash[ $record[ $this->mainField ] ]= $record;
        
        return $hash;
    }
    
    var $head_value;
    function head_make( ){
        if( !$this->exists )
            return $this->emptyRecord;
        
        $result= $this->request( "select * from [{$this->name}] limit 1" )->fetch( \PDO::FETCH_ASSOC );
        
        if( $result === false )
            return null;
        
        return $result;
    }
    
    var $tail_value;
    function tail_make( ){
        if( !$this->exists )
            return $this->emptyRecord;
        
        $result= $this->request( "select * from [{$this->name}] order by _ROWID_ desc limit 1" )->fetch( \PDO::FETCH_ASSOC );
        
        if( $result === false )
            return null;
        
        return $result;
    }
    
    function begin( $name= null ){
        $this->request( $name ? "savepoint [{$name}]" : "begin transaction" );
        return $this;
    }
    
    function end( $name= null ){
        $this->request( $name ? "release savepoint [{$name}]" : "commit transaction" );
        return $this;
    }
    
    function rollback( $name= null ){
        $this->request( $name ? "rollback transaction to savepoint [{$name}]" : "rollback transaction" );
        return $this;
    }
    
    function offsetSet( $key, $data ){
        if( $key ):
            $data= array( $this->mainField => $key ) + $data;
        endif;
        
        $columns= '[' . implode( '], [', array_keys( $data ) ) . ']';
        $values= '"' . implode( '", "', array_values( $data ) ) . '"';
        $this->request( "insert or replace into [{$this->name}] ( {$columns} ) values ( {$values} )" );
        return $this;
    }
    
    function offsetExists( $id ){
        if( !$this->exists )
            return false;
        
        return (boolean) $this[ $id ];
    }
    
    function offsetGet( $id ){
        if( !$this->exists )
            return $this->emptyRecord;
        
        $result= $this->request( "select * from [{$this->name}] where [{$this->mainField}] = ", $id )->fetch( \PDO::FETCH_ASSOC );
        
        if( $result === false )
            return null;
        
        return $result;
    }
    
    function offsetUnset( $id ){
        if( !$this->exists )
            return $this;
        
        return $this->request( "delete from [{$this->name}] where [{$this->mainField}] = ", $id );
    }

}