/**
 * Created by russ on 11/26/16.
 */
jQuery(document).ready(function($) {
    console.log('Ready');

    $('button').click(function (e) {
        $('button').each(function () {
            var $btn = $(this);
            var newValue = (parseInt($btn.text(), 10) % 3) + 1;
            $btn.text(newValue);
        })
    })
});
