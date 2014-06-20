<html>
<head>
<title>Ошибка</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			12px;
color:				#000;
}

#content  {
border:				#999 1px solid;
background-color:	#fff;
padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#990000;
margin:				0 0 4px 0;
}

#errorMessage_text {

}
</style>

<link href="/css/style.css type="text/css" rel="stylesheet"/>
    <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="image_src" href="http://rynda.org/images/rynda.png" />
</head>
<body>
	<div id="errPage_contentContainer" class="g800 ml40 rounded_all pa20">
		<div id="content">
			<h1>Если Вы видите эту страницу, то, кажется, у нас ошибка. А именно:</h1>
			<div id="errorMessage_text"><?php echo $message; ?></div>
		</div>
	</div>
</body>
</html>