<?php

namespace App\Szkolenia\Application\CommandHandler;

use App\Entity\Training;
use App\Szkolenia\Application\Command\GetCourseCommand;
use App\Szkolenia\Application\Factory\CourseToDisplayCollectionFactory;
use App\Szkolenia\Application\Query\CourseFindByIdQuery;
use App\Szkolenia\Domain\CourseToDisplayCollection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class GetCourseCommandHandler
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
        private CourseToDisplayCollectionFactory $courseToDisplayCollectionFactory
    ) {}

    public function __invoke(GetCourseCommand $command): CourseToDisplayCollection
    {
        try {
            /** @var Training $course */
            $course = $this->handle(new CourseFindByIdQuery($command->getCourseId()));
            return $this->courseToDisplayCollectionFactory->createCollection([$course]);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}