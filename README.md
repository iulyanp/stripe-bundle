# IPStripeBundle

This is a symfony bundle that implements payment with Stripe library.

## Requirements

> The version `0.1.0` of the bundle requires that you'll have already installed AsseticBundle.

### Installation

#### Require the bundle 

```bash
$ composer require iulyanp/IPStripeBundle
```

#### Update AppKernel.php

```php
$bundles = [
    new IP\StripeBundle\IPStripeBundle(),
];
```

#### Configure the bundle

```yml
# parameters.yml
stripe_public_key: 'your_public_stripe_key'
stripe_private_key: 'your_private_stripe_key'
amount: 1000
currency: usd

# config.yml
ip_stripe:
    public_key: "%stripe_public_key%"
    private_key: "%stripe_private_key%"
    amount: "%amount%"
    currency: "%currency%"
    view_template: "%view_template%"
```

#### Configure where to redirect after success payment
By default after success the bundle will redirect you to `ip_stripe_success` route.
If you want to change this you just need to override the `route_success` parameter and set the correct route for you.

```yml
# parameters.yml
route_success: 'ip_stripe_success'

# config.yml
ip_stripe:
    route_success: "%route_success%"
```

#### Configure the success payment template
By default the success template is set to `'IPStripeBundle:Stripe:success.html.twig'` twig template.
To override it you just need to add `success_template` in your `parameters.yml` file.

```yml
# parameters.yml
success_template: 'IPStripeBundle:Stripe:success.html.twig'

# config.yml
ip_stripe:
    success_template: "%success_template%"
```

#### Change the stripe payment form template
By default the stripe payment form template is set to `'IPStripeBundle:Stripe:charge.html.twig'` twig template.
To override it you just need to add `view_template` in your `parameters.yml` file.

```yml
# parameters.yml
view_template: 'IPStripeBundle:Stripe:charge.html.twig'

# config.yml
ip_stripe:  
    view_template: "%view_template%"
```

#### Example: Overriding The Default layout.html.twig¶

It is highly recommended that you override the Resources/views/layout.html.twig template so that the pages provided by the IPStripeBundle have a similar look and feel to the rest of your application.
The following Twig template file is an example of a layout file that might be used to override the one provided by the bundle.

```twig
{% extends 'layout.html.twig' %}

{% block title %}My Demo Application{% endblock %}

{% block ip_stripe_content %}
    {% include 'your_brand_new_form.html.twig' %}
{% endblock %}
```