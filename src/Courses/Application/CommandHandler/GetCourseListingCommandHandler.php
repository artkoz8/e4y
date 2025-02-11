<?php

namespace App\Courses\Application\CommandHandler;

use App\CourseLeader\Application\Query\FindCourseLeaderQuery;
use App\CourseLeader\Entity\CourseLeader;
use App\Courses\Application\Command\GetCourseListingCommand;
use App\Courses\Application\Factory\CourseToDisplayCollectionFactory;
use App\Courses\Application\Query\CourseFindByQuery;
use App\Courses\Domain\CourseToDisplayCollection;
use App\Courses\Entity\Course;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsMessageHandler]
class GetCourseListingCommandHandler
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $messageBus,
        private CourseToDisplayCollectionFactory $courseToDisplayCollectionFactory
    ) {}

    public function __invoke(GetCourseListingCommand $command): CourseToDisplayCollection
    {
        try {
            if (null !== $command->getCourseLeaderName() && null !== $command->getCourseLeaderSurname()) {
                /** @var CourseLeader $courseLeader */
                $courseLeader = $this->handle(new FindCourseLeaderQuery($command->getCourseLeaderName(), $command->getCourseLeaderSurname()));
            }

            /** @var Course[] $courses */
            $courses = $this->handle(new CourseFindByQuery(
                courseLeader: $courseLeader??null,
                courseName: $command->getName(),
                startDate: $command->getStartDate(),
                endDate: $command->getEndDate()
            ));
            return $this->courseToDisplayCollectionFactory->createCollection($courses);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        } catch (Throwable $exception) {
            throw $exception;
        }
    }
}