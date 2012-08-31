<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:template match=" so:gist_aside ">
        <xsl:apply-templates mode="so:gist_aside" />
    </xsl:template>
    
    <xsl:template match=" html:include " mode="so:gist_aside">
        <xsl:apply-templates select=" document( @href, . ) / * " mode="so:gist_aside" />
    </xsl:template>
    
    <xsl:template match=" so:gist " mode="so:gist_aside">
    
        <wc:sidebar class=" align=left ">
            <wc:net-bridge wc:net-bridge_resource="{ so:gist_uri }">
                <wc:editor class=" hlight=md ">
                    <xsl:apply-templates select=" so:gist_content / node() " />
                </wc:editor>
            </wc:net-bridge>
        </wc:sidebar>
        
    </xsl:template>

</xsl:stylesheet>
