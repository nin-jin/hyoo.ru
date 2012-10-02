$jam.Event=
$jam.Class( function( klass, proto ){

    proto.constructor=
    $jam.Poly
    (   function( ){
            this.$= document.createEvent( 'Event' )
            this.$.initEvent( '', true, true )
            return this
        }
    ,   function( event ){
            this.$= event
            return this
        }
    )
    
    proto.type=
    $jam.Poly
    (   function( ){
            return this.$.type
        }
    ,   function( type ){
            this.$.initEvent( type, this.$.bubbles, this.$.cancelable )
            return this
        }
    )
        
    proto.data=
    $jam.Poly
    (   function( ){
            return this.$.extendedData
        }
    ,   function( data ){
            this.$.extendedData= data
            return this
        }
    )
        
    proto.keyMeta=
    $jam.Poly
    (   function( ){
            return Boolean( this.$.metaKey || this.$.ctrlKey )
        }
    )
    
    proto.keyShift=
    $jam.Poly
    (   function( ){
            return Boolean( this.$.shiftKey )
        }
    )
    
    proto.keyAlt=
    $jam.Poly
    (   function( ){
            return Boolean( this.$.altKey )
        }
    )
    
    proto.keyAccel=
    $jam.Poly
    (   function( ){
            return this.keyMeta() || this.keyShift() || this.keyAlt()
        }
    )
    
    proto.keyCode=
    $jam.Poly
    (   function( ){
            var code= this.$.keyCode
            var keyCode= new Number( code )
            keyCode[ $jam.keyCode( code ) ]= code
            return keyCode
        }
    )
    
    proto.button=
    function( ){
        return this.$.button
    }
    
    proto.target=
    function( ){
        return this.$.target
    }
    
    proto.wheel=
    $jam.Poly
    (   function( ){
            if( this.$.wheelDelta ) return - this.$.wheelDelta / 120 
            return this.$.detail / 4
        }
    ,   function( val ){
            this.$.wheelDelta= - val * 120
            return this
        }
    )
    
    proto.defaultBehavior=
    $jam.Poly
    (   function( ){
            return Boolean( this.$.defaultPrevented )
        }
    ,   function( val ){
            if( val ) this.$.returnValue= !!val
            else this.$.preventDefault()
            return this
        }
    )
    
    proto.scream=
    function( node ){
        $jam.raw( node ).dispatchEvent( this.$ )
        return this
    }
    
})
