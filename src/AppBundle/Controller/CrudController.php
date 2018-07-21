<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use AppBundle\Entity\Manager\Interfaces\ManagerInterface;
use AppBundle\Form\Handler\FormHandlerInterface;

class CrudController extends Controller {

    protected $container;
    protected $manager;
    protected $handlerForm;

    public function getContainer() {
        return $this->container;
    }

    public function __construct(Container $container, ManagerInterface $manager, FormHandlerInterface $handlerForm) {
        $this->container = $container;
        $this->manager = $manager;
        $this->handlerForm = $handlerForm;
    }

    public function listAction(Request $request) {

        $posts = $this->getResourceManager()->getResultFilterPaginated();

        return $this->render($request->get('_template'), array(
                    'posts' => $posts,
        ));
    }

    public function showAction(Request $request) {

        $post = $this->getResourceManager()->find($request->get('id'));

        return $this->render($request->get('_template'), array(
                    'post' => $post,
        ));
    }

    public function crudAction(Request $request) {

        if ($request->get('id')) {
            $post = $this->getResourceManager()->find($request->get('id'));
        } else {
            $post = null;
        }

        $entityToProcess = $this->getResourceFormHandler()->processForm($post);

        if ($this->getResourceFormHandler()->handleForm($this->getResourceFormHandler()->getForm(), $entityToProcess, $request)) {
            $this->addFlash('success', $this->getResourceFormHandler()->getMessage());

            return $this->redirectToRoute('posts_list');
        }

        return $this->render($request->get('_template'), array(
                    'form' => $this->getResourceFormHandler()->createView(),
                    'post' => $entityToProcess,
        ));
    }

    public function deleteAction(Request $request) {

        $post = $this->getResourceManager()->find($request->get('id'));

        $this->getResourceManager()->remove($post);

        return new RedirectResponse($this->get('router')->generate('posts_list'));
    }

    public function getResourceFormHandler() {
        return $this->handlerForm;
    }

    public function getResourceManager() {
        return $this->manager;
    }

}
