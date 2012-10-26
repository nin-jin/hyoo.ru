<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_console ">
        <wc_spacer>
            <wc_terminal>
                <form
                    method="post"
                    action="{@so_uri}"
                    wc_form="wc_form"
                    >
                    <wc_terminal_out>
                        <wc_form_result></wc_form_result>
                    </wc_terminal_out>
                    <wc_terminal_in>
                        <wc_field wc_field_name="code">
                            <wc_editor
                                wc_editor_hlight="php"
                                wc_editor_active="true"
                                >
                                <xsl:comment/>
                            </wc_editor>
                        </wc_field>
                    </wc_terminal_in>
                </form>
            </wc_terminal>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_console_result ">
        <wc_hlight wc_hlight_lang="{ @so_console_lang }">
            <xsl:value-of select=" @so_console_content " />
        </wc_hlight>
    </xsl:template>
    
</xsl:stylesheet>
