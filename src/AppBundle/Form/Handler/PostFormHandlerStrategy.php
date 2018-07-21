<?php
namespace AppBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Post;

interface PostFormHandlerStrategy
{
    public function handleForm(Request $request, Post $post);

    public function createForm(Post $post);

    public function createView();
}
