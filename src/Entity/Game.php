<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $secret;

    /**
     * @ORM\Column(type="integer")
     */
    private $attempts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setToken(): self
    {
        $unique = str_shuffle(uniqid('', true));
        $hash = str_replace('.', '', $unique);
        $this->token = strtoupper(substr($hash, 0, 16));

        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }
}
