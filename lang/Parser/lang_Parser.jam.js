$lang.Parser=
function( map ){
    if( !map[ '' ] ) map[ '' ]= $lang.text
    return $jam.Pipe
    (   $jam.Parser( map )
    ,   $jam.Concater()
    )
}
