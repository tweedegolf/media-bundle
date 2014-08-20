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
        $filter = $request->query->get('filter', 'all');
        $order = $request->query->get('order', 'newest');

        $results =  $this->getDoctrine()->getRepository('TweedeGolfMediaBundle:File')->findSubset($filter, $order);
        $data = $this->get('tweedegolf.media.file_serializer')->serializeAll($results);


        return new JsonResponse([
            'success' => $data
        ]);
    }

    /**
     * Delete a new File entity.
     *
     * @Route("/delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('file');
        $translator = $this->get('translator');

        if ($id !== null) {

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TweedeGolfMediaBundle:File')->find($id);

            if (!$entity) {
                return new JsonResponse([
                    'errors' => [
                        $translator->trans('File not found'),
                    ],
                ]);
            }

            $em->remove($entity);
            $em->flush();

            return new JsonResponse([
                'success' => intval($id),
            ]);
        }

        return new JsonResponse([
            'errors' => [
                $translator->trans('Invalid request'),
            ],
        ]);
    }

    /**
     * Creates a new File entity.
     *
     * @Route("/create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity = new File();

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $data = $this->get('tweedegolf.media.file_serializer')->serialize($entity);

            return new JsonResponse([
                'success' => $data
            ]);
        }

        $errors = [];
        $translator = $this->get('translator');
        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $translator->trans($error->getMessage());
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
    }

    /**
     * @param File $entity
     * @return Form
     */
    protected function createCreateForm(File $entity)
    {
        $builder = $this->get('form.factory')->createNamedBuilder('', 'form', $entity, [
            'csrf_protection' => false,
        ]);

        $builder->add('file');

        return $builder->getForm();
    }
}
