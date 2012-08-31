$lang.Wrapper=
function( name ){
    var prefix= '<' + name + '>'
    var postfix= '</' + name + '>'
    return function( content ){
        return prefix + content + postfix
    }
}
