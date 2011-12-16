
<h1><img src="../logo.gif" /> Maksa.ee</h1>
<br />
<p id="maksa-loading-status">
    <img src="{$base_dir}img/loader.gif" />
    {l s='Please wait while your order is being processed...' mod='maksa'}
</p>
<script type="text/javascript">

var maksaWaiting = true;
function checkwaitingorder()
{ldelim}
    if (maksaWaiting) {
        $.ajax({ldelim}
            type:"POST",
            async:true,
            url:'{$base_dir}modules/maksa/payment/checkwaitingorder.php',
            data:'id_cart={$id_cart|intval}&id_module={$id_module|intval}&key={$key|escape}',
            success: function (r) {ldelim}
                var data = $.parseJSON(r);

                if (data.status == 'ok') {
                    $("#maksa-payment-order-id").val(data.id_order);
                    $("#maksa-result-div").show();
                    $("#maksa-payment-ok").show();
                    $("#maksa-loading-status").hide();
                    maksaWaiting = false;
                } else if(data.status == 'not_ok') {
                    $("#maksa-payment-order-id").val(data.id_order);
                    $("#maksa-result-div").show();
                    $("#maksa-payment-fail").show();
                    $("#maksa-loading-status").hide();
                    maksaWaiting = false;
                }
            {rdelim}
        {rdelim});

        setTimeout('checkwaitingorder()', 3000);
    }
{rdelim}
checkwaitingorder();
</script>

<div style="display: none" id="maksa-result-div">

    <div id="maksa-payment-ok" style="display: none">
        <table>
            <tr>
                <td>
                    <img src="../success.png"  alt="{l s='Payment success!' mod='maksa'}"/>
                </td>
                <td>
                    <span style="color: green; font-size: 26px;"> {l s='Payment success!' mod='maksa'} </span>
                </td>
            </tr>
        </table>

    </div>
    <div id="maksa-payment-fail" style="display: none">
        <table>
            <tr>
                <td>
                    <img src="../failure.png"  alt="{l s='Payment failure!' mod='maksa'}"/>
                </td>
                <td>
                    <span style="margin-left: 15px; color: red; font-size: 26px;"> {l s='Payment failure!' mod='maksa'} </span>
                </td>
            </tr>
        </table>

    </div>

    <p class="cart_navigation">
        <form action="{$maksa_link}">
            <input id="maksa-payment-order-id" type="hidden" name="id_order" value="" />
            <input type="submit" value="{l s='Go to order' mod='maksa'} &raquo;" class="exclusive_large" />
        </form>

    </p>

</div>