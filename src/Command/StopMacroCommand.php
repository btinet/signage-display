<?php

namespace App\Command;

use App\Repository\RunningDisplayMacroRepository;
use App\Service\InfoScreenSettings;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:makro:stop',
    description: 'Stop macro.',
)]
class StopMacroCommand extends Command
{

    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly RunningDisplayMacroRepository $runningDisplayMacroRepository,
        private readonly InfoScreenSettings            $infoScreenSettings
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $currentRunningMacro = $this->runningDisplayMacroRepository->findOneBy([]);
            if (!$currentRunningMacro) {
                throw new Exception("no macro is running");
            }
            $today = new \DateTimeImmutable("now")->setTime(0, 0, 0);
            if ($currentRunningMacro->getValidTo()->format("Y-m-d") !== $today->format("Y-m-d")) {
                throw new Exception("it is not time to cancel");
            }
            $macro = $currentRunningMacro->getDisplayMacro();

            // Alte Elemente wiederherstellen
            foreach ($macro->getPrevBlogPosts() as $blogPost) {
                $blogPost->setActive(true);
                $this->entityManager->persist($blogPost);
            }
            foreach ($macro->getPrevShoutOuts() as $shoutOut) {
                $shoutOut->setDisabled(false);
                $this->entityManager->persist($shoutOut);
            }

            // Makro deaktivieren
            foreach ($macro->getDisplayBlogPosts() as $blogPost) {
                $blogPost->setActive(false);
                $this->entityManager->persist($blogPost);
            }
            foreach ($macro->getDisplayTickers() as $ticker) {
                $ticker->setDisabled(true);
                $this->entityManager->persist($ticker);
            }
            $this->infoScreenSettings->save([
                'show_schedule_column' => $macro->isPrevShowScheduleColumn()
            ]);
            $this->entityManager->remove($currentRunningMacro);
            $this->entityManager->flush();
        } catch (Exception) {
        }
        return Command::SUCCESS;
    }

}