<?php

namespace App\Courses\Application\CommandHandler;

use App\Courses\Application\Command\DeleteCourseCommand;
use App\Courses\Application\Query\CourseFindByIdQuery;
use App\Courses\Repository\CourseRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class DeleteCourseCommandHandler
{
    use HandleTrait;

    public function __construct(
        private CourseRepository    $trainingRepository,
        private MessageBusInterface $messageBus,
    ) {}

    public function __invoke(DeleteCourseCommand $command): void
    {
        try {
            $course = $this->handle(new CourseFindByIdQuery($command->getCourseId()));
            $this->trainingRepository->delete($course);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}