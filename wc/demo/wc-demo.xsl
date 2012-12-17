<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:template match=" wc_demo/text() ">
    <textarea>
        <xsl:copy />
    </textarea>
</xsl:template>

</xsl:stylesheet>
