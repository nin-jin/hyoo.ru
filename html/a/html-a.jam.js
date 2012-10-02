$jam.Component
(   'a'
,   function( el ){
        var isTarget= ( el.href == document.location.href )
        $jam.Node( el ).state( 'target', isTarget )
    }
)
