<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" mixer_article ">
        <wc_spacer>
            <wc_pop-tool>
                <wc_pop-tool_panel wc_pop-tool_edge="top">
                    <xsl:apply-templates select="." mode="mixer_article_author" />
                    <xsl:apply-templates select="." mode="mixer_article_permalink" />
                </wc_pop-tool_panel>
            </wc_pop-tool>
            <wc_paper>
                <wc_spacer>
                    <xsl:apply-templates select="." mode="mixer_article_head" />
                    <xsl:apply-templates select="." mode="mixer_article_body" />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_permalink" />
    <xsl:template match=" mixer_article[ @so_uri ] " mode="mixer_article_permalink">
        <wc_pop-tool_item>
            <a href="{ @so_uri }" title="Постоянная ссылка на эту запись">
                <xsl:value-of select=" @mixer_article_name " />
            </a>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_author" />
    <xsl:template match=" mixer_article[ @mixer_article_author ] " mode="mixer_article_author">
        <wc_pop-tool_item>
            <a
                href="{ @mixer_article_author }"
                title="Автор статьи"
                >
                <xsl:apply-templates
                    select=" $so_uri_map[ @so_uri = current()/@mixer_article_author ] "
                    mode="mixer_author_name"
                />
            </a>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_head" />
    <xsl:template match=" mixer_article[ @mixer_article_name ] " mode="mixer_article_head">
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
                        <xsl:value-of select=" @mixer_article_name " />
                    </wc_editor>
                </wc_field>
            </h1>
        </form>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_body">
        <wc_net-bridge
            wc_net-bridge_resource="{ @so_uri }"
            wc_net-bridge_field="mixer_article_content"
            >
            <wc_editor
                wc_editor_hlight="md"
                >
                <xsl:value-of select=" @mixer_article_content " />
            </wc_editor>
        </wc_net-bridge>
    </xsl:template>
    
</xsl:stylesheet>
