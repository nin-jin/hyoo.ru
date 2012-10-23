<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_article ">
        <wc_spacer>
            <wc_pop-tool>
                <wc_pop-tool_pane wc_pop-tool_edge="top">
                    <xsl:apply-templates select="." mode="so_article_author" />
                    <xsl:apply-templates select="." mode="so_article_permalink" />
                </wc_pop-tool_pane>
            </wc_pop-tool>
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
        <wc_pop-tool_item>
            <wc_permalink title="Постоянная ссылка на эту запись">
                <a href="{ @so_uri }">
                    <xsl:value-of select=" @so_article_name " />
                </a>
            </wc_permalink>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" so_article " mode="so_article_author" />
    <xsl:template match=" so_article[ @so_article_author ] " mode="so_article_author">
        <wc_pop-tool_item>
            <a
                href="{ @so_article_author }"
                title="Автор статьи"
                >
                <xsl:apply-templates
                    select=" $so_uri_map[ @so_uri = current()/@so_article_author ] "
                    mode="so_author_name"
                />
            </a>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" so_article " mode="so_article_head" />
    <xsl:template match=" so_article[ @so_article_name ] " mode="so_article_head">
        <form
            method="move"
            action="{ @so_uri }"
            wc_form="wc_form"
            >
            <wc_form_result>
                <xsl:comment/>
            </wc_form_result>
            <h1>
                <wc_field wc_field_name="name">
                    <wc_editor>
                        <xsl:value-of select=" @so_article_name " />
                    </wc_editor>
                </wc_field>
            </h1>
        </form>
    </xsl:template>
    
    <xsl:template match=" so_article " mode="so_article_body" />
    <xsl:template match=" so_article[ @so_article_gist ] " mode="so_article_body">
        <xsl:apply-templates select=" $so_uri_map[ @so_uri = current()/@so_article_gist ] " />
    </xsl:template>
    
</xsl:stylesheet>
