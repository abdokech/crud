<?php
namespace Swe\CrudBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Swe\CrudBundle\Form\Handler\Interfaces\FormHandlerInterface;
use Swe\CrudBundle\Form\Handler\Interfaces\FormHandlerStrategyInterface;
use Swe\CrudBundle\Controller\Configuration;

class ResourceFormHandler implements FormHandlerInterface
{
    /**
     * @var string
     */
    private $message = "";
    
    /**
     * @var Configuration $cofig
     */
    protected $config;

    /**
     * @var FormInterface $form
     */
    protected $form;

    /**
     * @var ResourceFormHandlerStrategy $resourceFormHandlerStrategy
     */
    protected $resourceFormHandlerStrategy;

    /**
     * @var ResourceFormHandlerStrategy $newResourceFormHandlerStrategy
     */
    protected $newResourceFormHandlerStrategy;

    /**
     * @var ResourceFormHandlerStrategy $updateResourceFormHandlerStrategy
     */
    protected $updateResourceFormHandlerStrategy;

    
    public function setConfiguration(Configuration $config){
        $this->config=$config;
        
    }
    public function setNewResourceFormHandlerStrategy(FormHandlerStrategyInterface $nafhs) {
        $this->newResourceFormHandlerStrategy = $nafhs;
    }

    public function setUpdateResourceFormHandlerStrategy(FormHandlerStrategyInterface $uafhs) {
        $this->updateResourceFormHandlerStrategy = $uafhs;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Object|null $post
     * @return Post
     */
    public function processForm($post = null)
    {
        if (is_null($post)) {
            $post = $this->config->createNew();
            $this->resourceFormHandlerStrategy = $this->newResourceFormHandlerStrategy;
        } else {
            $this->resourceFormHandlerStrategy = $this->updateResourceFormHandlerStrategy;
        }

        $this->form = $this->createForm($post);

        return $post;
    }

    /**
     * @param Object $post
     * @return FormInterface
     */
    public function createForm($post)
    {
        return $this->resourceFormHandlerStrategy->createForm($post);
    }

    /**
     * @param FormInterface $form
     * @param Object $post
     * @param Request $request
     * @return bool
     */
    public function handleForm(FormInterface $form, $post, Request $request)
    {
        if (
            (null === $post->getId() && $request->isMethod('POST'))
            || (null !== $post->getId() && $request->isMethod('PUT'))
        ) {
            $form->handleRequest($request);

            if (!$form->isValid()) {
                return false;
            }

            $this->message = $this->resourceFormHandlerStrategy->handleForm($request, $post);

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
        return $this->resourceFormHandlerStrategy->createView();
    }
}
