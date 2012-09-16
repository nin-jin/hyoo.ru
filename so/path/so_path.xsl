<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_path ">
        <wc_path>
            <xsl:apply-templates />
        </wc_path>
    </xsl:template>

    <xsl:template match=" so_path_item ">
        <xsl:text>/</xsl:text>
        <a href="{ so_path_link }">
            <wc_path_item>
                <xsl:apply-templates select=" so_path_title " />
            </wc_path_item>
        </a>
    </xsl:template>

    <xsl:template match=" so_path_title ">
        <xsl:apply-templates />
    </xsl:template>

</xsl:stylesheet>
