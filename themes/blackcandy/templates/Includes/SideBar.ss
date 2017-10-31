<div id="Sidebar" class="typography">
	<div class="sidebarBox">
		<h3>
			<% control Level(1) %>
				$Title
			<% end_control %>
  		</h3>
  		<ul id="Menu2">
		  	<% control Menu(2) %>
			  	    <li class="$LinkingMode"><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levela"><span><em>$MenuTitle.XML</em></span></a>
	  			<% if LinkOrSection = section %>
	  				<% if Children %>
						<ul class="sub">
							<li>
								<ul class="roundWhite">
									<% control Children %>
										<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode levelb"><span><em>$MenuTitle.XML</em></span></a></li>
									<% end_control %>
								</ul>
							</li>
						</ul>
			 		 <% end_if %>
				<% end_if %> 
			</li> 
  			<% end_control %>
  		</ul>
		<div class="clear"></div>
	</div>
	<div class="sidebarBottom"></div>
</div>
  