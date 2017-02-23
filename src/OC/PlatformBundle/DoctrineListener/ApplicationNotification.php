<?php
/**
 * Created by PhpStorm.
 * User: Shaheer
 * Date: 2/22/2017
 * Time: 4:22 PM
 */

namespace OC\PlatformBundle\DoctrineListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use OC\PlatformBundle\Entity\Application;


class ApplicationNotification
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer){
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if(!$entity instanceof Application){
            return;
        }

        $message = new \Swift_Message(
            'Nouvelle candidature',
            'Vous avez recu une nouvelle candidature.'
        );

        $message
            ->addTo($entity->getAdvert()->getAuthor())
            ->addFrom('admin@admin.com');

        $this->mailer->send($message);
    }

}