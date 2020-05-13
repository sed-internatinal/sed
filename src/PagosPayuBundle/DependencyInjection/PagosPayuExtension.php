<?php
/**
 * Created by PhpStorm.
 * User: iridian_dev5
 * Date: 12/05/2017
 * Time: 4:46 PM
 */

namespace PagosPayuBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PagosPayuExtension extends Extension
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->container = $container;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config as $key => $value) {
            $this->parseNode('pagos_payu.'.$key, $value);
        }

        $container->setParameter('pagos_payu', $config);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws \Exception
     */
    protected function parseNode($name, $value)
    {
        if (is_string($value)) {
            $this->set($name, $value);

            return;
        }
        if (is_integer($value)) {
            $this->set($name, $value);

            return;
        }
        if (is_array($value)) {
            foreach ($value as $newKey => $newValue) {
                $this->parseNode($name.'.'.$newKey, $newValue);
            }

            return;
        }
        if (is_bool($value)) {
            $this->set($name, $value);

            return;
        }
        throw new \Exception(gettype($value).' not supported');
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    protected function set($key, $value)
    {
        $this->container->setParameter($key, $value);
    }
}