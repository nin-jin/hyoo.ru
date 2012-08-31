$jam.Component
(   'wc:aspect'
,   function( nodeRoot ){
        return new function( ){
            var update= function( ){
                nodeRoot= $jam.Node( nodeRoot )
                var ratio= parseFloat( nodeRoot.attr( 'wc:aspect_ratio' ) )
                nodeRoot.$.style.height= Math.min( nodeRoot.width() * ratio, window.innerHeight * .9 ) + 'px'
            }
            update()
            window.addEventListener( 'resize', update )
            this.destroy= function( ){
                window.removeEventListener( 'resize', update )
            }
        }
    }
)
