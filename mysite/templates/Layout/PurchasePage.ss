<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
		<table>
		<% loop $Items().Sort('Sequence', ASC) %>
		<tr>
		<form action="$FormAction" method="post"><!-- SiteConfig -->
					<fieldset>
						<td>
						<h3>$ItemName</h3>
						<% if $HasTextOption %>
						<p>
						<input type="hidden" name="on0" value="$TextOptionCaption"><h5>$TextOptionCaption</h5>
						<input type="text" name="os0" maxlength="200" required>
						</p>
						<% end_if %>
						<% if $DiscountPercentage > "0" %>
							<p><input type="hidden" name="on1" value="Re enrol discount"></p>
							<p><label><input type="checkbox" name="os1" value="true">Returning student COVID credit (25% discount)</label></p>
							<input type="hidden" name="voucherDiscountRate" value="$DiscountPercentage"></p>
						<% end_if %>
						<% if $DiscountAmount > "0" %>
							<p><input type="hidden" name="on2" value="Use Creative Kids Voucher"></p>
							<p><label><input type="checkbox" name="os2" value="Use Creative Kids Voucher">Use $100 Creative Kids Voucher</label></p>
							<h5>Please forward the Creative Kids Voucher to <a href="mailto:natalie@cantabilemusic.com.au?subject=Creative%20Kids%20Voucher">natalie@cantabilemusic.com.au</a></h5>
							<input type="hidden" name="voucherDiscount" value="$DiscountAmount"></p>
						<% if $HasTextOption2 %>
							<p>
							<input type="hidden" name="on3" value="$TextOption2Caption"><h5>$TextOption2Caption</h5>
							<input type="text" name="os3" maxlength="200">
							</p>
						<% end_if %>
						<% end_if %>
						</td>
						<td>
						<h5>Full Price</h5>
						<h6>$Amount.Nice<br>inc GST</h6>
						<h7>Discounts will be applied when you press "Buy Now"</H7><br><br>
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
						</td>
					</fieldset>
		</form>	
		</tr>
		<% end_loop %>
		</table>
	</article>
		$Form
		$CommentsForm
		<div class="typography">
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>
</div>