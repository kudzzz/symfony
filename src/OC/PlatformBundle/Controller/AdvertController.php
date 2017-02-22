<?php

// src/OC/PlatformBundle/Controller/AdvertController.php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
  public function indexAction($page)
  {
    // On ne sait pas combien de pages il y a
    // Mais on sait qu'une page doit être supérieure ou égale à 1
    if ($page < 1) {
      // On déclenche une exception NotFoundHttpException, cela va afficher
      // une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }

    // Ici, on récupérera la liste des annonces, puis on la passera au template

    // Mais pour l'instant, on ne fait qu'appeler le template
	
	$listAdverts = array(
	  array(
	    'id' => 1,
		'title' => 'Developer Symfony2',
		'author' => 'Alexandre',
		'content' => 'nouveau developpeur pour Symfony2',
		'date' => new \DateTime()
	  ),
	  array(
	    'id' => 2,
		'title' => 'Developer webmaster',
		'author' => 'Alexandre',
		'content' => 'nouveau developpeur pour webmaster',
		'date' => new \DateTime()
	  ),
	  array(
	    'id' => 3,
		'title' => 'Developer webdesigner',
		'author' => 'Alexandre',
		'content' => 'nouveau developpeur pour webdesigner',
		'date' => new \DateTime()
	  ),
	  
	);
	
    return $this->render('OCPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
  }

  public function viewAction($id)
  {
    // Ici, on récupérera l'annonce correspondante à l'id $id
	/*
	$advert = array(
	  'title' => 'Recherche developpeur Symfony2',
	  'id' => $id,
	  'author' => 'Alexandre',
	  'content' => 'Un nouveau developpeur de Symfony2',
	  'date' => new \DateTime()	  
	);

    return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
    ));*/

      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert){
          throw new NotFoundHttpException("L'annonce d'id " .$id. " n'existe pas.");
      }
      $listApplications = $em->getRepository('OCPlatformBundle:Application')->findBy(array('advert' => $advert));

      $listAdvertSkills = $em->getRepository('OCPlatformBundle:AdvertSkill')->findBy(array('advert' => $advert));

      return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
          'advert' => $advert,
          'listApplications' => $listApplications,
          'listAdvertSkills' => $listAdvertSkills));
  }

  public function addAction(Request $request)
  {
	  
    /*$antispam = $this->container->get('oc_platform.antispam');
    $text = '...';
	if ($antispam->isSpam($text)){
	  throw new \Exception('Votre message a ete detecte comme spam !');
		
	}*/
      $em = $this->getDoctrine()->getManager();

      $advert = new Advert();
      $advert->setTitle("Dev Symfony2");
      $advert->setAuthor("Alex");
      $advert->setContent("Symfony2 zzzz");

      $application1 = new Application();
      $application1->setAuthor('Marine');
      $application1->setContent("J'ai toutes les qualites requises.");

      $application2 = new Application();
      $application2->setAuthor('Pierre');
      $application2->setContent("Je suis tres motive.");

      $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

      foreach($listSkills as $skill){

          $advertSkill = new AdvertSkill();

          $advertSkill->setAdvert($advert);
          $advertSkill->setSkill($skill);
          $advertSkill->setLevel('Expert');

          $em->persist($advertSkill);
      }

      /*
      $image = new Image();
      $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
      $image->setAlt('Job de reve');

      $advert->setImage($image);
       */

      $application1->setAdvert($advert);
      $application2->setAdvert($advert);

      $em->persist($advert);

      $em->persist($application1);
      $em->persist($application2);

      $em->flush();


      return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
  }

  public function editAction($id, Request $request)
  {
    // Ici, on récupérera l'annonce correspondante à $id

    // Même mécanisme que pour l'ajout
	/*
    if ($request->isMethod('POST')) {
      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

      return $this->redirectToRoute('oc_platform_view', array('id' => 5));
    }

    return $this->render('OCPlatformBundle:Advert:edit.html.twig');
	*/
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert){
          throw new NotFoundHttpException("L'annonce d'id ".$id. " n'existe pas.");
      }

      $listCategories = $em->getRepository('OCPlatformBundle:category')->findAll();

      foreach ($listCategories as $category){
          $advert->addCategory($category);
      }

      $em->flush();

	return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert' => $advert));
  }

  public function deleteAction($id)
  {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert){
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
      }

      foreach($advert->getCategories() as $category){
          $advert->removeCategory($category);
      }
      $em->flush();

    return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert' => $advert));
  }
  
  public function menuAction(){
	  $listAdverts = array(
		array('id' => 2, 'title' => 'Dev Symfony2'),
		array('id' => 5, 'title' => 'Webmaster'),
		array('id' => 9, 'title' => 'Webdesigner')
	  );
	  
	  return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
	  
  }
}