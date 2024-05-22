<?php

namespace App\Szkolenia\Application\CommandHandler;

use App\CourseLeader\Application\Query\FindCourseLeaderQuery;
use App\Entity\CourseLeader;
use App\Entity\Training;
use App\Szkolenia\Application\Command\GetCourseListingCommand;
use App\Szkolenia\Application\Factory\CourseToDisplayCollectionFactory;
use App\Szkolenia\Application\Query\CourseFindByQuery;
use App\Szkolenia\Domain\CourseToDisplayCollection;
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

            /** @var Training[] $courses */
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