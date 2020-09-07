<?php


namespace App\Commands;


use Core\App;
use Illuminate\Container\Container;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateInstallCommand extends Command
{
    protected Container $kernel;
    protected static string $defaultName = 'app:install-migrate';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $container = App::getInstance();
        $resolver = $container->get('db.resolver');

        $repository = new DatabaseMigrationRepository($resolver, 'migration');
        $repository->createRepository();

        return 0;
    }
}