<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return [];
    }

    /**
     * @Route("/home", name="dashboard")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function dashboardAction(Request $request)
    {
        return [];
    }

    /**
     * @Template()
     */
    public function sidebarAction(Request $request)
    {
        return [];
    }

    /**
     * @Template()
     */
    public function headerAction()
    {
        return [];
    }

}
