<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:output
        method="html"
        omit-xml-declaration="yes"
        doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    />
    
    <xsl:variable
        name="so_page_stylesheet"
        select="/processing-instruction()[ name() = 'xml-stylesheet' ]"
    />
    
    <xsl:variable
        name="so_page_mode"
        select=" substring-before( substring-after( $so_page_stylesheet, '-mix/' ), '.xsl' ) "
    />
    
    <xsl:variable
        name="so_page_base"
        select=" substring-before( substring-after( $so_page_stylesheet, 'href=&quot;' ), concat( $so_page_mode, '.xsl' ) ) "
    />
    
    <xsl:template match=" so_page ">
        
        <html>
            <head>
                
                <title>
                    <xsl:value-of select=" @so_page_title " />
                </title>
                
                <meta http-equiv="content-type" content="text/html;charset=utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
                
                <xsl:apply-templates select=" . " mode="so_page_resources" />
                
            </head>
            <body>
                <wc_desktop>
                    <a href="?"><wc_logo>Gist!</wc_logo></a>
                    
                    <xsl:apply-templates select=" so_page_aside " mode="so_page_special" />
                    
                    <xsl:apply-templates select=" $so_uri_map[ @so_uri = current()/@so_page_uri ] " />
                    <xsl:apply-templates select=" * [ not( @so_uri ) ] " />
                    
                    <wc_footer>
                        <a href="https://github.com/nin-jin/PMS" title="Исходники этого сайта">Gist!</a>
                        <xsl:text> @ </xsl:text>
                        <a href="http://vrozetke.com/" title="Бесплатный Хостинг">Vrozetke.com</a>
                    </wc_footer>
                    
                </wc_desktop>
                
            </body>
        </html>
        
    </xsl:template>

    <xsl:template match=" head ">
        <xsl:apply-templates />
    </xsl:template>
    
    <xsl:template match=" body ">
        <wc_spacer>
            <wc_paper>
                <wc_spacer>
                    <xsl:apply-templates />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_page " mode="so_page_resources">
        <link href="{ $so_page_base }{ $so_page_mode }.css" rel="stylesheet" />
        <script src="{ $so_page_base }{ $so_page_mode }.js">//</script>
    </xsl:template>

    <xsl:template match=" so_page_aside " />
    <xsl:template match=" so_page_aside " mode="so_page_special">
        <wc_sidebar wc_sidebar_align="right">
            <wc_editor
                wc_editor_hlight="tags"
                >
                <xsl:text> </xsl:text>
            </wc_editor>
        </wc_sidebar>
    </xsl:template>
    
</xsl:stylesheet>
