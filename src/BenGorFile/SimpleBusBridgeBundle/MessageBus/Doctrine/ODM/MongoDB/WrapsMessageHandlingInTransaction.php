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

namespace BenGorFile\SimpleBusBridgeBundle\MessageBus\Doctrine\ODM\MongoDB;

use Doctrine\Common\Persistence\ManagerRegistry;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

/**
 * Transactional middleware for Doctrine ODM MongoDB.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class WrapsMessageHandlingInTransaction implements MessageBusMiddleware
{
    /**
     * The document manager name.
     *
     * @var string
     */
    private $documentManagerName;

    /**
     * The manager registry.
     *
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry     The manager registry
     * @param string          $documentManagerName The document manager name
     */
    public function __construct(ManagerRegistry $managerRegistry, $documentManagerName)
    {
        $this->managerRegistry = $managerRegistry;
        $this->documentManagerName = $documentManagerName;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $documentManager = $this->managerRegistry->getManager($this->documentManagerName);

        call_user_func($next, $message);
        $documentManager->flush();
    }
}
