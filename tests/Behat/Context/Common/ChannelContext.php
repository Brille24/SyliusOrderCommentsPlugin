<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Context\Common;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class ChannelContext implements Context
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Given /^(this channel) has "([^"]+)" as a contact email$/
     */
    public function thisChannelHasAsAContactEmail(ChannelInterface $channel, string $contactEmail): void
    {
        $channel->setContactEmail($contactEmail);

        $this->entityManager->persist($channel);
        $this->entityManager->flush();
    }
}
