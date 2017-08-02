<?php

declare(strict_types=1);

namespace Tests\Sylius\OrderCommentsPlugin\Behat\Element;

use Behat\Mink\Element\NodeElement;

interface OrderCommentsElementInterface
{
    /**
     * @return array|NodeElement[]
     */
    public function getFirstComment(): NodeElement;
}
