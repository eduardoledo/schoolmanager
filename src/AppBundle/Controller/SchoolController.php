<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type;
use AppBundle\Entity;

/**
 * Description of SchoolController
 *
 * @author eduardoledo
 * @Route("/school")
 */
class SchoolController extends Controller
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @Route("/")
     * @Route("/mine", name="app_school_index_mine", defaults={"mine"=true})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function indexAction(Request $request, $mine = false)
    {
        $this->logger = $this->get('logger');

        $items = [];

        return [
            'mine' => $mine,
            'items' => $items
        ];
    }

    /**
     * @Route("/{slug}/dashboard")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function dashboardAction(Request $request, $slug)
    {
        
    }

    /**
     * @Route("/add")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $school = (new Entity\School())
                ->setOwner($this->getUser());

        $form = $this->createFormBuilder($school)
                ->add('name', Type\TextType::class)
                ->add('legalName', Type\TextType::class)
                ->add('submit', Type\SubmitType::class)
                ->getForm();

        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            $school = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($school);
            $em->flush();
            $this->addFlash("success", "School created successfully");

            return $this->redirectToRoute('app_school_index', ['mine' => true]);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/{slug}/edit", options={"expose"=true})
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function editAction(Request $request, Entity\School $school)
    {
        die($school . '');

        return [];
    }

    /**
     * @Route("/list.json")
     * @Secure(roles="ROLE_USER")
     */
    public function listAction(Request $request)
    {
        $mine = filter_var($request->get('mine'), FILTER_VALIDATE_BOOLEAN);
        $draw = $request->get('draw', 0);
        $start = $request->get('start', 0);
        $length = $request->get('length', 10);
        $search = $request->get('search');

        $recordsTotal = $this->getDoctrine()
                ->getRepository('AppBundle:School')
                ->count();

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
