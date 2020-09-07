<?php


namespace App\Commands;


use Core\App;
use Illuminate\Container\Container;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{
    protected Container $kernel;
    protected static string $defaultName = 'app:migrate';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = App::getInstance();
        $resolver = $container->get('db.resolver');

        $repository = new DatabaseMigrationRepository($resolver, 'migration');
        $files = new Filesystem();
        $migrator = new Migrator($repository, $resolver, $files);

        $migrations = $migrator->setOutput($output)->run($container->get('paths')['migration_path']);

        return $migrations > 0 ? 0 : 1;
    }
}