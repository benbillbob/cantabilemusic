<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<% with $Event %>
		<h1>$Title</h1>
		<div class="content">$ParsedContent</div>
		<p>Online Ticket sales are closed. Tickets available at the box office.</p>
		<% end_with %>
	</article>
		$Form
		$CommentsForm
		<div class="typography">
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>
</div>