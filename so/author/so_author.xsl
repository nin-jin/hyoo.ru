<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_author ">
        <wc_spacer>
            <wc_top-tool>
                <wc_top-tool_pane>
                    <xsl:apply-templates select="." mode="so_author_permalink" />
                </wc_top-tool_pane>
            </wc_top-tool>
            <wc_paper>
                <wc_spacer>
                    <xsl:apply-templates select="." mode="so_author_title" />
                    <xsl:apply-templates select="." mode="so_author_catch" />
                    <xsl:apply-templates select="." mode="so_author_descr" />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_author " mode="so_author_permalink" />
    <xsl:template match=" so_author[ @so_uri ] " mode="so_author_permalink">
        <wc_top-tool_item>
            <wc_permalink title="Ссылка на этого пользователя">
                <a href="{ @so_uri }">#</a>
            </wc_permalink>
        </wc_top-tool_item>
    </xsl:template>
    
    <xsl:template match=" so_author " mode="so_author_title" />
    <xsl:template match=" so_author[ @so_author_name ] " mode="so_author_title">
        <h1>
            <form
                method="move"
                action="{@so_uri}"
                wc_form="wc_form"
                >
                <wc_field wc_field_name="author">
                    <wc_editor>
                        <xsl:value-of select=" @so_author_name " />
                    </wc_editor>
                </wc_field>
            </form>
        </h1>
    </xsl:template>

    <xsl:template match=" so_author " mode="so_author_catch" />
    <xsl:template match=" so_author[ @so_author_catch ] " mode="so_author_catch">
        <p>
            <form
                method="put"
                action="{@so_uri}"
                wc_form="wc_form"
                >
                <xsl:text>Никто ещё не представился как </xsl:text>
                <b>
                    <xsl:value-of select=" @so_author_name " />
                </b>
                <xsl:text> </xsl:text>
                <wc_button>
                    <button>Да это же я!</button>
                </wc_button>
            </form>
        </p>
    </xsl:template>
    
    <xsl:template match=" so_author " mode="so_author_descr" />
    <xsl:template match=" so_author[ @so_author_about ] " mode="so_author_descr">
        <xsl:apply-templates select=" key( 'so_uri', @so_author_about ) " mode="so_gist_view" />
    </xsl:template>
    
</xsl:stylesheet>
