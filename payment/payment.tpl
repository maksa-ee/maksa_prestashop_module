{*<h2>{l s='Order' mod='maksa'}</h2>*}

{*<h3>{l s='Maksa.ee Payment' mod='maksa'}</h3>*}

<form id="maksa-pay-form" action="{$maksa_url}" method="post">
    <input type="hidden" name="bank" value="{$bank}" />
    <input type="hidden" name="signedRequest" value="{$signed_request}" />

    {*<p>Connecting to maksa.ee ...</p>*}

    {*<script type="text/javascript">*}
        {*$('#maksa-pay-form').submit();*}
    {*</script>*}
</form>