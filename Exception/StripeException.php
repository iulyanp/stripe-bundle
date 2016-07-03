<?php

namespace IP\StripeBundle\Exception;

/**
 * Class PaymentException
 * @package IP\StripeBundle\Exception
 */
class StripeException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string     $message The internal exception message
     * @param int        $code The internal exception code
     * @param \Exception $previous The previous exception
     */
    public function __construct($message = null, $code = 404, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}