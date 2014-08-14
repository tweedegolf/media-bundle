<?php

namespace TweedeGolf\MediaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
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
     * @Route("/")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $images = $this->getDoctrine()->getRepository('TweedeGolfMedia:File')->findImages();
        $data = $this->get('tweedegolf.media.file_serializer')->serializeAll($images);

        return new JsonResponse([
            'images' => $data
        ]);
    }

    /**
     * Creates a new File entity.
     *
     * @Route("/")
     * @Method("POST")
     */
    public function uploadAction(Request $request)
    {
        $entity = new File();

        $form = $this->createUploadForm($entity);
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
        $translator = $this->get('translator');
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $translator->trans($error->getMessage());
        }

        return new JsonResponse([
            'valid' => $form->isValid(),
            'errors' => $errors,
        ]);
    }

    /**
     * @param File $entity
     * @return Form
     */
    protected function createUploadForm(File $entity)
    {
        $builder = $this->get('form.factory')->createNamedBuilder('', 'form', $entity, [
            'csrf_protection' => false,
        ]);

        $builder->add('file');

        return $builder->getForm();
    }
}
