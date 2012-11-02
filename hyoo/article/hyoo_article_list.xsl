<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:template match=" hyoo_article_list " mode="so_page_title">
        <xsl:text>Все статьи</xsl:text>
    </xsl:template>
    
    <xsl:template match=" hyoo_article_list[ @hyoo_article_author ] " mode="so_page_title">
        <xsl:value-of select=" $so_uri_map[ @so_uri = current() / @hyoo_article_author ] / @hyoo_author_name " />
        <xsl:text> – Статьи</xsl:text>
    </xsl:template>
    
    <xsl:template match=" hyoo_article_list " mode="so_page_description">
        <xsl:text>Приятное чтение. Удобное редактирование. Быстрый поиск.</xsl:text>
    </xsl:template>
    
    <xsl:template match=" hyoo_article_list ">
        <xsl:apply-templates
            select=" * | document( * / @so_uri_external, / ) // *[ @so_uri ] "
            mode="so_gist_teaser"
        />
    </xsl:template>
    
</xsl:stylesheet>
