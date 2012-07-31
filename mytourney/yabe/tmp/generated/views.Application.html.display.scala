
                    package views.Application.html

                    import play.templates._
                    import play.templates.TemplateMagic._
                    import views.html._

                    object display extends BaseScalaTemplate[Html,Format[Html]](HtmlFormat) {

                        def apply/*1.2*/(post:(models.Post,models.User,Seq[models.Comment]), mode: String = "full"):Html = {
                            try {
                                _display_ {
def /*3.2*/commentsTitle/*3.15*/ = {

format.raw/*3.19*/("""
""")+_display_(/*4.2*/if(post._3)/*4.13*/ {format.raw/*4.15*/("""
""")+_display_(/*5.2*/post/*5.6*/._3.size)+format.raw/*5.14*/(""" comments, latest by """)+_display_(/*5.36*/post/*5.40*/._3(0).author)+format.raw/*5.53*/("""
""")}/*6.3*/else/*6.8*/{format.raw/*6.9*/("""
no comments
""")})+format.raw/*8.2*/("""
""")};
format.raw/*1.77*/("""

""")+format.raw/*9.2*/("""

<div class="post """)+_display_(/*11.19*/mode)+format.raw/*11.23*/("""">
    <h2 class="post-title">
        <a href="#">""")+_display_(/*13.22*/post/*13.26*/._1.title)+format.raw/*13.35*/("""</a>
    </h2>
    <div class="post-metadata">
        <span class="post-author">by """)+_display_(/*16.39*/post/*16.43*/._2.fullname)+format.raw/*16.55*/("""</span>,
        <span class="post-date">
            """)+_display_(/*18.14*/post/*18.18*/._1.postedAt.format("dd MMM yy"))+format.raw/*18.50*/("""
        </span>
        """)+_display_(/*20.10*/if(mode != "full")/*20.28*/ {format.raw/*20.30*/("""
            <span class="post-comments">
                """)+_display_(/*22.18*/commentsTitle)+format.raw/*22.31*/("""
            </span>
        """)})+format.raw/*24.10*/("""
    </div>
    """)+_display_(/*26.6*/if(mode != "teaser")/*26.26*/ {format.raw/*26.28*/("""
    <div class="post-content">
        <div class="about">Detail: </div>
        """)+_display_(/*29.10*/Html(post._1.content.replace("\n", "<br>")))+format.raw/*29.53*/("""
    </div>
    """)})+format.raw/*31.6*/("""
</div>

""")+_display_(/*34.2*/if(mode == "full")/*34.20*/ {format.raw/*34.22*/("""

<div class="comments">
    <h3>
        """)+_display_(/*38.10*/commentsTitle)+format.raw/*38.23*/("""
    </h3>

    """)+_display_(/*41.6*/post/*41.10*/._3.map/*41.17*/ { comment =>format.raw/*41.30*/("""
    <div class="comment">
        <div class="comment-metadata">
            <span class="comment-author">by """)+_display_(/*44.46*/comment/*44.53*/.author)+format.raw/*44.60*/(""",</span>
                    <span class="comment-date">
                        """)+_display_(/*46.26*/comment/*46.33*/.postedAt.format("dd MMM yy"))+format.raw/*46.62*/("""
                    </span>
        </div>
        <div class="comment-content">
            <div class="about">Detail: </div>
            """)+_display_(/*51.14*/Html(comment.content.replace("\n", "<br>")))+format.raw/*51.57*/("""
        </div>
    </div>
    """)})+format.raw/*54.6*/("""

</div>

""")})}
                            } catch {
                                case e:TemplateExecutionError => throw e
                                case e => throw Reporter.toHumanException(e)
                            }
                        }

                    }

                
                /*
                    -- GENERATED --
                    DATE: Tue Jan 03 16:34:30 CST 2012
                    SOURCE: /app/views/Application/display.scala.html
                    HASH: 4b6e7c455f56a9f32a846a8f0054041f9130a84f
                    MATRIX: 331->1|505->79|526->92|550->96|577->98|596->109|616->111|643->113|654->117|682->125|730->147|742->151|775->164|792->167|803->172|821->173|862->187|892->76|920->189|967->209|992->213|1071->265|1084->269|1114->278|1226->363|1239->367|1272->379|1354->434|1367->438|1420->470|1473->496|1500->514|1521->516|1607->575|1641->588|1700->618|1743->635|1772->655|1793->657|1903->740|1967->783|2012->800|2048->810|2075->828|2096->830|2166->873|2200->886|2243->903|2256->907|2272->914|2304->927|2442->1038|2458->1045|2486->1052|2595->1134|2611->1141|2661->1170|2829->1311|2893->1354|2953->1386
                    LINES: 10->1|13->3|13->3|15->3|16->4|16->4|16->4|17->5|17->5|17->5|17->5|17->5|17->5|18->6|18->6|18->6|20->8|22->1|24->9|26->11|26->11|28->13|28->13|28->13|31->16|31->16|31->16|33->18|33->18|33->18|35->20|35->20|35->20|37->22|37->22|39->24|41->26|41->26|41->26|44->29|44->29|46->31|49->34|49->34|49->34|53->38|53->38|56->41|56->41|56->41|56->41|59->44|59->44|59->44|61->46|61->46|61->46|66->51|66->51|69->54
                    -- GENERATED --
                */
            
