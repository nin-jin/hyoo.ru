$jam.define
(   '$jam.Thread'
,   $jam.Lazy( function(){
    
        var poolNode= $jam.Lazy( function(){
            var body= document.getElementsByTagName( 'body' )[ 0 ]
            var pool= document.createElement( 'wc_Thread:pool' )
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
                    var starter= document.createElement( 'button' )
                    poolNode().appendChild( starter )
                }
                
                starter.onclick= function( ev ){
                    ( ev || window.event ).cancelBubble= true
                    res= proc.apply( self, args )
                }
                starter.click()
    
                free.push( starter )
                return res
            }
        }
    
    })
)
