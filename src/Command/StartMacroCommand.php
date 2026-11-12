<?php

namespace App\Command;

use App\Entity\RunningDisplayMacro;
use App\Repository\BlogPostRepository;
use App\Repository\DisplayMacroRepository;
use App\Repository\RunningDisplayMacroRepository;
use App\Repository\ShoutOutRepository;
use App\Service\InfoScreenSettings;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:makro:start',
    description: 'Start macro.',
)]
class StartMacroCommand extends Command
{

    public function __construct(
        private readonly EntityManagerInterface        $entityManager,
        private readonly DisplayMacroRepository        $displayMacroRepository,
        private readonly RunningDisplayMacroRepository $runningDisplayMacroRepository,
        private readonly BlogPostRepository            $blogPostRepository,
        private readonly ShoutOutRepository            $shoutOutRepository,
        private readonly InfoScreenSettings            $infoScreenSettings
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $hasRunningMacro = $this->runningDisplayMacroRepository->findOneBy([]);
            if ($hasRunningMacro) {
                throw new Exception("macro already running");
            }
            $currentMacro = $this->displayMacroRepository->findCurrentMacro();
            if (!$currentMacro) {
                throw new Exception("no macro found");
            }
            $lastDay = $currentMacro->getVaildTo() ?: $currentMacro->getValidFrom();
            $runningMacro = new RunningDisplayMacro();
            $runningMacro->setDisplayMacro($currentMacro);
            $runningMacro->setValidTo($lastDay);
            $this->entityManager->persist($runningMacro);

            // Alles deaktivieren
            foreach ($this->blogPostRepository->findAll() as $blogPost) {
                $blogPost->setActive(false);
                $this->entityManager->persist($blogPost);
            }
            foreach ($this->shoutOutRepository->findAll() as $shoutOut) {
                $shoutOut->setDisabled(true);
                $this->entityManager->persist($shoutOut);
            }

            // Makro starten
            foreach ($currentMacro->getDisplayBlogPosts() as $displayBlogPost) {
                $displayBlogPost->setActive(true);
                $this->entityManager->persist($displayBlogPost);
            }
            foreach ($currentMacro->getDisplayTickers() as $ticker) {
                $ticker->setDisabled(false);
                $this->entityManager->persist($ticker);
            }
            $this->entityManager->flush();
            $this->infoScreenSettings->save([
                'show_schedule_column' => $currentMacro->isShowScheduleColumn()
            ]);
        } catch (Exception) {

        }
        return Command::SUCCESS;
    }

}