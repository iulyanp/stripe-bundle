<?php

namespace IP\StripeBundle\Services;

/**
 * Interface BillingInterface
 * @package IP\StripeBundle\Services
 */
interface BillingInterface
{
    /**
     * Charge the user
     *
     * @param array $data
     * @param       $token
     * @return mixed
     */
    public function charge(array $data, $token);
}
