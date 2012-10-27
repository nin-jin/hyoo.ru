<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >

<xsl:template match=" so_conflict ">
    <wc_spacer>
        <wc_error>
            <xsl:apply-templates select=" node() " />
        </wc_error>
        <wc_button>
            <button
                type="submit"
                name="so_conflict_force"
                value="true"
                >
                <xsl:text>Знаю, пофиг!</xsl:text>
            </button>
        </wc_button>
    </wc_spacer>
</xsl:template>

</xsl:stylesheet>
