<?php

class so_xstyle_compiler
{
    use so_singleton;

    function makeInstance( ){
        return so_xstyle::make()->pathXSL( __DIR__ . '/so_xstyle_compiler/so_xstyle_xs2xsl.xsl' );
    }

}
