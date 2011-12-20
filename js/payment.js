/**
 * Date: 12/20/11
 * Time: 10:13 AM
 * @author Alex Rudakov <alexandr.rudakov@modera.net>
 */

$(function(){
    $(".link-set a").click(function(){

        $("#loader-img").show();

        var link = $(this);

        jQuery.ajax({
            type:'POST',
            url: link.attr('href'),
            success: function(data){
                $('#payment-form').html(data);
                $('#maksa-pay-form').submit();
            },
            error:function(XMLHttpRequest,textStatus,errorThrown){
                alert("Error: " + errorThrown)
            }});

        return false;
    });
});