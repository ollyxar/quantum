<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>QFinder - Sample - FCKeditor Integration</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<link href="../sample.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<h1 class="samples">
		QFinder - Sample - FCKeditor Integration
	</h1>
	<div class="description">
		QFinder can be easily integrated with FCKeditor. Try it now, by clicking
		the "Image" or "Link" icons and then the "<strong>Browse Server</strong>" button.</div>
	<p>
<?php

include_once '../../../fckeditor/fckeditor.php' ;
require_once '../../starter.php';

// This is a check for the FCKeditor class. If not defined, the paths must be checked.
if ( !class_exists( 'FCKeditor' ) )
{
	echo
		'<br><strong><span class="error">Error</span>: FCKeditor not found</strong>. ' .
		'This sample assumes that FCKeditor (not included with QFinder) is installed in ' .
		'the "fckeditor" sibling folder of the QFinder installation folder. If you have it installed in ' .
		'a different place, just edit this file, changing the wrong paths in the include ' .
		'(line 31) and the "BasePath" values (line 47).' ;
}
else
{
	$fckeditor = new FCKeditor( 'FCKeditor1' ) ;
	$fckeditor->BasePath	= '../../../fckeditor/' ;
	$fckeditor->Value		= '<p>Just click the <b>Image</b> or <b>Link</b> button, and then <b>&quot;Browse Server&quot;</b>.</p>' ;

	// Just call QFinder::SetupFCKeditor before calling Create() or CreateHtml()
	// in FCKeditor. The second parameter (optional), is the path for the
	// QFinder installation (default = "/qfinder/").
	QFinder::SetupFCKeditor( $fckeditor, '../../' ) ;

	$fckeditor->Create() ;
}

?>
	</p>
	<div id="footer">
		<hr />
	</div>
</body>
</html>
