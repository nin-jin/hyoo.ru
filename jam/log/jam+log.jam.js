$jam.define
(   '$jam.log'
,   new function(){
        var console= window.console
        if( !console || !console.log ){
            return function(){
                alert( [].slice.call( arguments ) )
            }
        }
        return function(){
            Function.prototype.apply.call( console.log, console, arguments )
        }
    }
)
