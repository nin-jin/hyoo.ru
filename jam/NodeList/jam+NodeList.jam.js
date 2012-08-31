$jam.define
(   '$jam.NodeList'
,   $jam.Class( function( klass, proto ){
        
        proto.get=
        function( index ){
            var node= this.$[ index ]
            return $jam.Node( node )
        }
        
        proto.length=
        function( ){
            return Number( this.$.length )
        }
        
        proto.head=
        function( ){
            return this.get( 0 )
        }
        
        proto.tail=
        function( ){
            return this.get( this.length() - 1 )
        }
        
    })
)
