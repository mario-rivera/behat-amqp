<?php
namespace App\Tests\Behat\Context;

use App\AddingService;
use App\MessageHandler\AddMessageHandler;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\Generator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;

class AddMessageHandlerContext implements Context
{
    private array $messages = [];

    private static MockObject|AMQPChannel $channel;

    private static AddingService $addingService;

    private static AddMessageHandler $messageHandler;

    /** @BeforeFeature */
    public static function setupFeature()
    {
        $generator = new Generator;

        self::$channel = $generator->getMock(
            AMQPChannel::class,
            $methods = [],
            $methods = [],
            $arguments = '',
            $callOriginalConstructor = false,
            $callOriginalClone = true,
            $callAutoload = true,
            $cloneArguments = true,
            $callOriginalMethods = false,
            $proxyTarget = null,
            $allowMockingUnknownTypes = true,
            $returnValueGeneration = true
        );

        self::$addingService = new AddingService;

        self::$messageHandler = new AddMessageHandler(self::$addingService);
    }

    /**
     * @Given the following messages in the adding queue:
     */
    public function thereAreMessagesInTheAddingQueue(TableNode $table)
    {
        $rows = $table->getRows();

        while ($row = next($rows)) {
            $message = (new AMQPMessage($row[0]))->setChannel(self::$channel);
            $this->messages[] = $message;
        }
    }

    /**
     * @When the messages in the queue are handled
     */
    public function theMessagesInTheQueueAreHandled()
    {
        $messageHandler = self::$messageHandler;

        $ackInvocationCount = new InvokedCount(count($this->messages));
        self::$channel->expects($ackInvocationCount)->method('basic_ack');

        foreach ($this->messages as $message) {
            $messageHandler($message);
        }

        $ackInvocationCount->verify();
    }

    /**
     * @Then the total in the adding service should be :arg1
     */
    public function theTotalInTheAddingServiceShouldBe(float $arg1)
    {
        Assert::assertEquals(self::$addingService->getTotal(), $arg1);
    }
}
