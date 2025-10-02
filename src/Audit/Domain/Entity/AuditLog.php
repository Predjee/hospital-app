<?php

declare(strict_types=1);

namespace App\Audit\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'audit_log')]
class AuditLog
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column(length: 255)]
    private string $action;

    /** @var array<string, mixed> */
    #[ORM\Column(type: 'json')]
    private array $payload;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $recordedAt;

    /** @param array<string, mixed> $payload */
    public function __construct(string $action, array $payload = [])
    {
        $this->id = new Ulid();
        $this->action = $action;
        $this->payload = $payload;
        $this->recordedAt = new \DateTimeImmutable();
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function action(): string
    {
        return $this->action;
    }

    /** @return array<string, mixed> */
    public function payload(): array
    {
        return $this->payload;
    }

    public function recordedAt(): \DateTimeImmutable
    {
        return $this->recordedAt;
    }
}
