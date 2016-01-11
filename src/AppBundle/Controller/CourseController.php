<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use AppBundle\Entity;

/**
 * Description of CourseController
 *
 * @author eduardoledo
 * @Route("/course")
 */
class CourseController extends Controller {

    /**
     * @Route("/{slug}", options={"expose"=true})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Request $request, Entity\School $school) {
        return [
            'school' => $school
        ];
    }

    /**
     * @Route("/{slug}/list.json")
     * @Template()
     */
    public function listAction(Request $request, Entity\School $school) {
        $draw = $request->get('draw', 0);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search');

        $recordsTotal = $school;

        $self = $this;

        $user = ($mine) ? $this->getUser() : null;

        $items = $this->getDoctrine()
                ->getRepository('AppBundle:School')
                ->filter($search['value'], [], $length, $start, $user);

        $data = array_map(function(Entity\School $item) use ($self) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'legalName' => $item->getLegalName(),
                'status' => $item->getStatus(),
                'slug' => $item->getSlug(),
                'mine' => $item->getOwner()->getId() == $self->getUser()->getId()
            ];
        }, $items->toArray());

        $recordsFiltered = count($items);

        return new JsonResponse([
            'get' => $request->query->all(),
            'post' => $request->request->all(),
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

}
