$jam.define
(   '$jam.Thread'
,   $jam.Lazy( function(){
    
        var poolNode= $jam.Lazy( function(){
            var body= $jam.doc().getElementsByTagName( 'body' )[ 0 ]
            var pool= $jam.doc().createElement( 'wc:Thread:pool' )
            pool.style.display= 'none'
            body.insertBefore( pool, body.firstChild )
            return $jam.Value( pool )
        })
            
        var free= []
    
        return function( proc ){
            return function( ){
                var res
                var self= this
                var args= arguments
    
                var starter= free.pop()
                if( !starter ){
                    var starter= $jam.doc().createElement( 'button' )
                    poolNode().appendChild( starter )
                }
                
                starter.onclick= function( ev ){
                    ( ev || $jam.glob().event ).cancelBubble= true
                    res= proc.apply( self, args )
                }
                starter.click()
    
                free.push( starter )
                return res
            }
        }
    
    })
)
