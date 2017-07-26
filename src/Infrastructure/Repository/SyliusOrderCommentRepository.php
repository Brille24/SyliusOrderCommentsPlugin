<?php

declare(strict_types=1);

namespace Sylius\OrderCommentsPlugin\Infrastructure\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\OrderCommentsPlugin\Application\Repository\OrderCommentRepository;

final class SyliusOrderCommentRepository extends EntityRepository implements OrderCommentRepository
{

}
