<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

    <xsl:output method="xml" />

    <xsl:template match=" node() | @* ">
        <xsl:copy>
            <xsl:apply-templates select=" node() | @*" />
        </xsl:copy>
    </xsl:template>

    <xsl:template match=" xsl:value-of ">
        <xsl:processing-instruction name="val">
            <xsl:value-of select=" @select " />
        </xsl:processing-instruction>
    </xsl:template>

    <xsl:template match=" xsl:copy-of ">
        <xsl:processing-instruction name="copy">
            <xsl:value-of select=" @select " />
        </xsl:processing-instruction>
    </xsl:template>

    <xsl:template match=" xsl:copy ">
        <xsl:processing-instruction name="copy-">
            <xsl:value-of select=" @use-attribute-sets " />
        </xsl:processing-instruction>
        <xsl:apply-templates />
        <xsl:processing-instruction name="copy."/>
    </xsl:template>

    <xsl:template match=" xsl:if [ xsl:value-of and count( * ) = 1 ] ">
        <xsl:processing-instruction name="if">
            <xsl:value-of select=" @test " />
            <xsl:text>\</xsl:text>
            <xsl:value-of select=" xsl:value-of / @select " />
        </xsl:processing-instruction>
    </xsl:template>

</xsl:stylesheet>