<?php

namespace IP\StripeBundle\Services;

use IP\StripeBundle\Exception\StripeException;
use Stripe\Charge;
use Stripe\Error\Api;
use Stripe\Error\Authentication;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Stripe;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class StripeBilling
 * @package IP\StripeBundle\Services
 */
class StripeBilling implements BillingInterface
{
    /**
     * @param $stripeParameters
     */
    public function __construct($stripeParameters)
    {
        Stripe::setApiKey($stripeParameters['private_key']);
    }

    /**
     * @param array $data
     * @param       $token
     * @return Charge
     * @throws StripeException
     */
    public function charge(array $data, $token)
    {
        try {
            return Charge::create([
                "amount"      => $data['amount'],
                "currency"    => $data['currency'],
                "source"      => $token,
                "description" => $data['email']
            ]);
        } catch (Card $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            $this->logger->error('Stripe error: ' . $err['type'] . ': ' . $err['code'] . ': ' . $err['message']);
            $error = $e->getMessage();
        } catch (InvalidRequest $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            $this->logger->error('Stripe error: ' . $err['type'] . ': ' . $err['message']);
            $error = $err['message'];
        } catch (Authentication $e) {
            $body = $e->getJsonBody();
            $this->logger->error('Stripe error: API key rejected!');
            $error = 'Payment processor API key error. '. $e->getMessage();
        } catch (Api $e) {
            $this->logger->error('Stripe error: Stripe could not be reached.');
            $error = 'Network communication with payment processor failed, try again later. '. $e->getMessage();
        } catch (Exception $e) {
            $this->logger->error('Stripe error: Unknown error. '.$e->getMessage());
            $error = 'There was an error, try again later. '. $e->getMessage();
        }

        if ($error !== null) {
            // an error is always a string
            throw new StripeException($error);
        }
    }

    /**
     * @param Logger $logger
     * @return Logger
     */
    public function setLogger(Logger $logger)
    {
        return $this->logger = $logger;
    }
}
