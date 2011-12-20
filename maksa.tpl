
<style type="text/css">
    .maksa-block {
        margin: 0.5em 0;
        margin-left: 0.7em;

        border: 1px solid gray;
        display: block;
        padding: 0.6em;
        text-decoration: none;
        text-align: center;
    }
    .maksa-block .link-set a:hover {
        background-color: green;
    }
    .maksa-block .link-set a {
        border: none;
        display: block;
        float: left;
        padding: 5px;
        height: 60px;
    }

</style>
<div class="maksa-block">

    <div class="link-set">
        <a href="{$base_dir_ssl}modules/maksa/payment/payment.php?type=swedbank" title="{l s='Swedbank' mod='maksa'}">
            <img src="{$images_path}/swedbank.png" alt="swedbank">
        </a>
        <a href="{$base_dir_ssl}modules/maksa/payment/payment.php?type=seb" title="{l s='SEB' mod='maksa'}">
            <img src="{$images_path}/seb.gif" alt="seb">
        </a>
        <a href="{$base_dir_ssl}modules/maksa/payment/payment.php?type=nordea" title="{l s='Nordea bank' mod='maksa'}">
            <img src="{$images_path}/nordea.gif" alt="nordea">
        </a>
        <a href="{$base_dir_ssl}modules/maksa/payment/payment.php?type=krep" title="{l s='Krediidibank' mod='maksa'}">
            <img src="{$images_path}/krep.gif" alt="krep">
        </a>
    </div>

    <div style="padding-top: 10px; clear: both;">
        <span style="background: url({$logo}) no-repeat scroll left center transparent;line-height: 50px; clear: both; padding: 20px 40px;">
            <a href="https://maksa.ee" title="{l s='Maksa.ee site (Uues aknas)' mod='maksa'}" target="_blank">Maksa.ee</a>. {l s='Turvaline makse panga ülekandega' mod='maksa'}
        </span><br />
        <img id="loader-img" style="display: none;" src="{$images_path}/ajax-loader.gif" />
    </div>

    <div id="payment-form"></div>

</div>

<script type="text/javascript">
    BASEDIR = '{$base_dir_ssl}';
</script>
<script type="text/javascript" src="{$js_path}/payment.js"></script>