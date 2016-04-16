<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @Route("/{id}/addEmail",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $email = new Email();

        $form = $this->createEmailForm($email);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/addEmail",
     *        requirements={"id"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);
        $email = new Email();
        $form = $this->createEmailForm($email);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($email);
            $email->setContact($contact);
            $contact->addEmail($email);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyEmail/{emailId}",
     *        requirements={"id"="\d+", "emailId"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function modifyAction(Request $request, $id, $emailId)
    {
        $email = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Email')
            ->find($emailId);

        if (!$email) {
            throw $this->createNotFoundException('No such email');
        }

        $form = $this->createEmailForm($email);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyEmail/{emailId}",
     *        requirements={"id"="\d+", "emailId"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function saveChangesAction(Request $request, $id, $emailId)
    {

        $email = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Email')
            ->find($emailId);

        if (!$email) {
            throw $this->createNotFoundException('No such email');
        }

        $form = $this->createEmailForm($email);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $id]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route ("/{id}/deleteEmail/{emailId}",
     *        requirements={"id"="\d+", "emailId"="\d+"})
     * @Method ("GET")
     */
    public function deleteAction(Request $request, $id, $emailId)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $email = $this->getDoctrine()->getRepository('CodersLabBundle:Email')->find($emailId);

        if (!$email) {
            throw $this->createNotFoundException('Email not found');
        }

        $contact->removeEmail($email);

        $entitymanager = $this->getDoctrine()->getManager();

        $entitymanager->remove($email);
        $entitymanager->flush();

        return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
    }

    private function createEmailForm($email)
    {
        $form = $this->createFormBuilder($email)
            ->add('address')
            ->add('type')
            ->add('Submit', 'submit')
            ->getForm();
        return $form;
    }
}
