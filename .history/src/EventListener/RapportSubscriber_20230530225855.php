<?php

namespace App\Form\EventListener;

use App\Entity\Mission;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RapportSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
   }

    public function preSetData(FormEvent $event,Mission $mission): void
    {       
            $missions = $mission->getDateretour();
            $rapportmission = $event->getData();
            $form = $event->getForm();

            if (!$rapportmission || null === $rapportmission->getId() 1) {
            $form->add('name', TextType::class);
        }
    }
}