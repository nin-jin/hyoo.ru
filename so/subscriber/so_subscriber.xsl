<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" so_subscriber_list ">
        <wc_spacer>
            <wc_error>Нет подписавшихся на этот ресурс</wc_error>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_subscriber_list[ so_subscriber ] ">
        <wc_spacer>
            <wc_paper>
                <table>
                    <thead>
                        <tr>
                            <th>Ресурс</th>
                            <th>Событие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:apply-templates />
                    </tbody>
                </table>
            </wc_paper>
        </wc_spacer>
    </xsl:template>
    
    <xsl:template match=" so_subscriber ">
        <tr>
            <td>
                <a href="{ @so_subscriber_uri }">
                    <xsl:value-of select=" @so_subscriber_uri " />
                </a>
            </td>
            <td>
                <xsl:value-of select=" @so_subscriber_event " />
            </td>
        </tr>
    </xsl:template>
    
</xsl:stylesheet>
