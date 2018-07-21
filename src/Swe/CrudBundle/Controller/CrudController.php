<?php

namespace Swe\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Swe\CoreBundle\Entity\Manager\Interfaces\GenericManagerInterface;
use Swe\CrudBundle\Form\Handler\Interfaces\FormHandlerInterface;

class CrudController extends Controller {

    protected $container;
    protected $manager;
    protected $handlerForm;

    public function getContainer() {
        return $this->container;
    }

    public function __construct(Container $container, GenericManagerInterface $manager, FormHandlerInterface $handlerForm) {
        $this->container = $container;
        $this->manager = $manager;
        $this->handlerForm = $handlerForm;
    }

    public function listAction(Request $request) {

        $resources = $this->getResourceManager()->getResultFilterPaginated();

        return $this->render($request->get('_template'), array(
                    'resources' => $resources,
        ));
    }

    public function showAction(Request $request) {

        $resource = $this->getResourceManager()->find($request->get('id'));

        return $this->render($request->get('_template'), array(
                    'resource' => $resource,
        ));
    }

    public function crudAction(Request $request) {

        if ($request->get('id')) {
            $resource = $this->getResourceManager()->find($request->get('id'));
        } else {
            $resource = null;
        }

        $entityToProcess = $this->getResourceFormHandler()->processForm($resource);

        if ($this->getResourceFormHandler()->handleForm($this->getResourceFormHandler()->getForm(), $entityToProcess, $request)) {
            $this->addFlash('success', $this->getResourceFormHandler()->getMessage());

            return $this->redirectToRoute('posts_list');
        }

        return $this->render($request->get('_template'), array(
                    'form' => $this->getResourceFormHandler()->createView(),
                    'resource' => $entityToProcess,
        ));
    }

    public function deleteAction(Request $request) {

        $resource = $this->getResourceManager()->find($request->get('id'));

        $this->getResourceManager()->remove($resource);

        return new RedirectResponse($this->get('router')->generate('posts_list'));
    }

    public function getResourceFormHandler() {
        return $this->handlerForm;
    }

    public function getResourceManager() {
        return $this->manager;
    }

}
