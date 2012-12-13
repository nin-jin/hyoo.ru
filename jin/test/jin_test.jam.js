this.$jin_test= $jin_class( function( $jin_test, test ){
    
    test.func= null
    test.passed= null
    test.timeout= 0
    test.onDone= null
    
    test.asserts= null
    test.results= null
    test.errors= null
    
    test.init= function( test, code, onDone ){
        test.asserts= []
        test.results= []
        test.errors= []
        test.onDone= onDone || function(){}
        
        var complete= false
        
        test.callback( function( ){
            var func= new Function( 'test', code )
            if( !func ) return
            
            func( test )
            complete= true
        } ).call( )
        
        if( !complete ) test.passed= false
        
        if( test.timeout ){
            setTimeout( function( ){
                test.asserts.push( false )
                test.errors.push( new Error( 'timeout(' + test.timeout + ')' ) )
                test.done()
            }, test.timeout )
        } else {
            test.done()
        }
    }
    
    var AND= function( a, b ){ return a && b }
    test.done= function( test ){
        if( test.passed == null )
            test.passed= test.asserts.reduce( AND, true )
        
        test.onDone()
    }
    
    test.ok= function( test, value ){
        switch( arguments.length ){
            case 1:
                var passed= true
                break
            
            case 2:
                var passed= !!value
                break
            
            default:
                for( var i= 2; i < arguments.length; ++i ){
                    var passed= ( arguments[ i ] === arguments[ i - 1 ] )
                    if( !passed ) break;
                }
        }
        
        test.asserts.push( passed )
        test.results.push( [].slice.call( arguments, 1 ) )
        
        return test
    }
    
    test.not= function( test, value ){
        switch( arguments.length ){
            case 1:
                var passed= false
                break
            
            case 2:
                var passed= !value
                break
            
            default:
                for( var i= 2; i < arguments.length; ++i ){
                    var passed= ( arguments[ i ] !== arguments[ i - 1 ] )
                    if( !passed ) break;
                }
        }
        
        test.asserts.push( passed )
        test.results.push( [].slice.call( arguments, 1 ) )
        
        return test
    }
    
    test.callback= function( test, func ){
        return $jam.Thread( function( ){
            try {
                return func.apply( this, arguments )
            } catch( error ){
                test.errors.push( error )
                throw error
            }
        } )
    }
    
} )