<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" mixer_gist " />
    <xsl:template match=" mixer_gist[ @so_uri and @mixer_gist_content ] ">
        <wc_net-bridge
            wc_net-bridge_resource="{ @so_uri }"
            wc_net-bridge_field="content"
            >
            <wc_editor
                wc_editor_hlight="md"
                >
                <xsl:value-of select=" @mixer_gist_content " />
            </wc_editor>
        </wc_net-bridge>
    </xsl:template>
    
</xsl:stylesheet>
