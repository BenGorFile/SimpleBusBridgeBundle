<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\SimpleBusBridgeBundle;

use BenGorFile\FileBundle\DependentBenGorFileBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Simple bus and Doctrine ODM MongoDB bridge bundle kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileSimpleBusDoctrineODMMongoDBBridgeBundle extends Bundle
{
    use DependentBenGorFileBundle;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $this->checkDependencies([
            'BenGorFileBenGorFileBundle',
            'BenGorFileDoctrineODMMongoDBBridgeBundle',
            'DoctrineMongoDBBundle',
        ], $container);
    }
}
