(function ($) {
    'use strict';

    $(function () {
        var $select = $('#header-currency');

        if (!$select.length) {
            return;
        }

        $select.on('change', function () {
            var currency = $(this).val();
            var token = $('meta[name="csrf-token"]').attr('content');
            var $form = $('<form>', {
                method: 'POST',
                action: $select.data('switch-url'),
            });

            $form.append($('<input>', { type: 'hidden', name: '_token', value: token }));
            $form.append($('<input>', { type: 'hidden', name: 'currency', value: currency }));
            $('body').append($form);
            $form.trigger('submit');
        });
    });
})(jQuery);
