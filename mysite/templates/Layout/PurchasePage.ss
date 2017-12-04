<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content aaa</div>
		<% loop $Items().Sort('Sequence', ASC) %>
		<form action="$FormAction" method="post"><!-- SiteConfig -->
					<fieldset>
						<p>$ItemName</p>
						<% if $HasTextOption %>
						<p>
						<input type="hidden" name="on0" value="Child's Name">Child's Name<br>
						<input type="text" name="os0" maxlength="200" required>
						</p>
						<% end_if %>
						<h4>$Amount.Nice inc GST</h4>				
						<input type="hidden" name="cmd" value="_xclick">
						<input type="hidden" name="business" value="$Business()" /><!-- SiteConfig -->
						<input type="hidden" name="item_name" value="$ItemName" /><!-- Shortcode -->
						<input type="hidden" name="item_number" value="$ItemNumber"><!-- Shortcode -->
						<input type="hidden" name="quantity" value="1"><!-- Shortcode -->
						<input type="hidden" name="amount" value="$Price" /><!-- Shortcode -->
						<input type="hidden" name="tax" value="$Tax" /><!-- Shortcode -->
						<input type="hidden" name="currency_code" value="$CurrencyCode()" /><!-- SiteConfig -->
						<input type="hidden" name="return" value="$ReturnUrl()" /><!-- SiteConfig -->
						<input type="hidden" name="cancel_return" value="$CancelUrl()" /><!-- SiteConfig -->
						<input type="submit" name="submitButton" value="$ButtonText" class="button" /><!-- Shortcode -->
					</fieldset>
		</form>	
		<% end_loop %>
	</article>
		$Form
		$CommentsForm
		<div class="typography">
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>
</div>