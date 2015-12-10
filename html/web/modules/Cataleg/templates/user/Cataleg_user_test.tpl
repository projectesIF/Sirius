{pageaddvar name='javascript' value='javascript/livepipe/livepipe.combined.min.js'}
<script>
		document.observe('dom:loaded',function(){
			
	//Centered Window / Content on Page
	var centered = new Control.Window($(document.body).down('[href=#centered]'),{
		className: 'simple_window',
		closeOnClick: true
	});
	
	//Relative Window / Dynamic Content
	var relative = new Control.Window($(document.body).down('[href=#relative]'),{
		position: 'relative',
		className: 'simple_window',
		closeOnClick: true
	});
	relative.container.insert('This content was inserted with JavaScript.');
	
	//HoverBox
	var relative = new Control.Window($(document.body).down('[href=#hoverbox]'),{
		position: 'relative',
		hover: true,
		offsetLeft: 75,
		width: 175,
		className: 'tooltip'
	});
	
	//Relative Window / Content from Ajax
	var ajax = new Control.Window($(document.body).down('[href=/ajax_example]'),{
		className: 'simple_window',
		closeOnClick: 'container',
		offsetLeft: 150
	});
	
	//styled examples use the window factory for a shared set of behavior
	var window_factory = function(container,options){
		var window_header = new Element('div',{
			className: 'window_header'
		});
		var window_title = new Element('div',{
			className: 'window_title'
		});
		var window_close = new Element('div',{
			className: 'window_close'
		});
		var window_contents = new Element('div',{
			className: 'window_contents'
		});
		var w = new Control.Window(container,Object.extend({
			className: 'window',
			closeOnClick: window_close,
			draggable: window_header,
			insertRemoteContentAt: window_contents,
			afterOpen: function(){
				window_title.update(container.readAttribute('title'))
			}
		},options || {}));
		w.container.insert(window_header);
		window_header.insert(window_title);
		window_header.insert(window_close);
		w.container.insert(window_contents);
		return w;
	};
	
	var styled_window_one = window_factory($('styled_window_one'));
	
	var styled_window_two = window_factory($('styled_window_two'));
	
	//Modal Window
	var modal = new Control.Modal($('modal'),{
		overlayOpacity: 0.75,
		className: 'modal',
		fade: true,
                height: 400,
                width: 650,
                iframe: true,
                closeOnClick: true 
 
                
	});
	
	//ToolTip
	var tooltip = new Control.ToolTip($('tooltip'),'Windows can also act as tool tips.',{
		className: 'tooltip'
	});

		});
	</script>
	<style>
		
	#control_overlay {
		background-color:#000;
	}
	
	.modal {
		background-color:#fff;
		padding:10px;
		border:1px solid #333;
	}

	.tooltip {
		border:1px solid #000;
		background-color:#fff;
		height:25px;
		width:200px;
		font-family:"Lucida Grande",Verdana;
		font-size:10px;
		color:#333;
	}

	.simple_window {
		width:250px;
		height:50px;
		border:1px solid #000;
		background-color:#fff;
		padding:10px;
		text-align:left;
		font-family:"Lucida Grande",Verdana;
		font-size:12px;
		color:#333;
	}
	
	.window {
		background-image:url("/stylesheets/window_background.png");
		background-position:top left;
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		padding:10px;
		font-family:"Lucida Grande",Verdana;
		font-size:13px;
		font-weight:bold;
		color:#fff;
		text-align:center;
		min-width:150px;
		min-height:100px;
	}
	
	.window .window_contents {
		margin-top:10px;
		width:100%;
		height:100%;	
	}

	.window .window_header {
		text-align:center;
	}

	.window .window_title {
		margin-top:-7px;
		margin-bottom:7px;
		font-size:11px;
		cursor:move;
	}

	.window .window_close {
		display:block;
		position:absolute;
		top:4px;
		left:5px;
		height:13px;
		width:13px;
		background-image:url("/stylesheets/window_close.gif");
		cursor:pointer;
	    cursor:hand;
	}

	</style>


	<table cellpadding="0" cellspacing="0" width="100%" class="api_table">
	<thead>
		<tr><td>Name</td><td class="description" align="right">Options</td></tr>
	</thead>
	<tbody>
		
			<tr class="even"><td class="name first"><b><a href="#centered">Centered Window / Content on Page</a></b></td><td class="description example last">className: 'simple_window', closeOnClick: true</td></tr>
		
			<tr class="odd"><td class="name first"><b><a href="#relative">Relative Window / Dynamic Content</a></b></td><td class="description example last">position: 'relative', className: 'simple_window', closeOnClick: true</td></tr>
		
			<tr class="even"><td class="name first"><b><a href="#hoverbox">HoverBox</a></b></td><td class="description example last">position: 'relative', offsetLeft: 75, width: 175, hover: true, className: 'tooltip'</td></tr>
		
			<tr class="odd"><td class="name first"><b><a href="/ajax_example">Relative Window / Content from Ajax</a></b></td><td class="description example last">offsetLeft: 150, position: 'relative', className: 'simple_window', closeOnClick: 'container'</td></tr>
		
			<tr class="even"><td class="name first"><b><a href="/stylesheets/sample_images/tabs_example_1_big.jpg" id="styled_window_one" title="Piha Beach, New Zealand">Draggable / Styled Window One</a></b></td><td class="description example last">window_factory() options</td></tr>
		
			<tr class="odd"><td class="name first"><b><a href="/stylesheets/sample_images/tabs_example_2_big.jpg" id="styled_window_two" title="Pioneer Mountains, Idaho">Draggable / Styled Window Two</a></b></td><td class="description example last">window_factory() options</td></tr>
		
			<tr class="even"><td class="name first"><b><a href="/stylesheets/sample_images/tabs_example_3_big.jpg" id="modal">Modal Window</a></b></td><td class="description example last">fade: true, overlayOpacity: 0.75, className: 'modal'</td></tr>
		
			<tr class="odd"><td class="name first"><b><a href="#" id="tooltip">Tooltip</a></b></td><td class="description example last">className: 'tooltip'</td></tr>
		
	</tbody>
</table>