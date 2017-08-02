<?php

declare(strict_types = 1);

namespace spec\Sylius\OrderCommentsPlugin\Application\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\OrderCommentsPlugin\Application\Command\CommentOrderByAdministrator;
use Sylius\OrderCommentsPlugin\Application\CommandHandler\CommentOrderByAdministratorHandler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\OrderCommentsPlugin\Domain\Model\Comment;
use Sylius\OrderCommentsPlugin\Domain\Model\Email;
use Webmozart\Assert\Assert;

final class CommentOrderByAdministratorHandlerSpec extends ObjectBehavior
{
    function let(OrderRepositoryInterface $orderRepository, EntityManagerInterface $entityManager)
    {
        $this->beConstructedWith($orderRepository, $entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CommentOrderByAdministratorHandler::class);
    }

    function it_handles_comment_order_by_administrator(
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager,
        OrderInterface $order
    ) {
        $orderRepository->findOneBy(['number' => '007'])->willReturn($order);

        $entityManager->persist(Argument::type(Comment::class))
            ->shouldBeCalled()
            ->will(function ($args) use ($order) {
                /** @var Comment $comment */
                $comment = $args[0];

                Assert::eq($comment->message(), 'Some message');
                Assert::eq($comment->authorEmail(), Email::fromString('admin@example.com'));
                Assert::eq($comment->order(), $order->getWrappedObject());
            })
        ;

        $this(CommentOrderByAdministrator::create('007', 'admin@example.com', 'Some message'));
    }

    function it_throws_a_domain_exception_if_order_is_not_found(
        OrderRepositoryInterface $orderRepository,
        EntityManagerInterface $entityManager
    ) {
        $orderRepository->findOneBy(['number' => '007'])->willReturn(null);

        $entityManager->persist(Argument::type(Comment::class))->shouldNotBeCalled();

        $this->shouldThrow(\DomainException::class)->during('__invoke', [
            CommentOrderByAdministrator::create('007', 'admin@example.com', 'Some message'),
        ]);
    }
}
