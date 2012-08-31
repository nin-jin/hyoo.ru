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
                    <form
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
                    </form>
                    <xsl:apply-templates select="." mode="so:gist_permalink" />
                </wc:top-tool_pane>
            </wc:top-tool>
            <wc:paper>
                <wc:spacer>
                    <wc:net-bridge
                        wc:net-bridge_resource="{ so:gist_uri }"
                        wc:net-bridge_field="content"
                        >
                        <wc:editor
                            wc:editor_hlight="md"
                            >
                            <xsl:apply-templates />
                        </wc:editor>
                    </wc:net-bridge>
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
    
    <xsl:template match=" so:gist_uri " />
    <xsl:template match=" so:gist_name " />
    <xsl:template match=" so:gist_author " />

    <xsl:template match=" so:gist_content ">
        <xsl:apply-templates />
    </xsl:template>
    
</xsl:stylesheet>
