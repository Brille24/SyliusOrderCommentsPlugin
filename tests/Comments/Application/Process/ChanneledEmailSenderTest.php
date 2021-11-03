<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Comments\Application\Process;

use Brille24\OrderCommentsPlugin\Application\Process\Sender\ChanneledEmailSender;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

class ChanneledEmailSenderTest extends TestCase
{
    private ChanneledEmailSender $channeledEmailSender;

    /** @var MockObject|SenderInterface */
    private $sender;

    /** @var ChannelInterface|MockObject */
    private $channel;

    /** @var ChannelContextInterface|MockObject */
    private $channelContext;

    protected function setUp(): void
    {
        $this->sender = $this->createMock(SenderInterface::class);

        $this->channel = $this->createMock(ChannelInterface::class);
        $this->channelContext = $this->createMock(ChannelContextInterface::class);

        /** @var ChannelRepositoryInterface|MockObject $channelRepository */
        $channelRepository = $this->createMock(ChannelRepositoryInterface::class);
        $channelRepository
            ->method('findOneByCode')
            ->with('glasses24')
            ->willReturn($this->channel)
        ;

        $this->channeledEmailSender =
            new ChanneledEmailSender($this->sender, $channelRepository, $this->channelContext);
    }

    public function testSendsTheEmailWithChanneledTemplate(): void
    {
        $this->sender->expects($this->once())->method('send')->with($this->equalTo('welcome_glasses24'));

        $this->channeledEmailSender
            ->sendWithChannelTemplate('welcome', 'glasses24', ['test@localhost.com'], ['message' => 'Hello']);
    }

    public function testGetsChannelFromChannelContext(): void
    {
        $this->channelContext->method('getChannel')->willReturn($this->channel);
        $this->channel->method('getCode')->willReturn('glasses24');
        $this->sender->expects($this->once())->method('send')->with($this->equalTo('welcome_glasses24'));

        $this->channeledEmailSender
            ->send('welcome', ['test@localhost.com'], ['message' => 'Hello']);
    }

    public function testSendsAnUnchanneledEmailIfTheChannelHasNoConfig(): void
    {
        $this->channelContext->method('getChannel')->willReturn($this->channel);
        $this->channel->method('getCode')->willReturn('glasses24');
        $this->sender
            ->expects($this->atLeastOnce())
            ->method('send')
            ->withConsecutive(
                [
                    'welcome_glasses24',
                    ['test@localhost.com'],
                    ['message' => 'Hello', 'channel' => $this->channel],
                    [],
                    [],
                ],
                ['welcome'],
            )
            ->will(
                new ConsecutiveCalls([
                    $this->throwException(new InvalidArgumentException()),
                    $this->returnValue(null),
                ])
            )
        ;
        $this->channeledEmailSender
            ->send('welcome', ['test@localhost.com'], ['message' => 'Hello']);
    }
}
