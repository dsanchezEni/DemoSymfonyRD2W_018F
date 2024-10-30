<?php
namespace App\Notification;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Security\Core\User\UserInterface;
class Sender{
    public function __construct(private readonly MailerInterface $mailer){

    }
    public function sendNewUserNotificationToAdmin(UserInterface $user):void{
        //Pour tester
        //file_put_contents('debug.txt',$user->getEmail());
        $message = new Email();
        $message->from('contact@test.fr')
            ->to('admin@test.fr')
            ->subject('Nouveau utilisateur créé depuis le site Demo')
            ->html('<h1>Nouvel utilisateur</h1><p>Email :'.$user->getEmail().'</p>');
        $this->mailer->send($message);

    }
}