var DISQUS= DISQUS || new function( ){
    this.settings= {}
    this.extend= function( target, source ){
        for( var key in source ) target[ key ]= source[ key ]
    }
}

with( $jam$ )
with( $wc$ )
$Component( 'wc:disqus', function( nodeRoot ){
    nodeRoot= $Node( nodeRoot )
    var script= $Node.Element( 'script' ).attr( 'src', '//nin-jin.disqus.com/thread.js?url=' + $uriEscape( '//' + document.location.host + document.location.pathname ) )
    script.listen( 'load', function( ){
        console.log( DISQUS.jsonData )

        var thread= nodeRoot.html( $lang_md( '  0 *a* b' ) )
        
        var postList= DISQUS.jsonData.posts
        var userList= DISQUS.jsonData.users
        for( var id in postList ){
            var post= postList[ id ]
            var user= userList[ post.user_key ]
            var message= $Node.Element( 'wc:disqus_message' )
            $Node.Element( 'wc:disqus_author' ).text( user.display_name ).parent( message )
            $Node.Element( 'wc:disqus_content' ).text( post.raw_message ).parent( message )
            message.parent( nodeRoot )
        }
    } )
    nodeRoot.head( script )
} )