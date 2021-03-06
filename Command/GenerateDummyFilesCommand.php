<?php
/**
 * @namespace Coolshop\CoolSonataTranslationBundle\Command
 */
namespace Coolshop\CoolSonataTranslationBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DumpTranslationFiles
 *
 * @package Coolshop\CoolSonataTranslationBundle\Command
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Component\Console\Input\InputArgument
 * @uses Symfony\Component\Console\Input\InputInterface
 * @uses Symfony\Component\Console\Input\InputOption
 * @uses Symfony\Component\Console\Output\OutputInterface
 */
class GenerateDummyFilesCommand extends BaseTranslationCommand
{
    /**
     * command configuration
     */
    protected function configure()
    {
        $this
            ->setName('cool:translations:dummy')
            ->setDescription('generate dummy files for translation loader')
            ->addOption(
                'force',
                'f',
                InputOption::VALUE_OPTIONAL,
                'force creation of all locales for the given domain.',
                'messages'
            )
            ;
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            '<info>--------------------------------------------------------------------------------</info>'
        );
        $output->writeln('<info>generating dummy files</info>');
        $output->writeln(
            '<info>--------------------------------------------------------------------------------</info>'
        );

        $translationPath = $this->getKernel()->getRootDir() . '/Resources/translations/';
        $fs              = $this->getFilesystem();

        // create directory for translations if not exists
        if (!$fs->exists($translationPath)) {
            $fs->mkdir($translationPath);
        }

        if ($forceDomain = $input->getOption('force')) {

            $translationManager = $this->getLocaleManager();
            $localeArray = $translationManager->getAvailableLocales();
            foreach ($localeArray as $locale) {
                $filename = $forceDomain . '.' . $locale . '.db';

                if (!$fs->exists($translationPath . $filename)) {
                    $fs->touch($translationPath . $filename);
                }
            }

        } else {

            $translationManager = $this->getTranslationManager();
            $transKeys = $translationManager->findAll();
            foreach ($transKeys as $tKey) {
                foreach ($tKey->getTranslations() as $transLabel) {
                    $filename = $tKey->getDomain() . '.' . $transLabel->getLocale() . '.db';

                    if (!$fs->exists($translationPath . $filename)) {
                        $fs->touch($translationPath . $filename);
                    }
                }
            }
        }

        $output->writeln(
            '<info>--------------------------------------------------------------------------------</info>'
        );
        $output->writeln('<info>finished!</info>');
        $output->writeln(
            '<info>--------------------------------------------------------------------------------</info>'
        );
    }
}
