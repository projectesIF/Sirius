<?php

// Get args from form
$fileName = $_REQUEST['fileName'];
$type = $_REQUEST['type'];

if (isset($_REQUEST['theme']) && !empty($_REQUEST['theme'])) {
    $theme = $_REQUEST['theme'];
} else {
    $theme = 'IWcat2';
}

// Security check to avoid access to parent directories
if (strpos($fileName, "../") !== false) {
    return false;
}

// Get path. Directory is hard coded because we'll only use this file to access here.
//$fileName = '../../zkdata/usu'.$schoolArray[1].'/data/theme/'.$fileName;
include_once ('config/config.php');
$folderPath = $ZConfig['System']['datadir'].'/style';
$fileName = $folderPath . '/' . $fileName;

// Check if file exists. If not set to default value.
if (!file_exists($fileName)) {
    if ($type == 'css') {
        $fileName = "themes/$theme/style/style.css";
    }
    if ($type == 'logo') {
        $fileName = "themes/$theme/images/logo.png";
    }
    if ($type == 'footer') {
        $fileName = "themes/$theme/templates/footer.htm";
    }
}

// Get file extension
$fileExtension = strtolower(substr(strrchr($fileName, "."), 1));
// Get MIME array
$ctypeArray = getMimetype($fileExtension);
// Get MIME type
$ctype = $ctypeArray['type'];

// Use the generated Content-Type (MIME type)
header("Content-Type: $ctype");

// Transfer the file
$chunksize = 1 * (1024 * 1024);
$buffer = '';

if (file_exists($fileName)) {
    $handle = fopen($fileName, 'rb');
    if ($handle === false) {
        return false;
    }
    while (!feof($handle)) {
        @set_time_limit(60 * 60);
        $buffer = fread($handle, $chunksize);
        echo $buffer;
        flush();
    }
    $status = fclose($handle);
} else {
    return false;
}

/**
 * get the list of information about file types based on extensions. 
 * @return an array with the list of information about file types based on extensions
 */
function getMimetype($extension) {
    $mimeTypes = array('xxx' => array('type' => 'document/unknown', 'icon' => 'unknown.gif'),
        '3gp' => array('type' => 'video/quicktime', 'icon' => 'video.gif'),
        'ai' => array('type' => 'application/postscript', 'icon' => 'image.gif'),
        'aif' => array('type' => 'audio/x-aiff', 'icon' => 'audio.gif'),
        'aiff' => array('type' => 'audio/x-aiff', 'icon' => 'audio.gif'),
        'aifc' => array('type' => 'audio/x-aiff', 'icon' => 'audio.gif'),
        'applescript' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'asc' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'asm' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'au' => array('type' => 'audio/au', 'icon' => 'audio.gif'),
        'avi' => array('type' => 'video/x-ms-wm', 'icon' => 'avi.gif'),
        'bmp' => array('type' => 'image/bmp', 'icon' => 'image.gif'),
        'c' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'cct' => array('type' => 'shockwave/director', 'icon' => 'flash.gif'),
        'cpp' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'cs' => array('type' => 'application/x-csh', 'icon' => 'text.gif'),
        'css' => array('type' => 'text/css', 'icon' => 'text.gif'),
        'dv' => array('type' => 'video/x-dv', 'icon' => 'video.gif'),
        'dmg' => array('type' => 'application/octet-stream', 'icon' => 'dmg.gif'),
        'doc' => array('type' => 'application/msword', 'icon' => 'word.gif'),
        'docx' => array('type' => 'application/msword', 'icon' => 'docx.gif'),
        'docm' => array('type' => 'application/msword', 'icon' => 'docm.gif'),
        'dotx' => array('type' => 'application/msword', 'icon' => 'dotx.gif'),
        'dcr' => array('type' => 'application/x-director', 'icon' => 'flash.gif'),
        'dif' => array('type' => 'video/x-dv', 'icon' => 'video.gif'),
        'dir' => array('type' => 'application/x-director', 'icon' => 'flash.gif'),
        'dxr' => array('type' => 'application/x-director', 'icon' => 'flash.gif'),
        'eps' => array('type' => 'application/postscript', 'icon' => 'pdf.gif'),
        'fdf' => array('type' => 'application/pdf', 'icon' => 'pdf.gif'),
        'flv' => array('type' => 'video/x-flv', 'icon' => 'video.gif'),
        'gif' => array('type' => 'image/gif', 'icon' => 'image.gif'),
        'gtar' => array('type' => 'application/x-gtar', 'icon' => 'zip.gif'),
        'tgz' => array('type' => 'application/g-zip', 'icon' => 'zip.gif'),
        'gz' => array('type' => 'application/g-zip', 'icon' => 'zip.gif'),
        'gzip' => array('type' => 'application/g-zip', 'icon' => 'zip.gif'),
        'h' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'hpp' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'hqx' => array('type' => 'application/mac-binhex40', 'icon' => 'zip.gif'),
        'htc' => array('type' => 'text/x-component', 'icon' => 'text.gif'),
        'html' => array('type' => 'text/html', 'icon' => 'html.gif'),
        'htm' => array('type' => 'text/html', 'icon' => 'html.gif'),
        'ico' => array('type' => 'image/vnd.microsoft.icon', 'icon' => 'image.gif'),
        'java' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'jcb' => array('type' => 'text/xml', 'icon' => 'jcb.gif'),
        'jcl' => array('type' => 'text/xml', 'icon' => 'jcl.gif'),
        'jcw' => array('type' => 'text/xml', 'icon' => 'jcw.gif'),
        'jmt' => array('type' => 'text/xml', 'icon' => 'jmt.gif'),
        'jmx' => array('type' => 'text/xml', 'icon' => 'jmx.gif'),
        'jpe' => array('type' => 'image/jpeg', 'icon' => 'image.gif'),
        'jpeg' => array('type' => 'image/jpeg', 'icon' => 'image.gif'),
        'jpg' => array('type' => 'image/jpeg', 'icon' => 'image.gif'),
        'jqz' => array('type' => 'text/xml', 'icon' => 'jqz.gif'),
        'js' => array('type' => 'application/x-javascript', 'icon' => 'text.gif'),
        'latex' => array('type' => 'application/x-latex', 'icon' => 'text.gif'),
        'm' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'mov' => array('type' => 'video/quicktime', 'icon' => 'video.gif'),
        'movie' => array('type' => 'video/x-sgi-movie', 'icon' => 'video.gif'),
        'm3u' => array('type' => 'audio/x-mpegurl', 'icon' => 'audio.gif'),
        'mp3' => array('type' => 'audio/mp3', 'icon' => 'audio.gif'),
        'mp4' => array('type' => 'video/mp4', 'icon' => 'video.gif'),
        'mpeg' => array('type' => 'video/mpeg', 'icon' => 'video.gif'),
        'mpe' => array('type' => 'video/mpeg', 'icon' => 'video.gif'),
        'mpg' => array('type' => 'video/mpeg', 'icon' => 'video.gif'),
        'odt' => array('type' => 'application/vnd.oasis.opendocument.text', 'icon' => 'odt.gif'),
        'ott' => array('type' => 'application/vnd.oasis.opendocument.text-template', 'icon' => 'odt.gif'),
        'oth' => array('type' => 'application/vnd.oasis.opendocument.text-web', 'icon' => 'odt.gif'),
        'odm' => array('type' => 'application/vnd.oasis.opendocument.text-master', 'icon' => 'odm.gif'),
        'odg' => array('type' => 'application/vnd.oasis.opendocument.graphics', 'icon' => 'odg.gif'),
        'otg' => array('type' => 'application/vnd.oasis.opendocument.graphics-template', 'icon' => 'odg.gif'),
        'odp' => array('type' => 'application/vnd.oasis.opendocument.presentation', 'icon' => 'odp.gif'),
        'otp' => array('type' => 'application/vnd.oasis.opendocument.presentation-template', 'icon' => 'odp.gif'),
        'ods' => array('type' => 'application/vnd.oasis.opendocument.spreadsheet', 'icon' => 'ods.gif'),
        'ots' => array('type' => 'application/vnd.oasis.opendocument.spreadsheet-template', 'icon' => 'ods.gif'),
        'odc' => array('type' => 'application/vnd.oasis.opendocument.chart', 'icon' => 'odc.gif'),
        'odf' => array('type' => 'application/vnd.oasis.opendocument.formula', 'icon' => 'odf.gif'),
        'odb' => array('type' => 'application/vnd.oasis.opendocument.database', 'icon' => 'odb.gif'),
        'odi' => array('type' => 'application/vnd.oasis.opendocument.image', 'icon' => 'odi.gif'),
        'pct' => array('type' => 'image/pict', 'icon' => 'image.gif'),
        'pdf' => array('type' => 'application/pdf', 'icon' => 'pdf.gif'),
        'php' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'pic' => array('type' => 'image/pict', 'icon' => 'image.gif'),
        'pict' => array('type' => 'image/pict', 'icon' => 'image.gif'),
        'png' => array('type' => 'image/png', 'icon' => 'image.gif'),
        'pps' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'powerpoint.gif'),
        'ppt' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'powerpoint.gif'),
        'pptx' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'pptx.gif'),
        'pptm' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'pptm.gif'),
        'potx' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'potx.gif'),
        'potm' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'potm.gif'),
        'ppam' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'ppam.gif'),
        'ppsx' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'ppsx.gif'),
        'ppsm' => array('type' => 'application/vnd.ms-powerpoint', 'icon' => 'ppsm.gif'),
        'ps' => array('type' => 'application/postscript', 'icon' => 'pdf.gif'),
        'qt' => array('type' => 'video/quicktime', 'icon' => 'video.gif'),
        'ra' => array('type' => 'audio/x-realaudio', 'icon' => 'audio.gif'),
        'ram' => array('type' => 'audio/x-pn-realaudio', 'icon' => 'audio.gif'),
        'rhb' => array('type' => 'text/xml', 'icon' => 'xml.gif'),
        'rm' => array('type' => 'audio/x-pn-realaudio', 'icon' => 'audio.gif'),
        'rtf' => array('type' => 'text/rtf', 'icon' => 'text.gif'),
        'rtx' => array('type' => 'text/richtext', 'icon' => 'text.gif'),
        'sh' => array('type' => 'application/x-sh', 'icon' => 'text.gif'),
        'sit' => array('type' => 'application/x-stuffit', 'icon' => 'zip.gif'),
        'smi' => array('type' => 'application/smil', 'icon' => 'text.gif'),
        'smil' => array('type' => 'application/smil', 'icon' => 'text.gif'),
        'sqt' => array('type' => 'text/xml', 'icon' => 'xml.gif'),
        'svg' => array('type' => 'image/svg+xml', 'icon' => 'image.gif'),
        'svgz' => array('type' => 'image/svg+xml', 'icon' => 'image.gif'),
        'swa' => array('type' => 'application/x-director', 'icon' => 'flash.gif'),
        'swf' => array('type' => 'application/x-shockwave-flash', 'icon' => 'flash.gif'),
        'swfl' => array('type' => 'application/x-shockwave-flash', 'icon' => 'flash.gif'),
        'sxw' => array('type' => 'application/vnd.sun.xml.writer', 'icon' => 'odt.gif'),
        'stw' => array('type' => 'application/vnd.sun.xml.writer.template', 'icon' => 'odt.gif'),
        'sxc' => array('type' => 'application/vnd.sun.xml.calc', 'icon' => 'odt.gif'),
        'stc' => array('type' => 'application/vnd.sun.xml.calc.template', 'icon' => 'odt.gif'),
        'sxd' => array('type' => 'application/vnd.sun.xml.draw', 'icon' => 'odt.gif'),
        'std' => array('type' => 'application/vnd.sun.xml.draw.template', 'icon' => 'odt.gif'),
        'sxi' => array('type' => 'application/vnd.sun.xml.impress', 'icon' => 'odt.gif'),
        'sti' => array('type' => 'application/vnd.sun.xml.impress.template', 'icon' => 'odt.gif'),
        'sxg' => array('type' => 'application/vnd.sun.xml.writer.global', 'icon' => 'odt.gif'),
        'sxm' => array('type' => 'application/vnd.sun.xml.math', 'icon' => 'odt.gif'),
        'tar' => array('type' => 'application/x-tar', 'icon' => 'zip.gif'),
        'tif' => array('type' => 'image/tiff', 'icon' => 'image.gif'),
        'tiff' => array('type' => 'image/tiff', 'icon' => 'image.gif'),
        'tex' => array('type' => 'application/x-tex', 'icon' => 'text.gif'),
        'texi' => array('type' => 'application/x-texinfo', 'icon' => 'text.gif'),
        'texinfo' => array('type' => 'application/x-texinfo', 'icon' => 'text.gif'),
        'tsv' => array('type' => 'text/tab-separated-values', 'icon' => 'text.gif'),
        'txt' => array('type' => 'text/plain', 'icon' => 'text.gif'),
        'wav' => array('type' => 'audio/wav', 'icon' => 'audio.gif'),
        'wmv' => array('type' => 'video/x-ms-wmv', 'icon' => 'avi.gif'),
        'asf' => array('type' => 'video/x-ms-asf', 'icon' => 'avi.gif'),
        'xdp' => array('type' => 'application/pdf', 'icon' => 'pdf.gif'),
        'xfd' => array('type' => 'application/pdf', 'icon' => 'pdf.gif'),
        'xfdf' => array('type' => 'application/pdf', 'icon' => 'pdf.gif'),
        'xls' => array('type' => 'application/vnd.ms-excel', 'icon' => 'excel.gif'),
        'xlsx' => array('type' => 'application/vnd.ms-excel', 'icon' => 'xlsx.gif'),
        'xlsm' => array('type' => 'application/vnd.ms-excel', 'icon' => 'xlsm.gif'),
        'xltx' => array('type' => 'application/vnd.ms-excel', 'icon' => 'xltx.gif'),
        'xltm' => array('type' => 'application/vnd.ms-excel', 'icon' => 'xltm.gif'),
        'xlsb' => array('type' => 'application/vnd.ms-excel', 'icon' => 'xlsb.gif'),
        'xlam' => array('type' => 'application/vnd.ms-excel', 'icon' => 'xlam.gif'),
        'xml' => array('type' => 'application/xml', 'icon' => 'xml.gif'),
        'xsl' => array('type' => 'text/xml', 'icon' => 'xml.gif'),
        'zip' => array('type' => 'application/zip', 'icon' => 'zip.gif'));

    $return = $mimeTypes[$extension];
    if ($return['type'] == '') {
        $return = $mimeTypes['xxx'];
    }
    return $return;
}
