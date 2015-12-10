<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" dir="{langdirection}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>{title}</title>
        <meta name="description" content="{slogan}" />
        <meta name="keywords" content="{keywords}" />
        <link rel="stylesheet" type="text/css" href="style/core.css" media="print,projection,screen" />
        {browserhack condition="if IE"}<link rel="stylesheet" type="text/css" href="styles/core_iehacks.css" media="print,projection,screen" />{/browserhack}
        <link rel="stylesheet" type="text/css" href="{$stylepath}/style.css" media="print,projection,screen" />
        <link rel="stylesheet" type="text/css" href="{$stylepath}/print.css" media="print" />
        <link rel="stylesheet" href="modules/Llicencies/style/xtec_style.css" type="text/css" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1" />
        <script type="text/javascript">
            document.location.entrypoint = "{{homepage}}";
            document.location.pnbaseURL = "{{$baseurl}}";
            document.location.ajaxtimeout = {{$modvars.ZConfig.ajaxtimeout}};
            if (typeof (Zikula) == 'undefined')
                {Zikula = {};}
            Zikula.Config = {"entrypoint":"{{homepage}}","baseURL":"{{$baseurl}}","baseURI":"{{getbaseuri}}","ajaxtimeout":"{{$modvars.ZConfig.ajaxtimeout}}","lang":"{{lang}}"}
        </script>
        <script type="text/javascript" src="javascript/ajax/proto_scriptaculous.combined.min.js"></script>
        <script type="text/javascript" src="javascript/helpers/Zikula.js"></script>
        <script type="text/javascript" src="modules/Llicencies/javascript/Llicencies.js"></script>
        {if $jquery}
            <script type="text/javascript" src="{$jquery}"></script>
            <script type="text/javascript" src="javascript/jquery/noconflict.js"></script>
        {/if}
    </head>
    <body>
        <h3 class="z-left">{gt text="Cercador de treballs elaborats amb llicències d'estudis" }</h3><br />
        {insert name='getstatusmsg'}   
        <div id="wait"></div>