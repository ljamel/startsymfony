<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace AppBundle\sandboxBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:test-command')
            // the short description shown while running "php bin/console list"
            ->setDescription('Prints some text into the console.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to print some text in the console")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'My First Symfony command',// A line
            '============',// Another line
            '',// Empty line
        ]);

        // outputs a message followed by a "\n"
        $output->writeln('Hey welcome to the test command wizard.');
        $output->writeln('Thanks for read the article');
        
        // outputs a message without adding a "\n" at the end of the line
        $output->write("You've succesfully implemented your first command");
    }
}