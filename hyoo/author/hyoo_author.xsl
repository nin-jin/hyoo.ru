<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    
    <xsl:template match=" hyoo_author ">
        <wc_spacer>
            <wc_pop-tool>
                <wc_pop-tool_panel wc_pop-tool_edge="top">
                    <xsl:apply-templates select="." mode="hyoo_author_permalink" />
                    <xsl:apply-templates select="." mode="hyoo_author_article-list" />
                </wc_pop-tool_panel>
            </wc_pop-tool>
            <wc_paper>
                <wc_article>
                    <xsl:apply-templates select="." mode="hyoo_author_head" />
                    <xsl:apply-templates select="." mode="hyoo_author_body" />
                </wc_article>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" hyoo_author " mode="hyoo_author_permalink" />
    <xsl:template match=" hyoo_author[ @so_uri ] " mode="hyoo_author_permalink">
        <wc_pop-tool_item>
            <a
                href="{ @so_uri }"
                title="Постоянная ссылка на этого автора"
                wc_reset="true"
                >
                <xsl:apply-templates select=" . " mode="hyoo_author_name" />
            </a>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" hyoo_author " mode="hyoo_author_article-list" />
    <xsl:template match=" hyoo_author[ @hyoo_author_article-list ] " mode="hyoo_author_article-list">
        <wc_pop-tool_item>
            <a
                href="{ @hyoo_author_article-list }"
                title="Статьи этого автора"
                wc_reset="true"
                >
                <xsl:text>Статьи</xsl:text>
            </a>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" hyoo_author " mode="hyoo_author_head" />
    <xsl:template match=" hyoo_author[ @hyoo_author_name ] " mode="hyoo_author_head">
        <wc_article_title>
            <h1 wc_reset="true">
                <form
                    method="get"
                    action="?"
                    wc_form="wc_form"
                    >
                    <wc_field wc_field_name="author">
                        <wc_editor>
                            <xsl:apply-templates select=" . " mode="hyoo_author_name" />
                        </wc_editor>
                    </wc_field>
                </form>
            </h1>
        </wc_article_title>
    </xsl:template>
    
    <xsl:template match=" hyoo_author " mode="hyoo_author_name" />
    <xsl:template match=" hyoo_author[ @hyoo_author_name ] " mode="hyoo_author_name">
        <xsl:value-of select=" @hyoo_author_name " />
    </xsl:template>
    
    <xsl:template match=" hyoo_author " mode="hyoo_author_body">
        <wc_article_content>
            <wc_spacer>
                <wc_error>Такого автора ещё не существует</wc_error>
            </wc_spacer>
            <form
                method="post"
                action="{@so_uri}"
                wc_form="wc_form"
                >
                <wc_form_result></wc_form_result>
                <wc_button>
                    <button>Да это же я!</button>
                </wc_button>
            </form>
        </wc_article_content>
    </xsl:template>
    
    <xsl:template match=" hyoo_author[ @so_gist_exists ] " mode="hyoo_author_body">
        <wc_article_content>
            <wc_net-bridge
                wc_net-bridge_resource="{ @so_uri }"
                wc_net-bridge_field="hyoo_author_about"
                >
                <wc_editor
                    wc_editor_hlight="md"
                    >
                    <xsl:value-of select=" @hyoo_author_about " />
                </wc_editor>
            </wc_net-bridge>
        </wc_article_content>
    </xsl:template>

</xsl:stylesheet>
