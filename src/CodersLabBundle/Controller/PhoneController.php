<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{
    /**
     * @Route("/{id}/addPhone",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function newAction(Request $request)
    {
        $phone = new Phone();

        $form = $this->createPhoneForm($phone);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/addPhone",
     *        requirements={"id"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);
        $phone = new Phone();
        $form = $this->createPhoneForm($phone);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($phone);
            $phone->setContact($contact);
            $contact->addPhone($phone);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyPhone/{phoneId}",
     *        requirements={"id"="\d+", "phoneId"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function modifyAction(Request $request, $id, $phoneId)
    {
        $phone = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Phone')
            ->find($phoneId);

        if (!$phone) {
            throw $this->createNotFoundException('No such phone');
        }

        $form = $this->createPhoneForm($phone);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyPhone/{phoneId}",
     *        requirements={"id"="\d+", "phoneId"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function saveChangesAction(Request $request, $id, $phoneId)
    {

        $phone = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Phone')
            ->find($phoneId);

        if (!$phone) {
            throw $this->createNotFoundException('No such phone');
        }

        $form = $this->createPhoneForm($phone);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $id]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route ("/{id}/deletePhone/{phoneId}",
     *        requirements={"id"="\d+", "phoneId"="\d+"})
     * @Method ("GET")
     */
    public function deleteAction(Request $request, $id, $phoneId)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
        }

        $phone = $this->getDoctrine()->getRepository('CodersLabBundle:Phone')->find($phoneId);

        if (!$phone) {
            throw $this->createNotFoundException('Phone not found');
        }

        $contact->removePhone($phone);

        $entitymanager = $this->getDoctrine()->getManager();

        $entitymanager->remove($phone);
        $entitymanager->flush();

        return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
    }

    private function createPhoneForm($phone)
    {
        $form = $this->createFormBuilder($phone)
            ->add('number')
            ->add('type')
            ->add('Submit', 'submit')
            ->getForm();
        return $form;
    }
}