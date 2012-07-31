
                    package views.html

                    import play.templates._
                    import play.templates.TemplateMagic._
                    import views.html._

                    object main extends BaseScalaTemplate[Html,Format[Html]](HtmlFormat) {

                        def apply/*1.2*/(title:String = "")(body: => Html):Html = {
                            try {
                                _display_ {

format.raw/*1.36*/("""

<!DOCTYPE html>
<html>
<head>
    <title>""")+_display_(/*6.13*/title)+format.raw/*6.18*/("""</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" media="screen" href="""")+_display_(/*8.50*/asset("public/stylesheets/main.css"))+format.raw/*8.86*/("""">
    <link rel="shortcut icon" type="image/png" href="""")+_display_(/*9.55*/asset("public/images/favicon.png"))+format.raw/*9.89*/("""">
    <script src="""")+_display_(/*10.19*/asset("public/javascripts/jquery-1.6.4.min.js"))+format.raw/*10.66*/("""" type="text/javascript"></script>
    <script src="""")+_display_(/*11.19*/asset("public/javascripts/jquery.tools.min.js"))+format.raw/*11.66*/(""""></script>
</head>
<body>

<div id="header">
    <div id="logo">
        yabe.
    </div>
    <ul id="tools">
        <li>
            <a href="#">Log in to write something</a>
        </li>
    </ul>
    <div id="title">
        <span class="about">About this blog</span>
        <h1>
            <a href="""")+_display_(/*27.23*/action(controllers.Application.index))+format.raw/*27.60*/("""">
                """)+_display_(/*28.18*/play/*28.22*/.Play.configuration.get("blog.title"))+format.raw/*28.59*/("""
            </a>
        </h1>
        <h2>""")+_display_(/*31.14*/play/*31.18*/.Play.configuration.get("blog.baseline"))+format.raw/*31.58*/("""</h2>
    </div>
</div>

<div id="main">
    """)+_display_(/*36.6*/body)+format.raw/*36.10*/("""
</div>

<p id="footer">
    Yabe is a (not that) powerful blog engine built with the
    <a href="http://www.playframework.org">Play framework</a>
    as a tutorial application.
</p>

</body>
</html>""")}
                            } catch {
                                case e:TemplateExecutionError => throw e
                                case e => throw Reporter.toHumanException(e)
                            }
                        }

                    }

                
                /*
                    -- GENERATED --
                    DATE: Wed Jan 04 05:06:42 CST 2012
                    SOURCE: /app/views/main.scala.html
                    HASH: a012fbaaf4f35c48ceacda36e98018fb0938bb17
                    MATRIX: 316->1|457->35|527->79|552->84|709->215|765->251|848->308|902->342|950->363|1018->410|1098->463|1166->510|1502->819|1560->856|1607->876|1620->880|1678->917|1750->962|1763->966|1824->1006|1896->1052|1921->1056
                    LINES: 10->1|14->1|19->6|19->6|21->8|21->8|22->9|22->9|23->10|23->10|24->11|24->11|40->27|40->27|41->28|41->28|41->28|44->31|44->31|44->31|49->36|49->36
                    -- GENERATED --
                */
            
