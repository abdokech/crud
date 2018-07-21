<?php
namespace AppBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;

class PostFormHandler implements FormHandlerInterface
{
    /**
     * @var string
     */
    private $message = "";

    /**
     * @var FormInterface $form
     */
    protected $form;

    /**
     * @var PostFormHandlerStrategy $postFormHandlerStrategy
     */
    protected $postFormHandlerStrategy;

    /**
     * @var PostFormHandlerStrategy $newPostFormHandlerStrategy
     */
    protected $newPostFormHandlerStrategy;

    /**
     * @var PostFormHandlerStrategy $updatePostFormHandlerStrategy
     */
    protected $updatePostFormHandlerStrategy;

    public function setNewPostFormHandlerStrategy(PostFormHandlerStrategy $nafhs) {
        $this->newPostFormHandlerStrategy = $nafhs;
    }

    public function setUpdatePostFormHandlerStrategy(PostFormHandlerStrategy $uafhs) {
        $this->updatePostFormHandlerStrategy = $uafhs;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Post|null $post
     * @return Post
     */
    public function processForm(Post $post = null)
    {
        if (is_null($post)) {
            $post = new Post();
            $this->PostFormHandlerStrategy = $this->newPostFormHandlerStrategy;
        } else {
            $this->PostFormHandlerStrategy = $this->updatePostFormHandlerStrategy;
        }

        $this->form = $this->createForm($post);

        return $post;
    }

    /**
     * @param Post $post
     * @return FormInterface
     */
    public function createForm(Post $post)
    {
        return $this->PostFormHandlerStrategy->createForm($post);
    }

    /**
     * @param FormInterface $form
     * @param Post $post
     * @param Request $request
     * @return bool
     */
    public function handleForm(FormInterface $form, Post $post, Request $request)
    {
        if (
            (null === $post->getId() && $request->isMethod('POST'))
            || (null !== $post->getId() && $request->isMethod('PUT'))
        ) {
            $form->handleRequest($request);

            if (!$form->isValid()) {
                return false;
            }

            $this->message = $this->PostFormHandlerStrategy->handleForm($request, $post);

            return true;
        }
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return mixed
     */
    public function createView()
    {
        return $this->PostFormHandlerStrategy->createView();
    }
}
