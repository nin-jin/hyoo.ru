<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    >
    
    <xsl:output
        method="html"
        omit-xml-declaration="yes"
        doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    />
    
    <xsl:variable
        name="so_page_stylesheet"
        select="/processing-instruction()[ name() = 'xml-stylesheet' ]"
    />
    
    <xsl:variable
        name="so_page_mode"
        select=" substring-before( substring-after( $so_page_stylesheet, '-mix/' ), '.xsl' ) "
    />
    
    <xsl:variable
        name="so_page_base"
        select=" substring-before( substring-after( $so_page_stylesheet, 'href=&quot;' ), concat( $so_page_mode, '.xsl' ) ) "
    />
    
    <xsl:variable
        name="so_page_uri"
        select=" / so_page / @so_page_uri "
    />
    
    <xsl:variable
        name="so_page_resource"
        select=" $so_uri_map[ @so_uri = $so_page_uri ] "
    />
    
    <xsl:template match=" so_page ">
        
        <html wc_reset="true">
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
                
                <xsl:apply-templates select=" . " mode="so_page_title" />
                <xsl:apply-templates select=" . " mode="so_page_description" />
                <xsl:apply-templates select=" . " mode="so_page_styles" />
                <xsl:apply-templates select=" . " mode="so_page_script" />
                <xsl:apply-templates select=" . " mode="so_page_icon" />
            </head>
            <body wc_reset="true">
                <wc_desktop>
                    <xsl:apply-templates select=" . " mode="so_page_logo" />
                    <xsl:apply-templates select=" . " mode="so_page_tools" />
                    <xsl:apply-templates select=" so_page_aside " mode="so_page_special" />
                    
                    <xsl:apply-templates select=" $so_page_resource " />
                    <xsl:apply-templates select=" * [ not( @so_uri ) ] " />
                    
                    <wc_spacer>
                        <wc_error><strong>Внимание!</strong> Это альфа-версия сайта, поэтому сохранность внесённых вами изменений пока не гарантируется!</wc_error>
                    </wc_spacer>
                    
                    <wc_footer>
                        <a href="mailto:nin-jin@ya.ru" wc_link="true">Экстренная связь с машинистом</a>
                    </wc_footer>
                    
                </wc_desktop>
            </body>
        </html>
        
    </xsl:template>

    <xsl:template match=" * " mode="so_page_title" />
    <xsl:template match=" so_page " mode="so_page_title">
        <title>
            <xsl:apply-templates select=" $so_page_resource " mode="so_page_title" />
        </title>
    </xsl:template>

    <xsl:template match=" * " mode="so_page_description" />
    <xsl:template match=" so_page " mode="so_page_description">
        <meta name="description">
            <xsl:attribute name="content">
                <xsl:apply-templates select=" $so_page_resource " mode="so_page_description" />
            </xsl:attribute>
        </meta>
    </xsl:template>

    <xsl:template match=" so_page " mode="so_page_styles" />
    <xsl:template match=" so_page[ @so_page_styles ] " mode="so_page_styles">
        <link href="{ @so_page_styles }" rel="stylesheet" />
    </xsl:template>

    <xsl:template match=" so_page " mode="so_page_script" />
    <xsl:template match=" so_page[ @so_page_script ] " mode="so_page_script">
        <script src="{ @so_page_script }">//</script>
    </xsl:template>

    <xsl:template match=" so_page " mode="so_page_icon" />
    <xsl:template match=" so_page[ @so_page_icon ] " mode="so_page_icon">
        <link rel="icon" href="{ @so_page_icon }" />
    </xsl:template>

    <xsl:template match=" so_page " mode="so_page_tools">
        <wc_pop-tool>
            <wc_pop-tool_panel wc_pop-tool_edge="bottom">
                <wc_pop-tool_item>
                    <a href="?article=О+проекте+hyoo.ru;author=Nin+Jin" wc_reset="true">О проекте</a>
                </wc_pop-tool_item>
            </wc_pop-tool_panel>
            <wc_pop-tool_panel wc_pop-tool_edge="bottom">
                <wc_pop-tool_item>
                    <a href="?author" wc_reset="true">ваш профиль</a>
                </wc_pop-tool_item>
            </wc_pop-tool_panel>
            <wc_pop-tool_panel wc_pop-tool_edge="bottom">
                <wc_pop-tool_item>
                    <a href="?article;list;author" wc_reset="true">ваши записи</a>
                </wc_pop-tool_item>
                <wc_pop-tool_item>
                    <a href="?article" wc_reset="true">+</a>
                </wc_pop-tool_item>
            </wc_pop-tool_panel>
            <wc_pop-tool_panel wc_pop-tool_edge="bottom">
                <wc_pop-tool_item>
                    <form
                        action="?"
                        method="get"
                        >
                        <input
                            name="search"
                            placeholder="Поиск"
                        />
                    </form>
                </wc_pop-tool_item>
            </wc_pop-tool_panel>
        </wc_pop-tool>
    </xsl:template>
    
    <xsl:template match=" so_page " mode="so_page_logo">
        <a href="?"><wc_logo>標</wc_logo></a>
    </xsl:template>
    
    <xsl:template match=" so_page_aside " />
    <xsl:template match=" so_page_aside " mode="so_page_special">
        <wc_sidebar wc_sidebar_align="right">
            <wc_editor
                wc_editor_hlight="tags"
                >
                <xsl:text> </xsl:text>
            </wc_editor>
        </wc_sidebar>
    </xsl:template>
    
    <xsl:template match=" so_page / html ">
        <xsl:copy-of select=" . " />
    </xsl:template>
    
</xsl:stylesheet>
