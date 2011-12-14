
<style type="text/css">
    p.maksa-block a img {
        margin: 5px;
    }
    p.maksa-block a {
        border: 1px solid gray;
        display: block;
        padding: 0.6em;
        text-decoration: none;
        text-align: center;
    }
</style>
<p class="payment_module maksa-block">

    <a href="{$base_dir_ssl}modules/maksa/payment/payment.php" title="{l s='Pay with Maksa.ee' mod='maksa'}">

        <img src="{$images_path}/swedbank.png" alt="swedbank">
        <img src="{$images_path}/seb.gif" alt="seb">
        <img src="{$images_path}/nordea.gif" alt="nordea">
        <img src="{$images_path}/krep.gif" alt="nordea">

        <img src="{$logo}" alt="maksa.ee">

        {l s='Maksa.ee. Turvaline makse panga ülekandega' mod='maksa'}
    </a>
</p>