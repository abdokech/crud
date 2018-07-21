<?php

namespace AppBundle\Form\Handler;

use AppBundle\Entity\Post;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;


abstract class AbstractPostFormHandlerStrategy implements PostFormHandlerStrategy
{

    /**
     * @var Form $form
     */
    protected $form;

    public function createView()
    {
        return $this->form->createView();
    }

    abstract public function handleForm(Request $request, Post $post);

    abstract public function createForm(Post $post);


}