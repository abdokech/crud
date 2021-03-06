<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\Manager\Interfaces\PostManagerInterface;
use AppBundle\Form\Type\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Swe\CrudBundle\Form\Handler\AbstractResourceFormHandlerStrategy;

class UpdatePostFormHandlerStrategy extends AbstractResourceFormHandlerStrategy {

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var PostManagerInterface
     */
    protected $postManager;

    /**
     * @var FormFactoryInterface
     */
    private $formPost;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Service of translation
     * @param PostManagerInterface $postManager
     * @param FormFactoryInterface $formPost
     * @param RouterInterface $router
     */
    public function __construct
    (
    TranslatorInterface $translator, PostManagerInterface $postManager, FormFactoryInterface $formPost, RouterInterface $router
    ) {
        $this->translator = $translator;
        $this->postManager = $postManager;
        $this->formPost = $formPost;
        $this->router = $router;
    }

    public function createForm($resource) {
        $this->form = $this->formPost->create(PostType::class, $resource, array(
               'action' => $this->router->generate('post_edit', array('id' => $resource->getId())),
            'method' => 'PUT',
        ));

        return $this->form;
    }

    public function handleForm(Request $request, $resource) {
        $this->postManager->save($resource, false, true);

        return $this->translator
                        ->trans('acteur.modifier.succes', array(
                            '%nom%' => $resource->getName(),
        ));
    }

}
