<?php

namespace IP\StripeBundle\Controller;

use IP\StripeBundle\Exception\StripeException;
use IP\StripeBundle\Form\StripeType;
use IP\StripeBundle\Services\BillingInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends Controller
{
    /** @var BillingInterface */
    private $stripe;

    /** @var FormFactory */
    private $formFactory;

    /** @var array */
    private $stripeParameters;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var TwigEngine */
    private $templating;

    /** @var Session */
    private $session;

    /**
     * StripeController constructor.
     * @param BillingInterface      $stripe
     * @param UrlGeneratorInterface $urlGenerator
     * @param FormFactory           $formFactory
     * @param Session               $session
     * @param TwigEngine            $templating
     * @param                       $stripeParameters
     */
    public function __construct(
        BillingInterface $stripe,
        UrlGeneratorInterface $urlGenerator,
        FormFactory $formFactory,
        Session $session,
        TwigEngine $templating,
        $stripeParameters
    )
    {
        $this->stripe = $stripe;
        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->templating = $templating;
        $this->stripeParameters = $stripeParameters;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function charge(Request $request)
    {
        $form = $this->formFactory->create(StripeType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $formData = $form->getData();

            try {
                $charge = $this->stripe->charge(
                    [
                        'amount'   => $this->stripeParameters['amount'],
                        'currency' => $this->stripeParameters['currency'],
                        'email'    => $formData['email'],
                    ],
                    $formData['token']
                );
                $response = $charge->__toArray();
            } catch (StripeException $e) {
                $this->addFlash('danger', $e->getMessage());

                return new RedirectResponse($this->urlGenerator->generate('ip_stripe_charge'));
            }

            if ($this->paymentSucceded($response)) {
                $this->addFlash('success', 'Thank you! Your payment was successfully made.');

                return new RedirectResponse($this->urlGenerator->generate(
                    $this->stripeParameters['route_success'], [
                        'id'       => $response['id'],
                        'charged'  => $response['amount'],
                        'created'  => $response['created'],
                        'currency' => $response['currency']
                    ]
                ));
            }
        }

        return $this->templating->renderResponse(
            $this->stripeParameters['view_template'],
            [
                'stripe_form'       => $form->createView(),
                'stripe_public_key' => $this->stripeParameters['public_key']
            ]
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success(Request $request)
    {
        $id = $request->get('id');
        $charged = $request->get('charged');
        $created = $request->get('created');
        $currency = $request->get('currency');

        return $this->templating->renderResponse(
            $this->stripeParameters['success_template'],
            [
                'id'       => $id,
                'charged'  => $charged,
                'created'  => $created,
                'currency' => $currency
            ]
        );
    }

    /**
     * @param $type
     * @param $message
     * @return mixed
     */
    protected function addFlash($type, $message)
    {
        return $this->session->getFlashBag()->add($type, $message);
    }
}
