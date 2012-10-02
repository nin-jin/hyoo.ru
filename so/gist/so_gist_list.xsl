<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_gist_list ">
        <wc_spacer>
            <wc_paper>
                <wc_spacer>
                    <xsl:apply-templates select="." mode="so_gist_list-title" />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
        <xsl:apply-templates select="." mode="so_gist_list-content" />
    </xsl:template>
    
    <xsl:template match=" so_gist_list " mode="so_gist_list-title">
        <h1>Записи</h1>
    </xsl:template>
    
    <xsl:template match=" so_gist_list[ @so_gist_author ] " mode="so_gist_list-title">
        <h1><b><xsl:value-of select="@so_gist_author" /></b> - записи</h1>
    </xsl:template>

    <xsl:template match=" so_gist_list " mode="so_gist_list-content" />
    <xsl:template match=" so_gist_list[ so_gist ] " mode="so_gist_list-content">
        <xsl:apply-templates select=" so_gist " />
    </xsl:template>
    
</xsl:stylesheet>
