<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0"> 
    <?include ./test-inc.xs ?>
    <?match- MATCH/XPATH \ MODE_NAME ?>
        <?param NAME \ XPATH ?>
        <?param- NAME ?>
            VALUE
        <?param.?>
        <?if- TEST/XPATH ?>
            <?if TEST/XPATH \ VALUE/XPATH ?>
            <?if TEST/VALUE/XPATH ?>
            <?val VALUE/XPATH ?>
        <?if.?>
        <?call NAME ?>
        <?call- NAME ?>
            <?arg NAME \ XPATH ?>
            <?arg- NAME ?>
                VALUE
            <?arg.?>
        <?call.?>
        <?text ANY&TEXT?>
        <?comment ANY&TEXT?>
    <?match. ?>
    <?template- NAME ?>
        <?const NAME \ XPATH ?>
        <?const- NAME ?>
            VALUE
        <?const.?>
        <?apply ?>
        <?apply SELECT/XPATH ?>
        <?apply \ MODE_NAME ?>
        <?apply- SELECT/XPATH \ MODE_NAME ?>
            <xsl:sort select=" name " lang="ru" data-type="text" order="ascending" case-order="upper-first"/>
        <?apply.?>
        <?copy XPATH ?>
        <?copy- ATTRIBUTE SETS ?>
            VALUE
        <?copy.?>
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
    <?template.?>
</xsl:stylesheet>