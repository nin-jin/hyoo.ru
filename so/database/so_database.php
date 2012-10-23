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
        return ':memory:';
    }
    function file_store( $data ){
        so_file::ensure( $data );
        $data->parent->exists= true;
        return $data;
    }
    
    var $connection_value;
    function connection_make(){
        $connection= new \PDO( 'sqlite:' . $this->file );
        
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
        $list= $this->request( "select * from [{$this->name}]" )->fetchAll( \PDO::FETCH_ASSOC );
        $hash= array();
        
        foreach( $list as $row )
            $hash[ $row[ $this->mainField ] ]= $row;
        
        return $hash;
    }
    
    var $head_value;
    function head_make( ){
        $result= $this->request( "select * from [{$this->name}] limit 1" )->fetch( \PDO::FETCH_ASSOC );
        
        if( $result === false )
            return null;
        
        return $result;
    }
    
    var $tail_value;
    function tail_make( ){
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
        return (boolean) $this[ $id ];
    }
    
    function offsetGet( $id ){
        $result= $this->request( "select * from [{$this->name}] where [{$this->mainField}] = ", $id )->fetch( \PDO::FETCH_ASSOC );
        
        if( $result === false )
            return null;
        
        return $result;
    }
    
    function offsetUnset( $id ){
        return $this->request( "delete from [{$this->name}] where [{$this->mainField}] = ", $id );
    }

}