<?php


namespace App\Service;


use App\Entity\LigneMouvement;
use Doctrine\Persistence\Event\LifecycleEventArgs;



class StockUpdateListener {

    private $article;
    private $mouvement;
    private $sens;

    public function __construct()
    {

    }

    public function postUpdate(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();
        // On ne veut envoyer un email que pour les entités Reglement

        if ($entity instanceof LigneMouvement) {
            $this->article      = $entity->getArticle();
           // dd( $entity->getEntreeStock());
            $this->mouvement    = $entity->getMouvement();
            $this->sens         = $this->mouvement->getSens()->getSens();

            $em = $args->getObjectManager();
//dd($entity->getQuantite(),$entity->getAncienneQuantite());
            $quantite   = $entity->getQuantite();
            $enStock    = $this->article->getQuantite() + $this->sens * $entity->getAncienneQuantite() * (-1);
            $stock      = $enStock + $this->sens * $quantite;

            $this->article->setQuantite($stock);

            $em->flush();
            //return;
        }

  /*      if ($entity instanceof LigneDemande) {
            $this->article      = $entity->getArticle();
            $this->sens         = -1;

            $em = $args->getObjectManager();

            $quantite   = $entity->getQuantiteRecue();
            $enStock    = $this->article->getQuantite() + $this->sens * $entity->getAncienneQuantiteRecue() * (-1);
            $stock      = $enStock + $this->sens * $quantite;

            $this->article->setQuantite($stock);

            $em->flush();
            //return;
        }*/

    }

    public function postPersist(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();
        // On ne veut envoyer un email que pour les entités Reglement

        if (!$entity instanceof LigneMouvement) {
            return;
        }

        $this->article      = $entity->getArticle();
        $this->mouvement    = $entity->getMouvement();
        $this->sens         = $this->mouvement->getSens()->getSens();

        $em = $args->getObjectManager();

        $quantite   = $entity->getQuantite();
        //dd($quantite);
        $enStock    = $this->article->getQuantite();
        $stock      = $enStock + $this->sens * $quantite;

        $this->article->setQuantite($stock);

        $em->flush();

    }

    public function postRemove(LifecycleEventArgs $args)
    {

        $entity = $args->getObject();
        // On ne veut envoyer un email que pour les entités Reglement

        if (!$entity instanceof LigneMouvement) {
            return;
        }
        //dd($entity->getEntreeStock());
        $this->article      = $entity->getArticle();
        $this->mouvement    = $entity->getMouvement();
        $this->sens         = $this->mouvement->getSens()->getSens();
        $em = $args->getObjectManager();

        $quantite   = $entity->getQuantite();
        $enStock    = $this->article->getQuantite() + $this->sens * $entity->getQuantite() * (-1);

        $this->article->setQuantite($enStock);

        $em->flush();

    }
}