<?php
namespace AppBundle\Form\Handler;

use AppBundle\Entity\Manager\Interfaces\PostManagerInterface;
use AppBundle\Form\Type\PostType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactoryInterface;

class UpdatePostFormHandlerStrategy extends AbstractPostFormHandlerStrategy
{
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
        TranslatorInterface $translator,
        PostManagerInterface $postManager,
        FormFactoryInterface $formPost,
        RouterInterface $router
    )
    {
        $this->translator = $translator;
        $this->PostManager = $postManager;
        $this->formPost = $formPost;
        $this->router = $router;
    }

    public function createForm(Post $post)
    {
        $this->form = $this->formPost->create(PostType::class, $post, array(
         //   'action' => $this->router->generate('post_edit', array('id' => $post->getId())),
            'method' => 'PUT',
        ));

        return $this->form;
    }

    public function handleForm(Request $request, Post $post)
    {
        $this->PostManager->save($post, false, true);

        return $this->translator
            ->trans('acteur.modifier.succes', array(
                '%nom%' => $post->getName(),
            ));
    }
}
