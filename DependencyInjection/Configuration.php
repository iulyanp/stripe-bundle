<?php

namespace IP\StripeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ip_stripe');

        $rootNode->children()
            ->scalarNode('public_key')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('private_key')
                ->isRequired()
                ->cannotBeEmpty()
            ->end()
            ->scalarNode('amount')
                ->isRequired()
                ->cannotBeEmpty()
                ->defaultValue('1000')
            ->end()
            ->scalarNode('currency')
                ->isRequired()
                ->cannotBeEmpty()
                ->defaultValue('usd')
            ->end()
            ->arrayNode('templates')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('view_template')
                        ->defaultValue('IPStripeBundle:Stripe:charge.html.twig')
                    ->end()
                    ->scalarNode('success_template')
                        ->defaultValue('IPStripeBundle:Stripe:success.html.twig')
                    ->end()
                ->end()
            ->end()
            ->scalarNode('route_success')
                ->defaultValue('ip_stripe_success')
            ->end()
        ->end();

        return $treeBuilder;
    }
}
