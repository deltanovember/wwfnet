<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>jQuery liScroll - a jQuery News Ticker</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="description" content=" "/>
<meta name="keywords" content=" " />
<script type="text/javascript" src="http://www.gcmingati.net/wordpress/wp-content/lab/jquery/newsticker/jq-liscroll/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="http://www.gcmingati.net/wordpress/wp-content/lab/jquery/newsticker/jq-liscroll/jquery.li-scroller.1.0.js"></script>
<!-- Syntax hl -->
<script src="http://www.gcmingati.net/wordpress/wp-content/themes/giancarlo-mingati/js/jquery-syntax/jquery.syntax.min.js" type="text/javascript" charset="utf-8"></script>


<link rel="stylesheet" href="http://www.gcmingati.net/wordpress/wp-content/lab/jquery/newsticker/jq-liscroll/li-scroller.css" type="text/css" media="screen" />
<script type="text/javascript">
$(function(){
	$("ul#ticker01").liScroll();
	$("ul#ticker02").liScroll({travelocity: 0.15});
//Syntax
$.syntax({root: 'http://www.gcmingati.net/wordpress/wp-content/themes/giancarlo-mingati/js/jquery-syntax/'});

});
</script>
<style type="text/css">
/* this page declarations */
#wrapp {
width: 760px;
text-align: left;
font: normal 1em Arial;
margin: 0 auto;
padding: 0;
color: black;
}
#wrapp h1 {font: bold 1.1em Arial; margin: 1.2em 0 0.5em 0; padding: 0;}
.gnb {
width: 740px;
margin: 20px 0 3px 0;
background: #f0f1f1 url(gnb_bg.gif) left top repeat-x
}
.gnb h3{
font: normal 9px/14px Arial;
text-align: right;
margin: 0 10px 0 0;
padding: 0
}
#wrapp p{font: normal 0.9em Arial; margin: 1em 0; padding: 0;}
#wrapp h2 {font: bold 1em Arial;}

code {
width:93%;
font: normal 11px 'Courier New', Courier, Fixed;
color: #000;
display: block;
padding: 1em;
margin: 1em 0;
background-color: #eee;
border: 1px solid #d3d3d6;
border-left-width: 5px;
white-space: pre;
overflow-x: auto;
}
</style>


</head>
<body>
<div id="wrapp">
<h1 style="letter-spacing: -1px;">liScroll (a jQuery News Ticker made easy) 1.0</h1>
<p>Last updated tuesday, march 30 2010</p>
<p><b>What's this?</b> liScroll is a jQuery plugin that transforms any given unordered list into a <em>scrolling News Ticker</em></p>



<div class="gnb">
<h3>jquery.li-scroller.1.0.js</h3>

<!-- first ticker -->
				<ul id="ticker01">
							<li><span>10/10/2007</span><a href="#/ogt/content/news/News183.complete">The first thing that most Javascript programmers</a></li>
							<li><span>10/10/2007</span><a href="#/ogt/content/news/News175.complete">End up doing is adding some code</a></li>

							<li><span>10/10/2007</span><a href="#/ogt/content/news/News177.complete">The code that you want to run</a></li>
							<li><span>08/10/2007</span><a href="#/ogt/content/news/News176.complete">Inside of which is the code that you want to run</a></li>
							<li><span>08/10/2007</span><a href="#/ogt/content/news/News178.complete">Right when the page is loaded</a></li>
							<li><span>05/10/2007</span><a href="#/ogt/content/news/News173.complete">Problematically, however, the Javascript code</a></li>
							<li><span>04/10/2007</span><a href="#/ogt/content/news/News183.complete">The first thing that most Javascript programmers</a></li>

							<li><span>04/10/2007</span><a href="#/ogt/content/news/News175.complete">End up doing is adding some code</a></li>
							<li><span>04/10/2007</span><a href="#/ogt/content/news/News177.complete">The code that you want to run</a></li>
							<li><span>03/10/2007</span><a href="#/ogt/content/news/News176.complete">Inside of which is the code that you want to run</a></li>
							<li><span>03/10/2007</span><a href="#/ogt/content/news/News178.complete">Right when the page is loaded</a></li>
							<li><span>01/10/2007</span><a href="#/ogt/content/news/News173.complete">Problematically, however, the Javascript code</a></li>

				</ul>

</div>

<h2>The markup</h2>
<pre class="syntax brush-html">
&lt;ul id=&quot;ticker01&quot;&gt;
	&lt;li&gt;&lt;span&gt;10/10/2007&lt;/span&gt;&lt;a href=&quot;#&quot;&gt;The first thing ...&lt;/a&gt;&lt;/li&gt;

	&lt;li&gt;&lt;span&gt;10/10/2007&lt;/span&gt;&lt;a href=&quot;#&quot;&gt;End up doing is ...&lt;/a&gt;&lt;/li&gt;
	&lt;li&gt;&lt;span&gt;10/10/2007&lt;/span&gt;&lt;a href=&quot;#&quot;&gt;The code that you ...&lt;/a&gt;&lt;/li&gt;

	&lt;!-- eccetera --&gt;
&lt;/ul&gt;
</pre>
<p>To build your news ticker all you need is the above markup: an unordered list with a <strong>unique ID</strong></p>

<!-- second ticker -->
		<ul id="ticker02">
					<li><span>10/10/2007</span><a href="#/ogt/content/news/News183.complete">The first thing that most Javascript programmers</a></li>

					<li><span>10/10/2007</span><a href="#/ogt/content/news/News175.complete">End up doing is adding some code</a></li>
					<li><span>10/10/2007</span><a href="#/ogt/content/news/News177.complete">The code that you want to run</a></li>
					<li><span>08/10/2007</span><a href="#/ogt/content/news/News176.complete">Inside of which is the code that you want to run</a></li>
					<li><span>08/10/2007</span><a href="#/ogt/content/news/News178.complete">Right when the page is loaded</a></li>
		</ul>

<h2>Ready? liScroll()</h2>
<p>Once you're done with your markup, simply call liScroll() like that:</p>
<pre class="syntax javascript">
$(function(){
	$(&quot;ul#ticker01&quot;).liScroll();
});
</pre>
<p>If you want your list to scroll faster or slower than the default value, modify the <em>travelocity</em> param</p>
<pre class="syntax javascript">
$(function(){
	$(&quot;ul#ticker02&quot;).liScroll({travelocity: 0.15});
});

</pre>
<p>Downloads:</p>
<ul>
	<li><a href="jquery.li-scroller.1.0.js">jquery.li-scroller.1.0.js</a></li>
	<li><a href="li-scroller.css">li-scroller.css</a></li>
</ul>
<p>... and enjoy!</p>
<span style="font-size: 0.7em; color: gray">2007 - 2009 Gian Carlo Mingati - Design and development for interactive media</span>
</div>
<div style="clear: left">&nbsp;</div>



<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-2064812-5");
pageTracker._initData();
pageTracker._trackPageview();
</script>

</body>
</html>c