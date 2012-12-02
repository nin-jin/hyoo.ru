this.$jin_method= function( func ){
    if( typeof func !== 'function' )
        return func
    
    var method= function( a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z ){
        return func( this, a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z )
    }
    
    method.call= func
    
    return method
}
