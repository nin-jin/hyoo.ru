<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" hyoo_search " />
    <xsl:template match=" hyoo_search[ @hyoo_search_text ] ">
        <wc_spacer>
            <wc_paper>
                <wc_article>
                    <wc_article_title>
                        <h1 wc_reset="true">
                            <form
                                method="get"
                                action="?"
                                wc_form="wc_form"
                                >
                                <wc_field wc_field_name="search">
                                    <wc_editor>
                                        <xsl:value-of select=" @hyoo_search_text " />
                                    </wc_editor>
                                </wc_field>
                            </form>
                        </h1>
                    </wc_article_title>
                    <wc_article_content>
                        <xsl:apply-templates select=" . " mode="hyoo_search_result" />
                    </wc_article_content>
                </wc_article>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" hyoo_search " mode="hyoo_search_result">
        <iframe
            src="{ @hyoo_search_frame }"
            wc_reset="true"
            style="height:60em"
            sandbox="allow-scripts"
            >
        </iframe>
    </xsl:template>
    
</xsl:stylesheet>
