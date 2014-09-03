<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>QFinder - Sample - Standalone</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<link href="../sample.css" rel="stylesheet" type="text/css" />
	<style type="text/css">

		/* By defining QFinderFrame, you are able to customize the QFinder frame style */
		.QFinderFrame
		{
			border: solid 2px #e3e3c7;
			background-color: #f1f1e3;
		}

	</style>
	<script type="text/javascript">

// This is a sample function which is called when a file is selected in QFinder.
function ShowFileInfo( fileUrl, data )
{
	var msg = 'The selected URL is: ' + fileUrl + '\n\n';
	// Display additional information available in the "data" object.
	// For example, the size of a file (in KB) is available in the data["fileSize"] variable.
	if ( fileUrl != data['fileUrl'] )
		msg += 'File url: ' + data['fileUrl'] + '\n';
	msg += 'File size: ' + data['fileSize'] + 'KB\n';
	msg += 'Last modified: ' + data['fileDate'];

	alert( msg );
}

	</script>
</head>
<body>
	<h1 class="samples">
		QFinder - Sample - Standalone
	</h1>
	<div class="description">
		QFinder may be used in standalone mode inside any page, to create a repository
		manager with ease. You may define a custom JavaScript function to be called when
		an image is selected (double-clicked).</div>
	<p style="padding-left: 30px; padding-right: 30px;">
<?php

require_once '../../starter.php';

// You can use the "QFinder" class to render QFinder in a page:
$finder = new QFinder() ;
$finder->BasePath = '../../' ;	// The path for the installation of QFinder (default = "/qfinder/").
$finder->SelectFunction = 'ShowFileInfo' ;
// The default height is 400.
$finder->Height = 600;
$finder->Create() ;

// It can also be done in a single line, calling the "static"
// Create( basePath, width, height, selectFunction ) function:
// QFinder::CreateStatic( '../../', null, null, 'ShowFileInfo' ) ;

?>
	</p>
	<div id="footer">
		<hr />
	</div>
</body>
</html>