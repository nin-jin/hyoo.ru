<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:template match=" so:page ">
        
        <html>
            <head>
                
                <title xml:space="preserve" >
                    <xsl:value-of select=" so:page_title " />
                </title>
                
                <meta http-equiv="content-type" content="text/html;charset=utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
                
                <xsl:apply-templates select=" so:page_stylesheet " mode="so:page_special" />
                
            </head>
            <body>
                <wc:desktop>
                    
                    <a href="?gist=Gist!"><wc:logo>Gist!</wc:logo></a>
                    
                    <xsl:apply-templates select=" so:page_aside " mode="so:page_special" />
                    
                    <xsl:apply-templates select=" node()[ name() ] " />
                    
                    <xsl:apply-templates select=" so:page_script " mode="so:page_special" />
                    
                </wc:desktop>
            </body>
        </html>
        
    </xsl:template>

    <xsl:template match=" so:page_stylesheet " />
    <xsl:template match=" so:page_stylesheet " mode="so:page_special">
        <link href="{ . }" rel="stylesheet" />
    </xsl:template>

    <xsl:template match=" so:page_script " />
    <xsl:template match=" so:page_script " mode="so:page_special">
        <script src="{ . }">//</script>
    </xsl:template>

    <xsl:template match=" so:page_title " />
    
    <xsl:template match=" so:page_aside " />
    <xsl:template match=" so:page_aside " mode="so:page_special">
        <wc:sidebar wc:sidebar_align="right">
            <wc:editor
                wc:editor_hlight="tags"
                >
                <xsl:text> </xsl:text>
            </wc:editor>
        </wc:sidebar>
    </xsl:template>
    
</xsl:stylesheet>
