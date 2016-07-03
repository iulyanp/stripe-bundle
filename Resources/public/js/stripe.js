(function () {
    var StripeBilling = {
        init: function () {
            this.form = $('#payment-form');
            this.submitButton = this.form.find('#submit-payment');

            this.bindEvents();
        },
        bindEvents: function() {
            this.form.on('submit', $.proxy(this.sendToken, this));
        },
        sendToken: function(event) {
            this.submitButton.addClass('active disabled');

            Stripe.card.createToken(this.form, $.proxy(this.stripeResponseHandler, this));

            event.preventDefault();
        },
        stripeResponseHandler: function (status, response) {

            if (response.error) {
                this.submitButton.removeClass('active disabled');
                return this.form.find('#payment-errors').removeClass('hidden').find('span').text(response.error.message);
            }

            this.form.find('#payment-errors').hide().find('span').text('');
            this.token = response.id;

            this.form.find('#stripe_token').val(this.token);

            this.form[0].submit();
        }
    };

    StripeBilling.init();
})();
