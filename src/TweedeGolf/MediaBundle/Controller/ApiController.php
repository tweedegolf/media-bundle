<?php

namespace TweedeGolf\MediaBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use TweedeGolf\MediaBundle\Model\AbstractFile;

/**
 * Class MediaController.
 */
class ApiController extends Controller
{
    /**
     * Render the modal template.
     *
     * @Route("/modal")
     * @Method("GET")
     */
    public function modalAction(Request $request)
    {
        return $this->render('TweedeGolfMediaBundle:File:modal.html.twig');
    }

    /**
     * List, sort and filter files.
     *
     * @Route("/", name="tgmedia_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $filter = $request->query->get('filter', 'all');
        $order = $request->query->get('order', 'newest');
        $page = $request->query->get('page', 1);
        $max = $this->container->getParameter('tweede_golf_media.max_per_page');

        $paginator = $this->get('tweedegolf.repository.file')->findSubset($filter, $order, $page, $max);
        $data = $this->get('tweedegolf.media.file_serializer')->serializeAll($paginator);

        return new JsonResponse([
            'success' => $data,
            'total' => count($paginator),
            'max' => $max,
        ]);
    }

    /**
     * Delete a new file entity.
     *
     * @Route("/delete", name="tgmedia_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request)
    {
        $id = $request->get('file');
        $translator = $this->get('translator');

        if ($id !== null) {
            $em = $this->getDoctrine()->getManager();
            $entity = $this->get('tweedegolf.repository.file')->find($id);

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
     * Creates a new file entity.
     *
     * @Route("/create", name="tgmedia_create")
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
                'success' => $data,
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
     * Create the upload form
     * Only used for validation.
     *
     * @param AbstractFile $entity
     *
     * @return Form
     */
    protected function createCreateForm(AbstractFile $entity)
    {
        $builder = $this->get('form.factory')->createNamedBuilder('', 'form', $entity, [
            'csrf_protection' => false,
        ]);

        $builder->add('file');

        return $builder->getForm();
    }
}
