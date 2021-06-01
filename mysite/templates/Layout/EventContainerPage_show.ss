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
		<% if $OnSaleNow() %>
		<% loop $EventTicketTypes().Sort('Sequence', ASC) %>
			$ItemName
			<h4>$Amount.Nice inc GST
				<form action="$FormAction" method="post"><!-- SiteConfig -->
					<fieldset>
						<input type="hidden" name="cmd" value="_xclick"/>
						<input type="hidden" name="business" value="$Business()" /><!-- SiteConfig -->
						<input type="hidden" name="item_name" value="$ItemName" /><!-- Shortcode -->
						<input type="hidden" name="item_number" value="$ItemNumber"/><!-- Shortcode -->
						<input type="hidden" name="quantity" value="1"/><!-- Shortcode -->
						<input type="hidden" name="amount" value="$Price" /><!-- Shortcode -->
						<input type="hidden" name="tax" value="$Tax" /><!-- Shortcode -->
						<input id="Custom" type="hidden" name="custom" /><!-- Runtime -->
						<input type="hidden" name="currency_code" value="$CurrencyCode()" /><!-- SiteConfig -->
						<input type="hidden" name="return" value="$ReturnUrl()" /><!-- SiteConfig -->
						<input type="hidden" name="cancel_return" value="$CancelUrl()" /><!-- SiteConfig -->
						<% if $DiscountAmount > "0" %>
							<p><input type="hidden" name="on0" value="Use Discover NSW Voucher"/></p>
							<p><label><input type="checkbox" name="os0" value="Use Discover NSW Voucher">Use Discover NSW Voucher</input></label></p>
							<% if $HasTextOption2 %>
								<p>
								<input type="hidden" name="on1" value="$TextOption2Caption"><h5>$TextOption2Caption</h5></input>
								<input type="text" name="os1" maxlength="200"/>
								</p>
							<% end_if %>
							<h5>Please also forward the Discover NSW Voucher to <a href="mailto:natalie@cantabilemusic.com.au?subject=Discover%20NSW%20Voucher">natalie@cantabilemusic.com.au</a></h5>
							<input type="hidden" name="voucherDiscount" value="$DiscountAmount"/></p>
						<% end_if %>
						<input type="submit" name="submitButton" value="$ButtonText" class="button" /><!-- Shortcode -->
						
					</fieldset>
				</form>	
</h4>				
		<% end_loop %>
		<% end_if %>
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