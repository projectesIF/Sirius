<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" dir="{langdirection}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={charset}" />
	<meta name="description" content="{$metatags.description}" />
	<meta name="keywords" content="{$metatags.keywords}" />
	<meta name="robots" content="index, follow" />
	<meta name="author" content="{$modvars.ZConfig.sitename}" />
	<link rel="shortcut icon" href="{$imagepath}/favicon.ico" />
	<meta name="copyright" content="Copyright (c) 2008 by {$modvars.ZConfig.sitename}" />
	<meta name="generator" content="Zikula - http://zikula.org" />
	<meta http-equiv="X-UA-Compatible" content="chrome=1" />
	<title>{pagegetvar name='title'}</title>
	<link rel="stylesheet" type="text/css" href="{$stylepath}/style.css" media="projection,screen" />
	<link rel="stylesheet" type="text/css" href="{$stylepath}/print.css" media="print" />
	<link rel="stylesheet" type="text/css" href="{$stylepath}/color-{$color_theme}.css" />
        <link rel="stylesheet" type="text/css" href="{getbaseurl}filetheme.php?fileName={$stylesheet}&amp;type=css&amp;theme=IWcat2" />
	<link rel="alternate" title="{pagegetvar name='title'}" href="index.php?theme=rss" type="application/rss+xml" />
</head>

<body>



		<div id="theme_content">
		   <div id="z-maincontent">
			<table id="theme_table_content">
				<tr>
					<td id="theme_content_center">
						{*blockposition name=center*}
						{$maincontent}
					</td>
				</tr>
			</table>
		   </div>
		</div>

	
</body>
</html>

