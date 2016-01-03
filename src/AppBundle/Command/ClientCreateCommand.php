<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question;

class ClientCreateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('oauth:client:create')
                ->setDescription('Creates a new client')
                ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Sets the client name', null)
                ->addOption('redirect-uri', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Sets the redirect uri. Use multiple times to set multiple uris.', null)
                ->addOption('grant-type', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Set allowed grant type. Use multiple times to set multiple grant types', null)
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        if (trim($input->getOption('name')) == '') {
            $question = new Question\Question('Please enter the client name: ');
            $question->setValidator(function ($value) {
                if (trim($value) == '') {
                    throw new \Exception('The client name can not be empty');
                }

                $doctrine = $this->getContainer()->get('doctrine');
                $client = $doctrine->getRepository('AppBundle:Client')
                        ->findOneBy(['name' => $value]);

                if ($client instanceof \AppBundle\Entity\Client) {
                    throw new \Exception('The client name must be unique');
                }

                return $value;
            });
            $question->setMaxAttempts(5);

            $input->setOption('name', $helper->ask($input, $output, $question));
        }

        $grants = $input->getOption('grant-type');
        if (!(is_array($grants) && count($grants))) {
            $question = new Question\ChoiceQuestion(
                    'Please select the grant types (defaults to password and facebook_access_token): ', [
                \OAuth2\OAuth2::GRANT_TYPE_AUTH_CODE,
                \OAuth2\OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS,
                \OAuth2\OAuth2::GRANT_TYPE_EXTENSIONS,
                \OAuth2\OAuth2::GRANT_TYPE_IMPLICIT,
                \OAuth2\OAuth2::GRANT_TYPE_REFRESH_TOKEN,
                \OAuth2\OAuth2::GRANT_TYPE_USER_CREDENTIALS,
                'http://grants.api.schoolmanager.ledo.eu.org/facebook_access_token'
                    ], '5,6'
            );
            $question->setMultiselect(true);
            $question->setMaxAttempts(5);

            $input->setOption('grant-type', $helper->ask($input, $output, $question));
        }

        parent::interact($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clientManager = $this->getContainer()->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris($input->getOption('redirect-uri'));
        $client->setAllowedGrantTypes($input->getOption('grant-type'));
        $client->setName($input->getOption('name'));
        $clientManager->updateClient($client);
        $output->writeln(sprintf('Added a new client with name <info>%s</info> and public id <info>%s</info>.', $client->getName(), $client->getPublicId()));
    }

}
