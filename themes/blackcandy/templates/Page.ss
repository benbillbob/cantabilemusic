<!DOCTYPE html>

<html lang="$ContentLocale">
  <head>
		<% base_tag %>
		<title><% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %> &raquo; $SiteConfig.Title</title>
		$MetaTags(false)
		<link rel="shortcut icon" href="/favicon.ico" />
		
		<% require themedCSS(layout) %> 
		<% require themedCSS(typography) %> 
		<% require themedCSS(form) %> 
		
		<!--[if IE 6]>
			<style type="text/css">
			 @import url(themes/blackcandy/css/ie6.css);
			</style> 
		<![endif]-->
	</head>
<body>
	<div id="BgContainer">
		<div id="Container">
			<div id="Header">
				$SearchForm
				<center><img src="themes/blackcandy/images/headersml.png" /></center>
		    	<center><h3>$SiteConfig.Tagline</h3></center>
			<center><iframe src="http://www.facebook.com/plugins/like.php?href=http://www.facebook.com/pages/Sutherland-Shire-Childrens-Choir-Cantabile-Music/179419121060"
        			scrolling="no" frameborder="0"
        			style="border:none; width:450px; height:80px"></iframe></center>
			</div>
			<div id="Navigation">
				<% include Navigation %>
		  	</div>
	  	
		  	<div class="clear"></div>
		
			<div id="Layout">
			  $Layout
			</div>
		
		   <div class="clear"></div>
		</div>
		<div id="Footer">
			<% include Footer %>
		</div> 
	</div>
</body>
</html>