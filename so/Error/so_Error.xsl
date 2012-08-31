<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >

<xsl:template match=" so:Error ">
    <wc:spacer>
        <wc:paper>
            <wc:error>
                <xsl:apply-templates select=" text() " />
            </wc:error>
        </wc:paper>
    </wc:spacer>
</xsl:template>

</xsl:stylesheet>
