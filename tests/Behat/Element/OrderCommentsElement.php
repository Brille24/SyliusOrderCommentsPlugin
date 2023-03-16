<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Behat\Element;

use Behat\Mink\Element\NodeElement;

class OrderCommentsElement extends Element implements OrderCommentsElementInterface
{
    public function getFirstComment(): ?NodeElement
    {
        return $this->getDocument()->find('css', '#comments .comment');
    }

    public function countComments(): int
    {
        return count($this->getDocument()->findAll('css', '#comments .comment'));
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'comments' => '#comments',
        ]);
    }
}
