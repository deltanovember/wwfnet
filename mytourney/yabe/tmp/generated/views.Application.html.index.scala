
                    package views.Application.html

                    import play.templates._
                    import play.templates.TemplateMagic._
                    import views.html._

                    object index extends BaseScalaTemplate[Html,Format[Html]](HtmlFormat) {

                        def apply/*1.2*/(
front:Option[(models.Post,models.User,Seq[models.Comment])],
older:Seq[(models.Post,models.User,Seq[models.Comment])]
):Html = {
                            try {
                                _display_ {

format.raw/*4.2*/("""

""")+_display_(/*6.2*/main(title = "Home")/*6.22*/ {format.raw/*6.24*/("""

""")+_display_(/*8.2*/front/*8.7*/.map/*8.11*/ { front =>format.raw/*8.22*/("""

<div class="post">
    <h2 class="post-title">
        <a href="#">""")+_display_(/*12.22*/front/*12.27*/._1.title)+format.raw/*12.36*/("""</a>
    </h2>
    <div class="post-metadata">
        <span class="post-author">by """)+_display_(/*15.39*/front/*15.44*/._2.fullname)+format.raw/*15.56*/("""</span>
                <span class="post-date">
                    """)+_display_(/*17.22*/front/*17.27*/._1.postedAt.format("MMM dd"))+format.raw/*17.56*/("""
                </span>
                <span class="post-comments">
                    &nbsp;|&nbsp;

                    """)+_display_(/*22.22*/if(front._3)/*22.34*/ {format.raw/*22.36*/("""
                        """)+_display_(/*23.26*/front/*23.31*/._3.size)+format.raw/*23.39*/(""" comments,
                        latest by """)+_display_(/*24.36*/front/*24.41*/._3(0).author)+format.raw/*24.54*/("""
                    """)}/*25.23*/else/*25.28*/{format.raw/*25.29*/("""
                        no comments
                    """)})+format.raw/*27.22*/("""

                </span>
    </div>
    <div class="post-content">
        """)+_display_(/*32.10*/Html(front._1.content.replace("\n", "<br>")))+format.raw/*32.54*/("""
    </div>
</div>

""")+_display_(/*36.2*/Option(older)/*36.15*/.filterNot(_.isEmpty).map/*36.40*/ { posts =>format.raw/*36.51*/("""

<div class="older-posts">
    <h3>Older posts <span class="from">from this blog</span></h3>

    """)+_display_(/*41.6*/posts/*41.11*/.map/*41.15*/ { post =>format.raw/*41.25*/("""
    <div class="post">
        <h2 class="post-title">
            <a href="""")+_display_(/*44.23*/action(controllers.Application.show(post._1.id())))+format.raw/*44.73*/("""">
                """)+_display_(/*45.18*/post/*45.22*/._1.title)+format.raw/*45.31*/("""
            </a>
        </h2>
        <div class="post-metadata">
                           <span class="post-author">
                               by """)+_display_(/*50.36*/post/*50.40*/._2.fullname)+format.raw/*50.52*/("""
                           </span>
                           <span class="post-date">
                               """)+_display_(/*53.33*/post/*53.37*/._1.postedAt.format("dd MMM yy"))+format.raw/*53.69*/("""
                           </span>
            <div class="post-comments">
                """)+_display_(/*56.18*/if(post._3)/*56.29*/ {format.raw/*56.31*/("""
                """)+_display_(/*57.18*/post/*57.22*/._3.size)+format.raw/*57.30*/(""" comments,
                latest by """)+_display_(/*58.28*/post/*58.32*/._3(0).author)+format.raw/*58.45*/("""
                """)}/*59.19*/else/*59.24*/{format.raw/*59.25*/("""
                no comments
                """)})+format.raw/*61.18*/("""
            </div>
        </div>
    </div>
    """)})+format.raw/*65.6*/("""

</div>

""")})+format.raw/*69.2*/("""

""")}/*71.2*/.getOrElse/*71.12*/ {format.raw/*71.14*/("""

<div class="empty">
    There is currently nothing to read here.
</div>

""")})+format.raw/*77.2*/("""

""")})}
                            } catch {
                                case e:TemplateExecutionError => throw e
                                case e => throw Reporter.toHumanException(e)
                            }
                        }

                    }

                
                /*
                    -- GENERATED --
                    DATE: Tue Jan 03 16:23:42 CST 2012
                    SOURCE: /app/views/Application/index.scala.html
                    HASH: ad6906d0496a3e5301306441942a4d24f665db1e
                    MATRIX: 329->1|556->122|584->125|612->145|632->147|660->150|672->155|684->159|713->170|810->240|824->245|854->254|966->339|980->344|1013->356|1110->426|1124->431|1174->460|1327->586|1348->598|1369->600|1422->626|1436->631|1465->639|1538->685|1552->690|1586->703|1625->726|1638->731|1658->732|1745->790|1849->867|1914->911|1961->932|1983->945|2017->970|2047->981|2173->1081|2187->1086|2200->1090|2229->1100|2334->1178|2405->1228|2452->1248|2465->1252|2495->1261|2679->1418|2692->1422|2725->1434|2872->1554|2885->1558|2938->1590|3058->1683|3078->1694|3099->1696|3144->1714|3157->1718|3186->1726|3251->1764|3264->1768|3298->1781|3333->1800|3346->1805|3366->1806|3441->1852|3520->1903|3559->1914|3578->1917|3597->1927|3618->1929|3722->2005
                    LINES: 10->1|17->4|19->6|19->6|19->6|21->8|21->8|21->8|21->8|25->12|25->12|25->12|28->15|28->15|28->15|30->17|30->17|30->17|35->22|35->22|35->22|36->23|36->23|36->23|37->24|37->24|37->24|38->25|38->25|38->25|40->27|45->32|45->32|49->36|49->36|49->36|49->36|54->41|54->41|54->41|54->41|57->44|57->44|58->45|58->45|58->45|63->50|63->50|63->50|66->53|66->53|66->53|69->56|69->56|69->56|70->57|70->57|70->57|71->58|71->58|71->58|72->59|72->59|72->59|74->61|78->65|82->69|84->71|84->71|84->71|90->77
                    -- GENERATED --
                */
            
