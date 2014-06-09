/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Scripts related to the Link dialog window (see fck_link.html).
 */

var dialog	= window.parent ;
var oEditor = dialog.InnerDialogLoaded() ;

var FCK			= oEditor.FCK ;
var FCKLang		= oEditor.FCKLang ;
var FCKConfig	= oEditor.FCKConfig ;
var FCKRegexLib	= oEditor.FCKRegexLib ;
var FCKTools	= oEditor.FCKTools ;

//#### Dialog Tabs

// Set the dialog tabs.
dialog.AddTab( 'Upload', FCKLang.DlgLnkUpload ) ;

// Function called when a dialog tag is selected.
function OnDialogTabChange( tabCode )
{
	ShowE('divUpload'	, ( tabCode == 'Upload' ) ) ;

	dialog.SetAutoSize( true ) ;
}

// oLink: The actual selected link in the editor. 
var oLink = dialog.Selection.GetSelection().MoveToAncestorNode( 'A' ) ;
if ( oLink )
	FCK.Selection.SelectNode( oLink ) ;

window.onload = function()
{
	// Translate the dialog box texts.
	oEditor.FCKLanguageManager.TranslatePage(document) ;

	// Show the initial dialog content.
	GetE('divUpload').style.display = '' ;

	// Set the actual uploader URL.
	if ( FCKConfig.FilesUploadURL )
		GetE('frmUpload').action = FCKConfig.FilesUploadURL ;

	// Activate the "OK" button.
	dialog.SetOkButton( true ) ;
}

//#### The OK button was hit.
function Ok()
{
	oEditor.FCKUndo.SaveUndoStep() ;

	return true ;
}


function BrowseServer()
{
	OpenFileBrowser( FCKConfig.LinkBrowserURL, FCKConfig.LinkBrowserWindowWidth, FCKConfig.LinkBrowserWindowHeight ) ;
}

function SetUrl( url )
{
	dialog.SetSelectedTab( 'Upload' ) ;
}

function OnUploadCompleted( errorNumber, fileUrl, fileName, customMsg )
{
	// Remove animation
	window.parent.Throbber.Hide() ;
	GetE( 'divUpload' ).style.display  = '' ;

	switch ( errorNumber )
	{
		case 0 :	// No errors
			alert( 'Your file has been successfully uploaded' ) ;
			break ;
		case 1 :	// Custom error
			alert( customMsg ) ;
			return ;
		case 101 :	// Custom warning
			alert( customMsg ) ;
			break ;
		case 201 :
			alert( 'A file with the same name is already available. The uploaded file has been renamed to "' + fileName + '"' ) ;
			break ;
		case 202 :
			alert( 'Invalid file type' ) ;
			return ;
		case 203 :
			alert( "Security error. You probably don't have enough permissions to upload. Please check your server." ) ;
			return ;
		case 500 :
			alert( 'The connector is disabled' ) ;
			break ;
		default :
			alert( 'Error on file upload. Error number: ' + errorNumber ) ;
			return ;
	}

	SetUrl( fileUrl ) ;
	GetE('frmUpload').reset() ;
}

var oUploadAllowedExtRegex	= new RegExp( FCKConfig.LinkUploadAllowedExtensions, 'i' ) ;
var oUploadDeniedExtRegex	= new RegExp( FCKConfig.LinkUploadDeniedExtensions, 'i' ) ;

function CheckUpload()
{
    var sFile;
    var sFile_count = 0;

    for (var i = 0; i <= 4; i++)
    {
        var sFile = GetE('txtUploadFile_' + i).value ;

        if ( sFile.length == 0 )
        {
            continue;
        }

        sFile_count ++;

//        if ( ( FCKConfig.LinkUploadAllowedExtensions.length > 0 && !oUploadAllowedExtRegex.test( sFile ) ) ||
//            ( FCKConfig.LinkUploadDeniedExtensions.length > 0 && oUploadDeniedExtRegex.test( sFile ) ) )
//        {
//            OnUploadCompleted( 202 ) ;
//            return false ;
//        }
    }

    if ( sFile_count == 0 )
    {
        alert( 'Please select a file to upload' ) ;
        return false ;
    }

    // Show animation
    window.parent.Throbber.Show( 100 ) ;
    GetE( 'divUpload' ).style.display  = 'none' ;

	return true ;
}