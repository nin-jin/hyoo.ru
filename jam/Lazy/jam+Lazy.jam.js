$jam.define
(   '$jam.Lazy'
,   function( gen ){
        var proc= function(){
            proc= gen.call( this )
            return proc.apply( this, arguments )
        }
        var lazy= function(){
            return proc.apply( this, arguments )
        }
        lazy.gen= $jam.Value( gen )
        return lazy
    }
)
