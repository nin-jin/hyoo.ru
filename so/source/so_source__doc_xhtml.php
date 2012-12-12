<?php

class so_source__doc_xhtml
extends so_source
{

    function content_make( ){
        return so_dom::make( $this->file->content );
    }

}