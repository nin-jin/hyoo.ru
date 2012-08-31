<xsl:stylesheet
    version="1.0"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:html="http://www.w3.org/1999/xhtml"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:wc="https://github.com/nin-jin/wc"
    xmlns:doc="https://github.com/nin-jin/doc"
    xmlns:so="https://github.com/nin-jin/so"
    >
    
    <xsl:template match=" so:gist_creator ">
        <form
            method="GET"
            action="?"
            >
            <input
                name="gist"
                value="{ so:gist_name }"
                placeholder="gist"
            />
            <input
                name="author"
                value="{ so:gist_author }"
                type="hidden"
            />
            <input
                type="submit"
                value="Создать"
            />
        </form>
    </xsl:template>

</xsl:stylesheet>
