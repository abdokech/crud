<?php

namespace Swe\CrudBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Swe\CrudBundle\Form\Handler\Interfaces\FormHandlerStrategyInterface;

abstract class AbstractResourceFormHandlerStrategy implements FormHandlerStrategyInterface
{

    /**
     * @var Form $form
     */
    protected $form;

    public function createView()
    {
        return $this->form->createView();
    }

    abstract public function handleForm(Request $request, $resource);

    abstract public function createForm($resource);


}