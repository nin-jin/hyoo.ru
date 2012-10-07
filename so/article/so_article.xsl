<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_article ">
        <wc_spacer>
            <wc_top-tool>
                <wc_top-tool_pane>
                    <xsl:apply-templates select="." mode="so_article_permalink" />
                </wc_top-tool_pane>
            </wc_top-tool>
            <wc_paper>
                <wc_spacer>
                    <xsl:apply-templates select="." mode="so_article_head" />
                    <xsl:apply-templates select="." mode="so_article_body" />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_article " mode="so_article_permalink" />
    <xsl:template match=" so_article[ @so_uri ] " mode="so_article_permalink">
        <wc_top-tool_item>
            <wc_permalink title="Ссылка на эту запись">
                <a href="{ @so_uri }">#</a>
            </wc_permalink>
        </wc_top-tool_item>
    </xsl:template>
    
    <xsl:template match=" so_article " mode="so_article_head" />
    <xsl:template match=" so_article[ @so_article_name ] " mode="so_article_head">
        <h1>
            <form
                method="get"
                action="?"
                wc_form="wc_form"
                >
                <wc_field wc_field_name="article">
                    <wc_editor>
                        <xsl:value-of select=" @so_article_name " />
                    </wc_editor>
                </wc_field>
                <input
                    type="hidden"
                    name="by"
                    value="{ @so_article_author }"
                />
            </form>
        </h1>
    </xsl:template>
    
    <xsl:template match=" so_article " mode="so_article_body" />
    <xsl:template match=" so_article[ @so_article_gist ] " mode="so_article_body">
        <xsl:apply-templates select=" $so_uri_map[ @so_uri = current()/@so_article_gist ] " />
    </xsl:template>
    
</xsl:stylesheet>
