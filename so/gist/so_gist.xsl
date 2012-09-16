<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_gist ">
        <wc_spacer>
            <wc_top-tool>
                <wc_top-tool_pane>
                    <!--<form
                        method="MOVE"
                        action="?"
                        >
                        <wc_top-tool_item>
                            <input
                                name="gist"
                                value="{ @so_gist_name }"
                                placeholder="gist"
                            />
                        </wc_top-tool_item>
                        <wc_top-tool_item>
                            <input
                                name="by"
                                value="{ @so_gist_author }"
                                placeholder="author"
                            />
                        </wc_top-tool_item>
                        <wc_top-tool_hidden>
                            <input
                                name="from"
                                value="{ @so_uri }"
                                type="hidden"
                            />
                            <input
                                type="submit"
                                value="Изменить"
                            />
                        </wc_top-tool_hidden>
                    </form>-->
                    <xsl:apply-templates select="." mode="so_gist_permalink" />
                </wc_top-tool_pane>
            </wc_top-tool>
            <wc_paper>
                <wc_spacer>
                    <xsl:apply-templates select="." mode="so_gist_title" />
                    <xsl:apply-templates select="." mode="so_gist_view" />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_gist " mode="so_gist_permalink" />
    <xsl:template match=" so_gist[ @so_uri ] " mode="so_gist_permalink">
        <wc_top-tool_item>
            <wc_permalink title="Ссылка на эту запись">
                <a href="{ @so_uri }">#</a>
            </wc_permalink>
        </wc_top-tool_item>
    </xsl:template>
    
    <xsl:template match=" so_gist " mode="so_gist_title" />
    <xsl:template match=" so_gist[ @so_gist_name ] " mode="so_gist_title">
        <h1>
            <form
                method="move"
                action="{@so_uri}"
                wc_form="wc_form"
                >
                <wc_field wc_field_name="name">
                    <wc_editor>
                        <xsl:value-of select=" @so_gist_name " />
                    </wc_editor>
                </wc_field>
            </form>
        </h1>
    </xsl:template>
    
    <xsl:template match=" so_gist " mode="so_gist_view" />
    <xsl:template match=" so_gist[ @so_gist_content | @so_gist_external ] " mode="so_gist_view">
        <wc_net-bridge
            wc_net-bridge_resource="{ @so_uri }"
            wc_net-bridge_field="content"
            >
            <wc_editor
                wc_editor_hlight="md"
                >
                <xsl:value-of select=" ( document( @so_gist_external, . ) / * | . ) / @so_gist_content " />
            </wc_editor>
        </wc_net-bridge>
    </xsl:template>
    
</xsl:stylesheet>
