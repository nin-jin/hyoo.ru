$lib.googl= function( url, callback ){
    $lib.jsonlib.fetch
    (   {   url: 'https://www.googleapis.com/urlshortener/v1/url'
        ,   header: 'Content-Type: application/json'
        ,   data: JSON.stringify({ longUrl: url })
        }
    ,   function( response ){
            var result= null
            try {
                result= JSON.parse( result.content ).id
                if( typeof result != 'string' )
                    result= null
            } catch( e ){
              result = null;
            }
            callback( result );
        }
    )
}
