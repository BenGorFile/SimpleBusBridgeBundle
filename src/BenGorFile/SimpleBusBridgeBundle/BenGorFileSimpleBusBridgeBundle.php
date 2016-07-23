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
use BenGorFile\FileBundle\LoadableBundle;
use BenGorFile\SimpleBusBridgeBundle\DependencyInjection\Compiler\SimpleBusPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Simple bus bridge bundle kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileSimpleBusBridgeBundle extends Bundle implements LoadableBundle
{
    use DependentBenGorFileBundle;

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $this->checkDependencies(['BenGorFileBenGorFileBundle'], $container);
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SimpleBusPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
