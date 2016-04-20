<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Grouping;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/group")
 */
class GroupingController extends Controller
{
    /**
     * @Route("/new")
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $grouping = new Grouping();

        $form = $this->createGroupingForm($grouping);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/new")
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $grouping = new Grouping();
        $form = $this->createGroupingForm($grouping);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $grouping->setUser($this->getUser());
            $this->getDoctrine()->getManager()->persist($grouping);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_grouping_showall');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modify",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function modifyAction(Request $request, $id)
    {
        $grouping = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Grouping')
            ->find($id);

        if (!$grouping) {
            throw $this->createNotFoundException('No such group');
        }

        if($grouping->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify groups that do not belong to you');
        }

        $form = $this->createGroupingForm($grouping);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modify",
     *        requirements={"id"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function saveChangesAction(Request $request, $id)
    {
        $grouping = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Grouping')
            ->find($id);

        if (!$grouping) {
            throw $this->createNotFoundException('No such group');
        }

        if($grouping->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify groups that do not belong to you');
        }

        $form = $this->createGroupingForm($grouping);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_grouping_showall');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route ("/{id}/delete",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $grouping = $this->getDoctrine()->getRepository('CodersLabBundle:Grouping')->find($id);

        if (!$grouping) {
            throw $this->createNotFoundException('Group not found');
        }

        if($grouping->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot delete groups that do not belong to you');
        }

        $entitymanager = $this->getDoctrine()->getManager();

        $entitymanager->remove($grouping);
        $entitymanager->flush();

        return $this->redirectToRoute("coderslab_grouping_showall");
    }

    /**
     * @Route("/{id}",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:showAll.html.twig")
     */
    public function showAction(Request $request, $id)
    {
        $grouping = $this->getDoctrine()->getRepository('CodersLabBundle:Grouping')->find($id);

        if (!$grouping) {
            throw $this->createNotFoundException('Group not found');
        }

        if($grouping->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot see groups that do not belong to you');
        }

        return ['contacts' => $grouping->getContacts()];
    }

    /**
     * @Route("/")
     * @Method ("GET")
     * @Template()
     */
    public function showAllAction(Request $request)
    {
        $user=$this->getUser()->getId();

        return ['groupings' => $this->getDoctrine()->getRepository('CodersLabBundle:Grouping')->findBy(['user' => $user], ['name' => 'ASC'])];
    }

    private function createGroupingForm($grouping)
    {
        $form = $this->createFormBuilder($grouping)
            ->add('name')
            ->add('description')
            ->add('contacts', 'entity', [
                'class' => 'CodersLabBundle:Contact',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.user = :user')
                        ->setParameter('user', $this->getUser())
                        ->orderBy('c.surname', 'ASC');
                },
                'multiple'=>true,
                'expanded'=>true
            ])
            ->add('Submit', 'submit')
            ->getForm();
        return $form;
    }

}