<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Element;

use Behat\Mink\Element\NodeElement;

class OrderCommentsElement extends Element implements OrderCommentsElementInterface
{
    public function getFirstComment(): ?NodeElement
    {
        return $this->getElement('comments')->find('css','.comment');
    }

    public function countComments(): int
    {
        return count($this->getElement('comments')->findAll('css','.comment'));
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'comments' => '#comments',
        ]);
    }
}
