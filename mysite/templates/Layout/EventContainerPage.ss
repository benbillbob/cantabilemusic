	<% if Menu(2) %>
		<% include BreadCrumbs %>
	<% end_if %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
		<div id='calendar'></div>
	</article>
		$Form
		$CommentsForm
</div>