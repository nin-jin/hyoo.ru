<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

    <xsl:template match=" @* | node() ">
        <xsl:copy>
            <xsl:apply-templates select=" @* " />
            <xsl:apply-templates select=" node() " />
        </xsl:copy>
    </xsl:template>

    <xsl:template match=" processing-instruction() " />

</xsl:stylesheet>
