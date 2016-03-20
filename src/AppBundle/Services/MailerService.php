<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 19.03.16
 * Time: 10:05
 */

namespace AppBundle\Services;


use Symfony\Bundle\TwigBundle\TwigEngine;

class MailerService
{
    private $mailer;
    private $templating;
    private $generator;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $template, RandomGenerator $randomGenerator)
    {
        $this->mailer = $mailer;
        $this->templating = $template;
        $this->generator = $randomGenerator;
    }

    public function sendMail($mailTo)
    {
        $hash = md5(uniqid());
        $message = \Swift_Message::newInstance()
            ->setSubject('Registration')
            ->setFrom('cs210785gaa@gmail.com')
            ->setTo($mailTo)
            ->setBody(
                $this->templating->render(
                    '@App/Emails/registration.html.twig',
                    array('hash' => $hash, 'email' => $mailTo)
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return $hash;
    }

    public function sendMailRecovery($mailTo)
    {
        $password = $this->generator->generator();
        $message = \Swift_Message::newInstance()
            ->setSubject('Registration')
            ->setFrom('cs210785gaa@gmail.com')
            ->setTo($mailTo)
            ->setBody(
                $this->templating->render(
                    '@App/Emails/recovery.html.twig',
                    array('password' => $password)
                ),
                'text/html'
            );

        $this->mailer->send($message);

        return $password;
    }
}