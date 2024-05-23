<?php

namespace App\Courses\Application\CommandHandler;

use App\Courses\Application\Command\GetCourseCommand;
use App\Courses\Application\Factory\CourseToDisplayCollectionFactory;
use App\Courses\Application\Query\CourseFindByIdQuery;
use App\Courses\Domain\CourseToDisplayCollection;
use App\Courses\Entity\Course;
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
            /** @var Course $course */
            $course = $this->handle(new CourseFindByIdQuery($command->getCourseId()));
            return $this->courseToDisplayCollectionFactory->createCollection([$course]);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}