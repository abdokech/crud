<?php
namespace AppBundle\Form\Handler;

use AppBundle\Entity\Manager\Interfaces\UserManagerInterface;
use AppBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Swe\CrudBundle\Form\Handler\AbstractResourceFormHandlerStrategy;

class NewUserFormHandlerStrategy extends AbstractResourceFormHandlerStrategy
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * @var FormFactoryInterface
     */
    private $formUser;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Service of translation
     * @param UserManagerInterface $userManager
     * @param FormFactoryInterface $formUser
     * @param RouterInterface $router
     */
    public function __construct
    (
        TranslatorInterface $translator,
        UserManagerInterface $userManager,
        FormFactoryInterface $formUser,
        RouterInterface $router
    )
    {
        $this->translator = $translator;
        $this->userManager = $userManager;
        $this->formUser = $formUser;
        $this->router = $router;
    }

    public function createForm($resource)
    {
        $this->form = $this->formUser->create(UserType::class, $resource, array(
            'action' => $this->router->generate('user_new'),
            'method' => 'USER',
        ));

        return $this->form;
    }

    public function handleForm(Request $request,$resource)
    {
        $this->userManager->save($resource, true, true);

        return $this->translator
            ->trans('acteur.ajouter.succes', array(
                '%nom%' => $resource->getName(),
            ));
    }
}
