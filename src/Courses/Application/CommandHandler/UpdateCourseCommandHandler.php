<?php

namespace App\Courses\Application\CommandHandler;

use App\Common\ValueObject\Money;
use App\CourseLeader\Application\Query\FindCourseLeaderQuery;
use App\Courses\Application\Command\SaveCourseCommand;
use App\Courses\Application\Command\UpdateCourseCommand;
use App\Courses\Application\Exception\DateOfCourseExpiredException;
use App\Courses\Application\Query\CourseFindByIdQuery;
use App\Courses\Entity\Course;
use DateTime;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class UpdateCourseCommandHandler
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
    ) {}

    public function __invoke(UpdateCourseCommand $command): void
    {
        try {
            /** @var Course $course */
            $course = $this->handle(new CourseFindByIdQuery($command->getCourseId()));

            if ($command->getName() !== null) {
                $course->setName($command->getName());
            }

            if ($command->getPrice() !== null) {
                $course->setPrice(Money::PLNFromFloat($command->getPrice()));
            }

            if ($command->getDateOfCourse() !== null) {
                if ($command->getDateOfCourse()->getTimestamp() <= (new DateTime())->getTimestamp()) {
                    throw new DateOfCourseExpiredException("Course start date is expire");
                }
                $course->setDateOfTraining($command->getDateOfCourse());
            }

            if ($command->getCourseLeaderName() !== null && $command->getCourseLeaderSurname() !== null) {
                $course->setCourseLeader(
                    $this->handle(new FindCourseLeaderQuery(
                            name: $command->getCourseLeaderName(),
                            surname: $command->getCourseLeaderSurname())
                    )
                );
            }

            $this->handle(new SaveCourseCommand($course));
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}