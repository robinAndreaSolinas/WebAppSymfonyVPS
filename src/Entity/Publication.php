<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT, options: ["unsigned" => true])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $journal = null;

    #[ORM\Column(length: 511)]
    private ?string $url = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, name: "datetime" , insertable: false, updatable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $datetime = null;


    #[ORM\Column(length: 100, nullable: true)]
    private ?string $editorial = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    private ?int $img = null;

    #[ORM\Column(nullable: true, options: ["unsigned" => true])]
    private ?int $body = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJournal(): ?string
    {
        return $this->journal;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }


    public function getEditorial(): ?string
    {
        return $this->editorial;
    }


    public function getImg(): ?int
    {
        return $this->img;
    }


    public function getBody(): ?int
    {
        return $this->body;
    }
}
