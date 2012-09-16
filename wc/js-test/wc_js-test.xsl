<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:template match=" wc_js-test/text() ">
    <textarea class=" wc_js-test_textarea" >
        <xsl:copy />
    </textarea>
</xsl:template>

</xsl:stylesheet>
