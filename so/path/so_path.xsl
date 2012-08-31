<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:template match=" so:path ">
        <wc:path>
            <xsl:apply-templates />
        </wc:path>
    </xsl:template>

    <xsl:template match=" so:path_item ">
        <xsl:text>/</xsl:text>
        <wc:path_item>
            <a href="{ so:path_link }">
                <xsl:apply-templates select=" so:path_title " />
            </a>
        </wc:path_item>
    </xsl:template>

    <xsl:template match=" so:path_title ">
        <xsl:apply-templates />
    </xsl:template>

</xsl:stylesheet>
