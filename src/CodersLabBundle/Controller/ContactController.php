<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Contact;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @Route("/new")
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createContactForm($contact);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/new")
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function createAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createContactForm($contact);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $contact->setUser($this->getUser());
            $this->getDoctrine()->getManager()->persist($contact);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
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
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if ($contact->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $form = $this->createContactForm($contact);

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
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if ($contact->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $form = $this->createContactForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/addToGroup",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function addGroupAction(Request $request, $id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if ($contact->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $form = $this->createGroupForm($contact);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/addToGroup",
     *        requirements={"id"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function saveGroupAction(Request $request, $id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if ($contact->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $form = $this->createGroupForm($contact);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
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
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        if ($contact->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Cannot delete contacts that do not belong to you');
        }

        $entitymanager = $this->getDoctrine()->getManager();

        $entitymanager->remove($contact);
        $entitymanager->flush();

        return $this->redirectToRoute("coderslab_contact_showall");
    }

    /**
     * @Route("/{id}",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        if ($contact->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Cannot see contacts that do not belong to you');
        }

        return ['contact' => $contact];
    }

    /**
     * @Route("/")
     * @Method ("GET")
     * @Template()
     */
    public function showAllAction(Request $request)
    {
        $user = $this->getUser();

        return ['contacts' => $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->findBy(['user' => $user], ['surname' => 'ASC'])];
    }

    /**
     * @Route("/")
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:showAll.html.twig")
     */
    public function showSomeAction(Request $request)
    {
        $parameters = [
            'user' => $this->getUser()->getId(),
            'name' => $request->request->get('form')['name'],
            'surname' => $request->request->get('form')['surname'],
            'description' => $request->request->get('form')['description'],
            'email' => $request->request->get('form')['email'],
            'city' => $request->request->get('form')['city'],
            'phone' => $request->request->get('form')['phone'],
        ];
        $contacts = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->findByParameters($parameters);
        return ['contacts' => $contacts];
    }

    /**
     * @Route("/search")
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function searchAction(Request $request)
    {
        $form = $this->createSearchForm();

        return ['form' => $form->createView()];
    }

    private function createContactForm($contact)
    {
        $form = $this->createFormBuilder($contact)
            ->add('name')
            ->add('surname')
            ->add('description')
            ->add('groups', 'entity', [
                'class' => 'CodersLabBundle:Grouping',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->where('g.user = :user')
                        ->setParameter('user', $this->getUser())
                        ->orderBy('g.name', 'ASC');
                },
                'multiple'=>true,
                'expanded'=>true,
                'choice_label' => 'name'
            ])
            ->add('Submit', 'submit')
            ->getForm();
        return $form;
    }

    private function createSearchForm()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl("coderslab_contact_showall"))
            ->add('name', 'text', ['required' => false])
            ->add('surname', 'text', ['required' => false])
            ->add('description', 'text', ['required' => false])
            ->add('city', 'text', ['required' => false])
            ->add('email', 'text', ['required' => false])
            ->add('phone', 'text', ['required' => false])
            ->add('Submit', 'submit')
            ->getForm();
        return $form;
    }

}