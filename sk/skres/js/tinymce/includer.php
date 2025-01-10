<!-- tinyMCE Advanced-->
<!--<script language="javascript" type="text/javascript" src="<?=SKRES_URL?>js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>-->


<? // set Editor-Styles
$MyEditorStyles=EDITOR_STYLES;
if(strlen($MyEditorStyles)<1) {
	$MyEditorStyles="Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1"; }

?>


<script language="javascript" type="text/javascript" src="<?=SITE_URL ?>index.php?action=resfile&filename=js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

<script language="javascript" type="text/javascript">
	elements = window.elements || "objtext";
	
	
	tinyMCE.init({
		theme : "advanced",
		elements : elements,
		mode : "exact",
		relative_urls : true,
		convert_urls : false,
		document_base_url : "<?=SITE_URL?>index.php",
		language : "de",
		content_css1 : "<?=SITE_URL?>index.php?action=resfile&filename=js/tinymce/examples/example_advanced.css",
		content_css : "<?=SITE_URL?>res/css/editor.css",
		extended_valid_elements : "a[href|target|name|class|id|rel],"
		+"ul[class|compact<compact|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown"
		  +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
		  +"|onmouseup|style|title|type],style[type],"
		  +"script[charset|defer|language|src|type],"
		  +"map[name|id],"
		  +"area[shape|coords|href|alt]",
		plugins : "table,advimage,preview,paste,zoom",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "seperator,pastetext,pasteword,selectall",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		external_image_list_url : "example_image_list.js",
		entity_encoding : "numeric",
		theme_advanced_styles : "<?=$MyEditorStyles?>", 
		themeurl : "<?=SKRES_URL?>js/tinymce/jscripts/tiny_mce/themes/advanced",
		debug : false
	});
	
	tinyMCE.baseURL="<?=SITE_URL?>index.php?action=resfile&filename=js/tinymce/jscripts/tiny_mce/";
	tinyMCE.baseURLd="<?=SKRES_URL?>js/tinymce/jscripts/tiny_mce/";
	tinyMCE.themeurl="<?=SKRES_URL?>js/tinymce/jscripts/tiny_mce/themes/advanced";
	// Custom event handler
	function myCustomExecCommandHandler(editor_id, elm, command, user_interface, value) {
		var linkElm, imageElm, inst;

		switch (command) {
			case "mceLink":
				inst = tinyMCE.getInstanceById(editor_id);
				linkElm = tinyMCE.getParentElement(inst.selection.getFocusElement(), "a");

				if (linkElm)
					alert("Link dialog has been overriden. Found link href: " + tinyMCE.getAttrib(linkElm, "href"));
				else
					alert("Link dialog has been overriden.");

				return true;

			case "mceImage":
				inst = tinyMCE.getInstanceById(editor_id);
				imageElm = tinyMCE.getParentElement(inst.selection.getFocusElement(), "img");

				if (imageElm)
					alert("Image dialog has been overriden. Found image src: " + tinyMCE.getAttrib(imageElm, "src"));
				else
					alert("Image dialog has been overriden.");

				return true;
		}

		return false; // Pass to next handler in chain
	}

	// Custom save callback, gets called when the contents is to be submitted
	function customSave(id, content) {
		//alert(id + "=" + content);
		document.editform.objtext.value=content;
		//alert(document.editform.objtext.value);
	}
	
	function initEditor(){
		var x;	
	}
</script>
<!-- /tinyMCE -->
