<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:output method="html" />
    
    <xsl:template match=" html:include ">
        <xsl:apply-templates select=" document( ., . ) / * / node() " />
    </xsl:template>
    
    <xsl:template match=" / html:html ">
        <xsl:apply-templates select="node() " />
    </xsl:template>

</xsl:stylesheet>
