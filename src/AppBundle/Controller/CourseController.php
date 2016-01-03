<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity;

/**
 * Description of CourseController
 *
 * @author eduardoledo
 * @Route("/course")
 */
class CourseController extends Controller
{

    /**
     * @Route("/{slug}", options={"expose"=true})
     * @Template()
     */
    public function indexAction(Request $request, Entity\School $school)
    {
        if ($school instanceof Entity\School) {
            die($school);
        }
        return [];
    }

}
