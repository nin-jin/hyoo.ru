<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    >

<xsl:template match=" wc:test-js/text() ">
    <textarea class=" wc_test-js_textarea" >
        <xsl:copy />
    </textarea>
</xsl:template>

</xsl:stylesheet>