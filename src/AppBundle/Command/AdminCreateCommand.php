<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AdminCreateCommand
 * @package AppBundle\Command
 */
class AdminCreateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:admin_create')
            ->setDescription('Create admin user')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
                new InputArgument('firstName', InputArgument::REQUIRED, 'The first name'),
                new InputArgument('lastName', InputArgument::REQUIRED, 'The last name')
            ));

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');
        $firstName  = $input->getArgument('firstName');
        $lastName   = $input->getArgument('lastName');

        $this->getContainer()->get('app.admin.creator')
             ->create($email, $password, $firstName, $lastName);

        $output->writeln(sprintf('User <comment>%s</comment> was created/updated', $firstName.' '.$lastName));
    }
}
