<?php

namespace App\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait MetaEntityTrait
{


    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false,  options: ['default' => 'CURRENT_TIMESTAMP'])]
    protected ?DateTime $createdAt;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, insertable: false, updatable: false, columnDefinition: 'DATETIME on update CURRENT_TIMESTAMP')]
    protected ?DateTime $updatedAt;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

}
