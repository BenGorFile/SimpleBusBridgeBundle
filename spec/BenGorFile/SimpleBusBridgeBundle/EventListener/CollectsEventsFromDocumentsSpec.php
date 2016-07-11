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

namespace spec\BenGorFile\SimpleBusBridgeBundle\EventListener;

use BenGorFile\File\Domain\Model\File;
use BenGorFile\File\Domain\Model\FileAggregateRoot;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileUploaded;
use BenGorFile\SimpleBusBridgeBundle\EventListener\CollectsEventsFromDocuments;
use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use PhpSpec\ObjectBehavior;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

/**
 * Spec file of CollectsEventsFromDocuments class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CollectsEventsFromDocumentsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CollectsEventsFromDocuments::class);
    }

    function it_implements_doctrine_event_subscriber()
    {
        $this->shouldImplement(EventSubscriber::class);
    }

    function it_implements_contain_recorded_messages()
    {
        $this->shouldImplement(ContainsRecordedMessages::class);
    }

    function it_gets_subscribed_events()
    {
        $this->getSubscribedEvents()->shouldReturn([
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ]);
    }

    function it_post_persist(LifecycleEventArgs $event, FileAggregateRoot $user)
    {
        $event->getObject()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([]);

        $user->eraseEvents()->shouldBeCalled();

        $this->postPersist($event);
    }

    function it_post_update(LifecycleEventArgs $event, FileAggregateRoot $user)
    {
        $event->getObject()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([]);

        $user->eraseEvents()->shouldBeCalled();

        $this->postUpdate($event);
    }

    function it_post_remove(LifecycleEventArgs $event, FileAggregateRoot $user)
    {
        $event->getObject()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([]);

        $user->eraseEvents()->shouldBeCalled();

        $this->postRemove($event);
    }

    function it_manage_recorded_events(LifecycleEventArgs $event, FileAggregateRoot $user)
    {
        $event->getObject()->shouldBeCalled()->willReturn($user);
        $user->events()->shouldBeCalled()->willReturn([
            new FileUploaded(
                new FileId('file-id')
            ),
        ]);
        $user->eraseEvents()->shouldBeCalled();

        $this->postPersist($event);

        $this->recordedMessages()->shouldBeArray();
        $this->recordedMessages()->shouldHaveCount(1);
        $this->eraseMessages();
        $this->recordedMessages()->shouldHaveCount(0);
    }
}
