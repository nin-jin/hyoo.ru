$jam.define=
function( ){

    var Ghost= function(){}
    var global= this
    
    return function( key, value ){
        var keyList= key.split( '.' )
        
        var obj= global
        while( true ){
            key= keyList.shift()
            if( !keyList.length ) break
            
            var next= obj[ key ]
            if( next ){
                obj= next
            } else {
                obj= obj[ key ]= new Ghost
            }
        }
        
        if( key in obj ){
            var val= obj[ key ]
            if(!( val instanceof Ghost )) throw new Error( 'Redeclaration of [' + key + ']' )
            
            for( i in val ){
                if( !val.hasOwnProperty( i ) ) continue
                if( i in value ) throw new Error( 'Redeclaration of [' + i + ']' )
                value[ i ]= val[ i ]
            }
        }
        
        obj[ key ]= value
        
        return this
    }
    
}.apply( this )
