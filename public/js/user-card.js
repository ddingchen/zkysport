$(function() {
    $('.card').click(function() {
        var $elem = $(this);
        var cardNo = $elem.attr('data-cardno');
        var baseUrl = "/user/card/" + cardNo;
        if ($elem.hasClass('touming')) {
            $.get(baseUrl + "/active", function() {
                $elem.removeClass('touming').siblings().addClass('touming');
            });
        } else {
            window.location.href = baseUrl;
        }
    });
})
