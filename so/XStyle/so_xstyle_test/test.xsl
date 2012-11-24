<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"> 
    
<!--include href="./test-inc.xs" -->
    
    <xsl:strip-space elements=" ELEMENT NAMES " />
    
    <xsl:preserve-space elements=" ELEMENT NAMES " />
    
    <xsl:key name="KEY_NAME" match=" MATCH/XPATH " use=" USE/XPATH  " />
    
    <xsl:attribute-set name="NAME" use-attribute-sets=" ATTRIBUTE SETS ">
        
        <xsl:attribute name="ATTR/NAME"><xsl:value-of select=" VALUE/XPATH "/></xsl:attribute>
        
        <xsl:attribute name="ATTR/NAME">
            ATTR/VALUE
        </xsl:attribute>
        
    </xsl:attribute-set>
    
<!--/include-->

    <xsl:template match=" MATCH/XPATH  " mode="MODE_NAME" >
        <xsl:param name="NAME" select="  XPATH " />
        <xsl:param name="NAME" >
            VALUE
        </xsl:param>
        <xsl:if test=" TEST/XPATH ">
            <xsl:if test="TEST/XPATH "><xsl:value-of select=" VALUE/XPATH "/></xsl:if>
            <xsl:if test="TEST/VALUE/XPATH "><xsl:value-of select="TEST/VALUE/XPATH "/></xsl:if>
            <xsl:value-of select="VALUE/XPATH "/>
        </xsl:if>
        <xsl:call-template name="NAME" />
        <xsl:call-template name="NAME" >
            <xsl:with-param name="NAME" select="  XPATH " />
            <xsl:with-param name="NAME" >
                VALUE
            </xsl:with-param>
        </xsl:call-template>
        <xsl:text>ANY&amp;TEXT</xsl:text>
        <xsl:comment>ANY&amp;TEXT</xsl:comment>
    </xsl:template>
    <xsl:template name="NAME">
        <xsl:variable name="NAME" select="  XPATH " />
        <xsl:variable name="NAME" >
            VALUE
        </xsl:variable>
        <xsl:apply-templates />
        <xsl:apply-templates select=" SELECT/XPATH " />
        <xsl:apply-templates mode="MODE_NAME" />
        <xsl:apply-templates select=" SELECT/XPATH " mode="MODE_NAME" >
            <xsl:sort select=" name " lang="ru" data-type="text" order="ascending" case-order="upper-first"/>
        </xsl:apply-templates>
        <xsl:copy-of select="XPATH "/>
        <xsl:copy attribute-sets="ATTRIBUTE SETS" >
            VALUE
        </xsl:copy>
        <?choose-?>
            <?when TEST \ XPATH ?>
            <?when- TEST ?>
                VALUE
            <?when.?>
            <?other XPATH ?>
            <?other-?>
                VALUE
            <?other.?>
        <?choose.?>
    </xsl:template>
</xsl:stylesheet>
