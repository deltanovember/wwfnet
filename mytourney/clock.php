<html>
<head>
<title>JavaScript Clock</title>

<script type="text/javascript">
<!--

function loadTime ()
{

http_request = false;

if(window.XMLHttpRequest)
{

// Mozilla, Safari,...
http_request = new XMLHttpRequest();

if(http_request.overrideMimeType)
{

// set type accordingly to anticipated content type

//http_request.overrideMimeType('text/xml');

http_request.overrideMimeType('text/html');

}

}
else if(window.ActiveXObject)
{ // IE
try
{

http_request = new ActiveXObject("Msxml2.XMLHTTP");

}
catch (e)
{

try
{

http_request = new ActiveXObject("Microsoft.XMLHTTP");

}
catch(e)
{

}
}
}

var parameters = "time=";

http_request.onreadystatechange = alertContents;

http_request.open('POST', 'time.php', true);

http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

http_request.setRequestHeader("Content-length", parameters.length);

http_request.setRequestHeader("Connection", "close");

http_request.send(parameters);

}


function alertContents()
{
if (http_request.readyState == 4)
{

if (http_request.status == 200)
{

result = http_request.responseText;

document.getElementById('clock').innerHTML = result;

}

}
}

// -->
</script>

<style type="text/css">
<!--
body {
background:#CCCCFF;
text-align:center;
}

span {
color:000;
}

input { maring-right:5px;}

-->
</style>
</head>
<body onload="setInterval('loadTime()', 200);">

<span id="clock"> </span><br>

</body>
</html>