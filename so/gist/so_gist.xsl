<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:template match=" so:gist ">
        <wc:spacer>
            <wc:top-tool>
                <wc:top-tool_pane>
<!--                    <form
                        method="MOVE"
                        action="?"
                        >
                        <wc:top-tool_item>
                            <input
                                name="gist"
                                value="{ so:gist_name }"
                                placeholder="gist"
                            />
                        </wc:top-tool_item>
                        <wc:top-tool_hidden>
                            <input
                                name="from"
                                value="{ so:gist_uri }"
                                type="hidden"
                            />
                            <input
                                type="submit"
                                value="Изменить"
                            />
                        </wc:top-tool_hidden>
                    </form>-->
                    <xsl:apply-templates select="." mode="so:gist_permalink" />
                </wc:top-tool_pane>
            </wc:top-tool>
            <wc:paper>
                <wc:spacer>
                    <xsl:apply-templates select="." mode="so:gist_title" />
                    <xsl:apply-templates select="." mode="so:gist_view" />
                </wc:spacer>
            </wc:paper>
        </wc:spacer>
    </xsl:template>
    
    <xsl:template match=" so:gist " mode="so:gist_permalink" />
    <xsl:template match=" so:gist[ so:gist_uri ] " mode="so:gist_permalink">
        <wc:top-tool_item>
            <wc:permalink title="Ссылка на эту запись">
                <a href="{ so:gist_uri }">#</a>
            </wc:permalink>
        </wc:top-tool_item>
    </xsl:template>
    
    <xsl:template match=" so:gist " mode="so:gist_view" />
    <xsl:template match=" so:gist[ so:gist_content ] " mode="so:gist_view">
        <wc:net-bridge
            wc:net-bridge_resource="{ so:gist_uri }"
            wc:net-bridge_field="content"
            >
            <wc:editor
                wc:editor_hlight="md"
                >
                <xsl:apply-templates select=" so:gist_content / node() " />
            </wc:editor>
        </wc:net-bridge>
    </xsl:template>
    
    <xsl:template match=" so:gist " mode="so:gist_title" />
    <xsl:template match=" so:gist[ so:gist_name ] " mode="so:gist_title">
        <h1>
            <form
                method="move"
                action="?"
                >
                <wc:field wc:field_name="gist">
                    <wc:editor>
                        <xsl:apply-templates select=" so:gist_name " />
                    </wc:editor>
                </wc:field>
                <input
                    type="hidden"
                    name="by"
                    value="{so:gist_author}"
                />
                <input
                    type="hidden"
                    name="from"
                    value="{so:gist_uri}"
                />
            </form>
        </h1>
    </xsl:template>
    
</xsl:stylesheet>
