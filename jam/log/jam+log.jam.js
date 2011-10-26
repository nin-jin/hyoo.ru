with( $jam$ )
$define( '$log', new function(){
    var console= $glob().console
    if( !console || !console.log ){
        return function(){
            alert( [].slice.call( arguments ) )
        }
    }
    return function(){
        Function.prototype.apply.call( console.log, console, arguments )
    }
})
