<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_gist " />
    <xsl:template match=" so_gist[ @so_uri and @so_gist_content ] ">
        <wc_net-bridge
            wc_net-bridge_resource="{ @so_uri }"
            wc_net-bridge_field="content"
            >
            <wc_editor
                wc_editor_hlight="md"
                >
                <xsl:value-of select=" @so_gist_content " />
            </wc_editor>
        </wc_net-bridge>
    </xsl:template>
    
</xsl:stylesheet>
