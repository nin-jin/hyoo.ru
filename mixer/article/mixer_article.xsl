<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" mixer_article_list ">
        <xsl:apply-templates
            select=" * | document( * / @so_uri_external, / ) // *[ @so_uri ] "
            mode="so_gist_teaser"
        />
    </xsl:template>
    
    <xsl:template match=" mixer_article ">
        <wc_spacer>
            <xsl:apply-templates select="." mode="mixer_article_tools" />
            <wc_paper>
                <wc_article>
                    <xsl:apply-templates select="." mode="mixer_article_head" />
                    <xsl:apply-templates select="." mode="mixer_article_body" />
                </wc_article>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="so_gist_teaser">
        <xsl:apply-templates select=" . " />
    </xsl:template>
    <xsl:template match=" mixer_article[ @mixer_article_annotation ] " mode="so_gist_teaser">
        <wc_spacer>
            <xsl:apply-templates select="." mode="mixer_article_tools" />
            <wc_paper>
                <wc_article>
                    <wc_article_title>
                        <a
                            wc_reset="true"
                            href="{ @so_uri }"
                            >
                            <h1 wc_reset="true">
                                <xsl:value-of select=" @mixer_article_name " />
                            </h1>
                        </a>
                    </wc_article_title>
                    <wc_article_content>
                        <wc_net-bridge
                            wc_net-bridge_resource="{ @so_uri }"
                            wc_net-bridge_field="mixer_article_annotation"
                            >
                            <wc_editor
                                wc_editor_hlight="md"
                                >
                                <xsl:value-of select=" @mixer_article_annotation " />
                            </wc_editor>
                        </wc_net-bridge>
                    </wc_article_content>
                </wc_article>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_tools">
        <wc_pop-tool>
            <wc_pop-tool_panel wc_pop-tool_edge="top">
                <xsl:apply-templates select="." mode="mixer_article_author" />
                <xsl:apply-templates select="." mode="mixer_article_permalink" />
            </wc_pop-tool_panel>
            <xsl:apply-templates select="." mode="mixer_article_remove" />
        </wc_pop-tool>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_permalink" />
    <xsl:template match=" mixer_article[ @so_uri ] " mode="mixer_article_permalink">
        <wc_pop-tool_item>
            <a
                href="{ @so_uri }"
                title="Постоянная ссылка на эту запись"
                wc_reset="true"
                >
                <xsl:value-of select=" @mixer_article_name " />
            </a>
        </wc_pop-tool_item>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_remove" />
    <xsl:template match=" mixer_article[ @so_uri ] " mode="mixer_article_remove">
        <wc_pop-tool_panel wc_pop-tool_edge="top">
            <wc_pop-tool_item>
                <form
                    method="delete"
                    action="{ @so_uri }"
                    wc_form="wc_form"
                    >
                    <wc_form_result>
                        <xsl:comment/>
                    </wc_form_result>
                    <button
                        type="submit"
                        wc_reset="true"
                        >
                        <xsl:text>Удалить</xsl:text>
                    </button>
                </form>
            </wc_pop-tool_item>
        </wc_pop-tool_panel>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_author" />
    <xsl:template match=" mixer_article[ @mixer_article_author ] " mode="mixer_article_author">
        <wc_pop-tool_item>
            <a
                wc_reset="true"
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
            <wc_article_title>
                <h1 wc_reset="true">
                    <wc_field wc_field_name="mixer_article_name">
                        <wc_editor>
                            <xsl:value-of select=" @mixer_article_name " />
                        </wc_editor>
                    </wc_field>
                </h1>
            </wc_article_title>
        </form>
    </xsl:template>
    
    <xsl:template match=" mixer_article " mode="mixer_article_body">
        <wc_article_annotation>
            <wc_net-bridge
                wc_net-bridge_resource="{ @so_uri }"
                wc_net-bridge_field="mixer_article_annotation"
                >
                <wc_editor
                    wc_editor_hlight="md"
                    >
                    <xsl:value-of select=" @mixer_article_annotation " />
                </wc_editor>
            </wc_net-bridge>
        </wc_article_annotation>
        <wc_article_content>
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
        </wc_article_content>
    </xsl:template>
    
</xsl:stylesheet>
