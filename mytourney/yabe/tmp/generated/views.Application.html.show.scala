
                    package views.Application.html

                    import play.templates._
                    import play.templates.TemplateMagic._
                    import views.html._

                    object show extends BaseScalaTemplate[Html,Format[Html]](HtmlFormat) {

                        def apply/*1.2*/(
post:(models.Post,models.User,Seq[models.Comment]),
pagination:(Option[models.Post],Option[models.Post])
)(
implicit
params:play.mvc.Scope.Params,
flash:play.mvc.Scope.Flash,
errors:Map[String,play.data.validation.Error]
):Html = {
                            try {
                                _display_ {

format.raw/*9.2*/("""

""")+_display_(/*11.2*/if(flash.get("success"))/*11.26*/ {format.raw/*11.28*/("""
<p class="success">""")+_display_(/*12.21*/flash/*12.26*/.get("success"))+format.raw/*12.41*/("""</p>
""")})+format.raw/*13.2*/("""

""")+_display_(/*15.2*/main(title = post._1.title)/*15.29*/ {format.raw/*15.31*/("""

<ul id="pagination">
    """)+_display_(/*18.6*/pagination/*18.16*/._1.map/*18.23*/ { post =>format.raw/*18.33*/("""
    <li id="previous">
        <a href="""")+_display_(/*20.19*/action(controllers.Application.show(post.id())))+format.raw/*20.66*/("""">
            """)+_display_(/*21.14*/post/*21.18*/.title)+format.raw/*21.24*/("""
        </a>
    </li>
    """)})+format.raw/*24.6*/("""
    """)+_display_(/*25.6*/pagination/*25.16*/._2.map/*25.23*/ { post =>format.raw/*25.33*/("""
    <li id="next">
        <a href="""")+_display_(/*27.19*/action(controllers.Application.show(post.id())))+format.raw/*27.66*/("""">
            """)+_display_(/*28.14*/post/*28.18*/.title)+format.raw/*28.24*/("""
        </a>
    </li>
    """)})+format.raw/*31.6*/("""
</ul>

""")+_display_(/*34.2*/display(post, mode = "full"))+format.raw/*34.30*/("""

<h3>Post a comment</h3>

""")+_display_(/*38.2*/form(controllers.Application.postComment(post._1.id()))/*38.57*/ {format.raw/*38.59*/("""

""")+_display_(/*40.2*/if(errors)/*40.12*/ {format.raw/*40.14*/("""
<p class="error">
    All fields are required!
</p>
""")})+format.raw/*44.2*/("""

<p>
    <label for="author">Your name: </label>
    <input type="text" name="author" value="""")+_display_(/*48.46*/params/*48.52*/.get(" author"))+format.raw/*48.67*/("""">
</p>
<p>
    <label for="content">Your message: </label>
    <textarea name="content">""")+_display_(/*52.31*/params/*52.37*/.get("content"))+format.raw/*52.52*/("""</textarea>
</p>
<p>
    <input type="submit" value="Submit your comment"/>
</p>

""")})+format.raw/*58.2*/("""

""")})+format.raw/*60.2*/("""

<script type="text/javascript" charset="utf-8">
    $(function() """)+format.raw("""{""")+format.raw/*63.19*/("""
    // Expose the form
    $('form').click(function() """)+format.raw("""{""")+format.raw/*65.33*/("""
    $('form').expose(""")+format.raw("""{""")+format.raw/*66.23*/("""api: true""")+format.raw("""}""")+format.raw/*66.33*/(""").load();
    """)+format.raw("""}""")+format.raw/*67.6*/(""");

    // If there is an error, focus to form
    if($('form .error').size()) """)+format.raw("""{""")+format.raw/*70.34*/("""
    $('form').expose(""")+format.raw("""{""")+format.raw/*71.23*/("""api: true, loadSpeed: 0""")+format.raw("""}""")+format.raw/*71.47*/(""").load();
    $('form input[type=text]').get(0).focus();
    """)+format.raw("""}""")+format.raw/*73.6*/("""
    """)+format.raw("""}""")+format.raw/*74.6*/(""");
</script>""")}
                            } catch {
                                case e:TemplateExecutionError => throw e
                                case e => throw Reporter.toHumanException(e)
                            }
                        }

                    }

                
                /*
                    -- GENERATED --
                    DATE: Wed Jan 04 17:20:25 CST 2012
                    SOURCE: /app/views/Application/show.scala.html
                    HASH: bc6dd71883d77841c7e9c3a2f8ad2af5c7d314a2
                    MATRIX: 328->1|658->225|687->228|720->252|741->254|789->275|803->280|839->295|873->301|902->304|938->331|959->333|1013->361|1032->371|1048->378|1077->388|1146->430|1214->477|1257->493|1270->497|1297->503|1354->532|1386->538|1405->548|1421->555|1450->565|1515->603|1583->650|1626->666|1639->670|1666->676|1723->705|1758->714|1807->742|1861->770|1925->825|1946->827|1975->830|1994->840|2015->842|2097->896|2219->991|2234->997|2270->1012|2387->1102|2402->1108|2438->1123|2549->1206|2580->1209|2695->1277|2798->1333|2868->1356|2925->1366|2986->1381|3113->1461|3183->1484|3254->1508|3362->1570|3414->1576
                    LINES: 10->1|22->9|24->11|24->11|24->11|25->12|25->12|25->12|26->13|28->15|28->15|28->15|31->18|31->18|31->18|31->18|33->20|33->20|34->21|34->21|34->21|37->24|38->25|38->25|38->25|38->25|40->27|40->27|41->28|41->28|41->28|44->31|47->34|47->34|51->38|51->38|51->38|53->40|53->40|53->40|57->44|61->48|61->48|61->48|65->52|65->52|65->52|71->58|73->60|76->63|78->65|79->66|79->66|80->67|83->70|84->71|84->71|86->73|87->74
                    -- GENERATED --
                */
            
