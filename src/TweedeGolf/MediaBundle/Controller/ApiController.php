<?php

namespace TweedeGolf\MediaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TweedeGolf\MediaBundle\Entity\File;

/**
 * Class MediaController
 */
class ApiController extends Controller
{

    /**
     * Creates a new File entity.
     *
     * @Route("/upload")
     * @Method("POST")
     */
    public function uploadAction(Request $request)
    {
        $entity = new File();

        $form = $this->createFormBuilder($entity, ['csrf_protection' => false])
            ->add('file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return new JsonResponse([
                'success' => $entity->getId()
            ]);
        }

        $errors = [];
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'valid' => $form->isValid(),
            'errors' => $errors,
            'error' => $form->getErrorsAsString()
        ]);
    }
}
