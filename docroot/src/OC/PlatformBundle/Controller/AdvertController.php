<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if($page < 1){
            throw new NotFoundHttpException('Page "'.$page.'" inexistante."');
        }

        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
        );

        return $this->render('OCPlatformBundle:Advert:index.html.twig', ['listAdverts' => $listAdverts]);
    }

    public function menuAction()
    {
        $listAdverts = [
            ['id' => 2, 'title' => 'Recherche développeur Symfony'],
            ['id' => 5, 'title' => 'Mission de webmaster'],
            ['id' => 9, 'title' => 'Offre de stage webdesigner']
        ];

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', ['listAdverts' => $listAdverts]);
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert){
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listApplications = $em->getRepository('OCPlatformBundle:Application')->findBy(['advert' => $advert]);

        return $this->render('OCPlatformBundle:Advert:view.html.twig', ['advert' => $advert, 'listApplications' => $listApplications]);
    }

    public function addAction(Request $request)
    {
//        $antispam = $this->container->get('oc_platform.antispam');
//
//        $text = '...';
//        if($antispam->isSpam($text)){
//            throw new \Exception('Votre message a été détecté comme spam !');
//        }

        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony');
        $advert->setAuthor('Alexandre');
        $advert->setContent('Nous recherchons un développeur Symfony débutant sur Lyon. Blabla...');

        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        $advert->setImage($image);

        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent('Je suis trés motivé.');

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $em = $this->getDoctrine()->getManager();

        $em->persist($advert);

        $em->persist($application1);
        $em->persist($application2);

        $em->flush();

        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('oc_platform_view', ['id' => $advert->getId()]);
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig');
    }

    public function editAction($id, Request $request)
    {
        if($request->isMethod('POST')){
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('oc_platform_view', ['id' => $id]);
        }

        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', ['advert' => $advert]);
    }

    public function editImageAction($advertId)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($advertId);

        $advert->getImage()->setUrl('test.png');

        $em->flush();

        return new Response('OK');
    }

    public function deleteAction($id)
    {
        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }
}
