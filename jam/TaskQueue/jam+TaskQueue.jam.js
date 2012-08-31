$jam.define
(   '$jam.TaskQueue'
,   $jam.Class( function( klass, proto ){
        
        proto.constructor=
        function( ){
            this.$= {}
            this.$.queue= []
            this.$.clock=
            $jam.Clock()
            .handler( $jam.Obj( this ).method( 'run' ) )
            return this
        }
        
        proto.latency=
        $jam.Poly
        (   function( ){
                return this.$.clock.latency()
            }
        ,   function( val ){
                this.$.clock.latency( val )
                return this
            }
        )
        
        proto.active=
        $jam.Poly
        (   function( ){
                return this.$.clock.active()
            }
        ,   function( val ){
                this.$.clock.active( val )
                return this
            }
        )
        
        proto.run=
        function( ){
            var proc= this.$.queue.shift()
            proc()
            if( !this.$.queue.length ) this.active( false )
            return this
        }
        
        proto.add=
        function( task ){
            this.$.queue.push( task )
            this.active( true )
            return this
        }
        
    })
)
