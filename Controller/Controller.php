<?php

namespace IP\StripeBundle\Controller;

/**
 * Class Controller
 * @package IP\StripeBundle\Controller
 */
class Controller
{
    /**
     * @param $response
     * @return bool
     */
    protected function paymentSucceded($response)
    {
        return isset($response) && 'succeeded' == $response['status'];
    }
}
