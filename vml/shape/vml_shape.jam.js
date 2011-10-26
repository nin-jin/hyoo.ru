$jam$.$Component( 'vml:shape', function( node ){
    var height= node.style.height
    node.style.height= '100%'
    //node.style.height= height

    var width= node.style.width
    node.style.width= '100%'
    //node.style.width= width

    return null
} )
