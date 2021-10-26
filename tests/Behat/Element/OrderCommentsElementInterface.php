<?php

declare(strict_types=1);

namespace Tests\Brille24\OrderCommentsPlugin\Behat\Element;

use Behat\Mink\Element\NodeElement;

interface OrderCommentsElementInterface
{
    public function getFirstComment(): ?NodeElement;

    public function countComments(): int;
}
