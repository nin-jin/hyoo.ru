$jam.Component
(   'a'
,   function( el ){
        var isTarget= ( el.href == $jam.doc().location.href )
        $jam.Node( el ).state( 'target', isTarget )
    }
)
