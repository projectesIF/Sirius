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
</head>
<body>
	<div id="theme_page_container">
		<div id="theme_header_top">
			<a href="http://www20.gencat.cat/portal/site/ensenyament" target="_blank">
                <span class="logodepart">
                    <img src="{$imagepath}/departament.png" alt="Departament d'Ensenyament" title="Departament d'Ensenyament" />
                </span>
            </a>
			<a href="http://www.xtec.cat" target="_blank">
                <span class="logoxtec">
                    <img src="{$imagepath}/xtec.png" alt="XTEC" title="XTEC" />
                </span>
            </a>
		</div>
<div id="theme_header" style="background:url({$imagepath}/logo_sirius.png) no-repeat top left;">
<div style="display:none">
			<h1><a href="{homepage}">{$modvars.ZConfig.sitename}</a></h1>
			<h2>{$modvars.ZConfig.slogan}</h2>
</div>
		</div>

		<div id="theme-top">
			{blockposition name=top}
		</div>

		<div id="theme_content">
		  <div id="z-maincontent">
			<table id="theme_table_content">
				<tr>
					<td id="theme_content_left">
				        {blockposition name=left}
				    </td>
					<td id="theme_content_center">
						{$maincontent}
					</td>
				</tr>
			</table>
		   </div>
		</div>

		<div id="theme_footer">
 <!--  Inici - Logo d'Àgora -->
<!--            <a href="http://agora.xtec.cat/"><img src="{$imagepath}/logo_agora.gif" alt="&Agrave;gora" /></a> -->
<!-- Final - Logo d'Àgora -->
            <a href="http://www.zikula.org"><img src="{$imagepath}/logo_zikula.gif" alt="Zikula" /></a>&nbsp;&nbsp;&nbsp;
			<a href="http://www.xtec.cat/web/formacio"><img src="{$imagepath}/logo_fp.png" alt="Formació del professorat" /></a>
		</div>

	</div>

	{*iwvhmenu*}

</body>
</html>

