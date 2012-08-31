this.$lang=
function( name ){
    return $lang[ name ] || $lang.text
}

$lang.text= $jam.htmlEscape