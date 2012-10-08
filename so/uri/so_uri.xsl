<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:variable
        name="so_uri_map"
        select=" // *[ @so_uri ] | document( // @so_uri_external, / ) // *[ @so_uri ] "
    />
    
</xsl:stylesheet>
