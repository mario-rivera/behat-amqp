<?php
namespace App\MessageHandler;

use App\AddingService;
use PhpAmqpLib\Message\AMQPMessage;

class AddMessageHandler
{
    public function __construct(
        private readonly AddingService $addingService
    ) {   
    }

    public function __invoke(AMQPMessage $message)
    {
        $value = json_decode($message->getBody(), true)['value'] ?? null;
        
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("Value must be a number");
        }

        $this->addingService->add($value);

        $message->ack();
    }
}
