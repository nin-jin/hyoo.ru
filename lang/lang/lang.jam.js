this.$lang=
function( name ){
    return $lang[ name ] || $lang.text
}

$lang.text= function( text ){
    return $jam.htmlEscape( text )
}

$lang.text.html2text= $jam.html2text
