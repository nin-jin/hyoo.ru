$jam.Value= function( val ){
    var value= function(){
        return val
    }
    value.toString= function(){
        return '$jam.Value: ' + String( val )
    }
    return value
}
