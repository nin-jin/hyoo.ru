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
                    <xsl:apply-templates select="." mode="so_author_head" />
                    <xsl:apply-templates select="." mode="so_author_body" />
                </wc_spacer>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_author " mode="so_author_permalink" />
    <xsl:template match=" so_author[ @so_uri ] " mode="so_author_permalink">
        <wc_top-tool_item>
            <wc_permalink title="Ссылка на эту запись">
                <a href="{ @so_uri }">#</a>
            </wc_permalink>
        </wc_top-tool_item>
    </xsl:template>
    
    <xsl:template match=" so_author " mode="so_author_head" />
    <xsl:template match=" so_author[ @so_author_name ] " mode="so_author_head">
        <h1>
            <form
                method="get"
                action="?"
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
    
    <xsl:template match=" so_author " mode="so_author_body">
        <wc_spacer>
            <wc_error>Такого автора ещё не существует</wc_error>
        </wc_spacer>
        <form
            method="put"
            action="{@so_uri}"
            wc_form="wc_form"
            >
            <wc_form_result></wc_form_result>
            <wc_button>
                <button>Да это же я!</button>
            </wc_button>
        </form>
    </xsl:template>
    <xsl:template match=" so_author[ @so_author_key ] " mode="so_author_body">
        <xsl:apply-templates select=" $so_uri_map[ @so_uri = current()/@so_author_about ] " />
    </xsl:template>
</xsl:stylesheet>
