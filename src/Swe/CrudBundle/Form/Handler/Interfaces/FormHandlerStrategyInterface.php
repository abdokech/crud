<?php
namespace Swe\CrudBundle\Form\Handler\Interfaces;

use Symfony\Component\HttpFoundation\Request;


interface FormHandlerStrategyInterface
{
    public function handleForm(Request $request,$resource);

    public function createForm($resource);

    public function createView();
}
