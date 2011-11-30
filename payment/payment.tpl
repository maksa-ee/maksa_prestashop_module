<h2>{l s='Order' mod='maksa'}</h2>

<h3>{l s='Maksa.ee Payment' mod='maksa'}</h3>

<form action="{$maksa_url}" method="post">
    <input type="hidden" name="signedRequest" value="{$signed_request}" />

    <p>Info here ....</p>

    <p class="cart_navigation">
        <a href="{$base_dir_ssl}order.php?step=3" class="button_large">{l s='Other payment methods' mod='maksa'}</a>
        <input type="submit" name="paymentSubmit" value="{l s='Pay with Maksa.ee' mod='maksa'}" class="exclusive_large" />
    </p>
</form>