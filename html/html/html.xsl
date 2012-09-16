<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" / html ">
        <xsl:apply-templates select="node() " />
    </xsl:template>

</xsl:stylesheet>
