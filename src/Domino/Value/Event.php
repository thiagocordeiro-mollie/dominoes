<?php declare(strict_types=1);

namespace App\Domino\Value;

class Event
{
    /** @var string */
    private $message;

    private $type;

    private function __construct(string $message, string $type)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public static function success($message): self
    {
        return new self($message, 'info');
    }

    public static function message($message): self
    {
        return new self($message, 'comment');
    }

    public static function error($message): self
    {
        return new self($message, 'error');
    }

    public function __toString(): string
    {
        return sprintf("<%s>%s<%s>", $this->type, $this->message, $this->type);
    }
}
