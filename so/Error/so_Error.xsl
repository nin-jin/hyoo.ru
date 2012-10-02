<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:template match=" so_error ">
    <wc_spacer>
        <wc_error>
            <xsl:apply-templates select=" text() " />
        </wc_error>
    </wc_spacer>
</xsl:template>

</xsl:stylesheet>
