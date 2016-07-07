<?php

namespace Abacaphiliac\DoctrineORMDiagnosticsModule;

use DoctrineModule\Component\Console\Input\RequestInput;
use DoctrineModule\Component\Console\Output\PropertyOutput;
use Symfony\Component\Console\Command\Command;
use Zend\Console\Request;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractCheckCommandFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CheckCommand
     * @throws \UnexpectedValueException
     * @throws \Zend\Console\Exception\RuntimeException
     * @throws \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $command = $serviceLocator->get($this->getCommandServiceName());
        if (!$command instanceof Command) {
            throw new \UnexpectedValueException(sprintf(
                'Expected type [%s]. Actual type [%s].',
                Command::class,
                is_object($command) ? get_class($command) : gettype($command)
            ));
        }
        
        $input = new RequestInput(new Request(array()));
        
        $output = new PropertyOutput();
        
        return new CheckCommand($command, $input, $output);
    }

    /**
     * @return string
     */
    abstract public function getCommandServiceName();
}
