<?php
declare(strict_types=1);

namespace Vim\Settings\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Vim\Settings\Entity\AbstractSettings;
use Vim\Settings\Repository\SettingsRepository;
use Vim\Settings\Service\SettingsCollection;
use Vim\Settings\Service\SettingsServiceInterface;

#[AsCommand(
    name: 'settings:sync',
)]
class SettingsSyncCommand extends Command
{
    public function __construct(
        private SettingsServiceInterface $settingsService,
        private SettingsCollection $settingsCollection,
        private SettingsRepository $settingsRepository,
        private EntityManagerInterface $em
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $settingEntities = array_reduce(
            $this->settingsRepository->findAll(),
            static function(array $result, AbstractSettings $settings) {
                $result[$settings->getCode()] = $settings;

                return $result;
            },
            []
        );

        foreach ($settingEntities as $settingEntity) {
            if (!$this->settingsCollection->one($settingEntity->getCode())) {
                $io->warning(sprintf('Remove - "%s"', $settingEntity->getCode()));
                $this->em->remove($settingEntity);
            }
        }

        foreach ($this->settingsCollection->all() as $settingConfig) {
            if (isset($settingEntities[$settingConfig->getCode()])) {
                continue;
            }

            $this->settingsService->save(
                $settingConfig->getCode(),
                $settingConfig->getType(),
                $settingConfig->getValue(),
            );
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
