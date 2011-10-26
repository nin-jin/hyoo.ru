<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:locale="https://github.com/nin-jin/locale"
    >

    <xsl:param name="locale:lang" select=" / * / @xml:lang " />
    <xsl:param name="locale:path" select=" concat( '../-mix/index.locale=', $locale:lang, '.xml' ) " />

</xsl:stylesheet>
