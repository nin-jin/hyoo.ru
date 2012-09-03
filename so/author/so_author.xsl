<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:template match=" so:author ">
        <wc:spacer>
            <wc:top-tool>
                <wc:top-tool_pane>
                    <xsl:apply-templates select="." mode="so:author_permalink" />
                </wc:top-tool_pane>
            </wc:top-tool>
            <wc:paper>
                <wc:spacer>
                    <xsl:apply-templates select="." mode="so:author_title" />
                    <xsl:apply-templates select="." mode="so:author_descr" />
                </wc:spacer>
            </wc:paper>
        </wc:spacer>
    </xsl:template>
    
    <xsl:template match=" so:author " mode="so:author_permalink" />
    <xsl:template match=" so:author[ so:author_uri ] " mode="so:author_permalink">
        <wc:top-tool_item>
            <wc:permalink title="Ссылка на этого пользователя">
                <a href="{ so:author_uri }">#</a>
            </wc:permalink>
        </wc:top-tool_item>
    </xsl:template>
    
    <xsl:template match=" so:author " mode="so:author_title" />
    <xsl:template match=" so:author[ so:author_name ] " mode="so:author_title">
        <h1>
            <xsl:value-of select=" so:author_name " />
        </h1>
    </xsl:template>

    <xsl:template match=" so:author " mode="so:author_descr" />
    <xsl:template match=" so:author[ so:author_about ] " mode="so:author_descr">
        <xsl:apply-templates select=" so:author_about / so:gist " mode="so:gist_view" />
    </xsl:template>
    
</xsl:stylesheet>
