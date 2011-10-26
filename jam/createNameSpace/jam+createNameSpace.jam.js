with( $jam$ )
$define( '$createNameSpace', function( name ){
    var ns= {}
    $define.call( $glob(), name, ns )
    $define.call( ns, name, ns )
    return ns
})
