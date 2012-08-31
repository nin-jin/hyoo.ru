$jam.keyCode=
new function( ){

    var codes= []
    
    var keyCode= function( code ){
        return codes[ code ] || 'unknown'
    }

    keyCode.ctrlPause= 3
    keyCode.backSpace= 8
    keyCode.tab= 9
    keyCode.enter= 13
    keyCode.shift= 16
    keyCode.ctrl= 17
    keyCode.alt= 18
    keyCode.pause= 19
    keyCode.capsLock= 20
    keyCode.escape= 27
    keyCode.space= 32
    keyCode.pageUp= 33
    keyCode.pageDown= 34
    keyCode.end= 35
    keyCode.home= 36
    keyCode.left= 37
    keyCode.up= 38
    keyCode.right= 39
    keyCode.down= 40
    
    keyCode.insert= 45
    keyCode.delete= 46
    
    for( var code= 48; code <= 57; ++code ){
        keyCode[ String.fromCharCode( code ).toLowerCase() ]= code
    }

    for( var code= 65; code <= 90; ++code ){
        keyCode[ String.fromCharCode( code ).toLowerCase() ]= code
    }

    keyCode.win= 91
    keyCode.context= 93

    for( var numb= 1; numb <= 12; ++numb ){
        keyCode[ 'f' + numb ]= 111 + numb
    }
    
    keyCode.numLock= 144
    keyCode.scrollLock= 145
    
    keyCode.semicolon= 186
    keyCode.plus= 187
    keyCode.minus= 189
    keyCode.comma= 188
    keyCode.period= 190
    keyCode.slash= 191
    keyCode.tilde= 192
    
    keyCode.openBracket= 219
    keyCode.backSlash= 220
    keyCode.closeBracket= 221
    keyCode.apostrophe= 222
    keyCode.backSlashLeft= 226
    
    for( var name in keyCode ){
        if( !keyCode.hasOwnProperty( name ) ) continue
        codes[ keyCode[ name ] ]= name
    }
    
    return keyCode
    
}