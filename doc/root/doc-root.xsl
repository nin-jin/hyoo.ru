<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:template match=" doc_pack ">
    <wc_vmenu_branch>
        <xsl:apply-templates />
    </wc_vmenu_branch>
</xsl:template>

<xsl:template match=" doc_file ">
    <a href="{ doc_link }" class=" reset=true ">
        <wc_vmenu_leaf>
            <xsl:value-of select=" doc_title " />
        </wc_vmenu_leaf>
    </a>
</xsl:template>

<xsl:variable name="wc_root-uri">
    <xsl:value-of select=" substring-before( substring-after( /processing-instruction()[ name() = 'xml-stylesheet' ], 'href=&quot;' ), '&quot;' ) " />
    <xsl:text>/../..</xsl:text>
</xsl:variable>
    
<xsl:template match=" /doc_root ">
    <html>
        <head>
        
            <title>
                <xsl:value-of select=" . // h1[ 1 ] " />
            </title>
            <meta http-equiv="content-type" content="text/html;charset=utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
            <link href="{$wc_root-uri}/-mix+doc/index.css" rel="stylesheet" />
            <script src="{$wc_root-uri}/-mix+doc/index.js?">//</script>

        </head>
        <body>
            <xsl:copy>
                <xsl:apply-templates select=" @* " />
                <wc_desktop>
                    <wc_sidebar wc_sidebar_align="left">
                        <wc_vmenu_root>
                            <xsl:apply-templates select=" document( '../-mix/index.doc.xml', / ) / * / node() " />
                        </wc_vmenu_root>
                    </wc_sidebar>
                
                    <wc_spacer>
                        <wc_paper>
                            <wc_spacer>
                            
                                <xsl:apply-templates />
                                
                            </wc_spacer>
                            <!--<wc_spacer>-->
                                <!--<wc_disqus>
                                    ...
                                </wc_disqus>-->
                                <!--<div id="disqus_thread">-->
                                <!--    <script>-->
                                <!--        disqus_developer= 1-->
                                <!--        disqus_url= '//' + document.location.host + document.location.pathname-->
                                <!--    </script>-->
                                <!--    <script src="http://nin-jin.disqus.com/embed.js" async="async" defer="defer">//</script>-->
                                <!--</div>-->
                            <!--</wc_spacer>-->
                        </wc_paper>
                    </wc_spacer>
                    
                    <wc_footer>
                        License: <a href="http://ru.wikipedia.org/wiki/%D0%9E%D0%B1%D1%89%D0%B5%D1%81%D1%82%D0%B2%D0%B5%D0%BD%D0%BD%D0%BE%D0%B5_%D0%B4%D0%BE%D1%81%D1%82%D0%BE%D1%8F%D0%BD%D0%B8%D0%B5">Public Domain</a>
                    </wc_footer>

                </wc_desktop>
            </xsl:copy>
            
        </body>
    </html>
</xsl:template>

</xsl:stylesheet>
