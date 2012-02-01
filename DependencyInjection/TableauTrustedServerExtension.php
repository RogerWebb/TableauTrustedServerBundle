<?php

namespace Tableau\Bundle\TableauTrustedServerBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

class TableauTrustedServerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = array();

        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if(!isset($config['host']))
            throw new \InvalidArgumentException("Argument tableau.host must be set!");

        if(!isset($config['port']))
            $config['port'] = 8000; // The default Tableau Server port number (as of Tableau Server 7)

        if(!isset($config['protocol']))
            $config['protocol'] = 'http';

        $container->setParameter('tableau.trusted_server.host', $config['host']);
        $container->setParameter('tableau.trusted_server.port', $config['port']);
        $container->setParameter('tableau.trusted_server.protocol', $config['protocol']);
    }

    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }

    public function getNamespace()
    {
        return 'http://symfony.com/schema/dic/services/services-1.0.xsd';
    }
}
