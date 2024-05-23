<?php

namespace App\Courses\Application\CommandHandler;

use App\CourseLeader\Application\Query\FindCourseLeaderQuery;
use App\Courses\Application\Command\CreateCourseCommand;
use App\Courses\Application\Command\SaveCourseCommand;
use App\Courses\Application\Factory\CourseFactory;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class CreateCourseCommandHandler
{
    use HandleTrait;

    public function __construct(
        private CourseFactory $courseFactory,
        private MessageBusInterface $messageBus
    ) {}

    public function __invoke(CreateCourseCommand $command): int
    {
        try {
            $courseLeader = $this->handle(new FindCourseLeaderQuery(
                name: $command->getCourseLeaderName(),
                surname: $command->getCourseLeaderSurname()
            ));

            $course = $this->courseFactory->create(
                courseLeader: $courseLeader,
                name: $command->getName(),
                price: $command->getPrice(),
                dateOfCourse: $command->getDateOfCourse()
            );
            $this->handle(new SaveCourseCommand($course));

            return $course->getId();
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        } catch (Throwable $e) {
            throw $e;
        }
    }
}