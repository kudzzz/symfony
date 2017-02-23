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

      //$listAdverts = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert')->findAll();

      $nbPerPage = 3;

      $listAdverts = $this->getDoctrine()
          ->getManager()
          ->getRepository('OCPlatformBundle:Advert')
          ->getAdverts($page, $nbPerPage);

      $nbPages = ceil(count($listAdverts)/$nbPerPage);

      if ($page > $nbPerPage){
          throw $this->createNotFoundException("La page ".$page." n'existe pas");
      }

    return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
        'listAdverts' => $listAdverts,
        'nbPages' => $nbPages,
        'page' => $page
    ));
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
      $advert = new Advert();
      $form = $this->get('form.factory')->createBuilder('form', $advert)
          ->add('date','date')
          ->add('title','text')
          ->add('content', 'textarea')
          ->add('author', 'text')
          ->add('published', 'checkbox', array('required' => false))
          ->add('save', 'submit')
          ->getForm();

      $form->handleRequest($request);

      if($form->isValid()){
          $em = $this->getDoctrine()
              ->getManager();
          $em->persist($advert);
          $em->flush();
          $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistree.');

          return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
      }

      return $this->render('OCPlatformBundle:Advert:add.html.twig', array(
          'form' => $form->createView()
      ));
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

	return $this->render('OCPlatformBundle:Advert:edit.html.twig', array('advert' => $advert));
  }

  public function deleteAction($id, Request $request)
  {
      $em = $this->getDoctrine()->getManager();

      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if (null === $advert){
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
      }

      if ($request->isMethod('POST')){
          $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimee.');

          return $this->redirect($this->generateUrl('oc_platform_view'));
      }

    return $this->render('OCPlatformBundle:Advert:delete.html.twig', array('advert' => $advert));
  }
  
  public function menuAction($limit = 3){

      $listAdverts = $this->getDoctrine()->getManager()->getRepository('OCPlatformBundle:Advert')->findBy(
          array(),
          array('date' => 'desc'),
          $limit,
          0
      );
	  
	  return $this->render('OCPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
	  
  }
}