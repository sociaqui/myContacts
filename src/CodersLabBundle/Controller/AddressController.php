<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends Controller
{
    /**
     * @Route("/{id}/addAddress",
     *        requirements={"id"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function newAction(Request $request, $id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if($contact->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $address = new Address();

        $form = $this->createAddressForm($address);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/addAddress",
     *        requirements={"id"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if($contact->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $address = new Address();
        $form = $this->createAddressForm($address);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->persist($address);
            $address->setContact($contact);
            $contact->addAddress($address);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyAddress/{addressId}",
     *        requirements={"id"="\d+", "addressId"="\d+"})
     * @Method ("GET")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function modifyAction(Request $request, $id, $addressId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if($contact->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $address = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Address')
            ->find($addressId);

        if (!$address) {
            throw $this->createNotFoundException('No such address');
        }

        $form = $this->createAddressForm($address);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyAddress/{addressId}",
     *        requirements={"id"="\d+", "addressId"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function saveChangesAction(Request $request, $id, $addressId)
    {

        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if($contact->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $address = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Address')
            ->find($addressId);

        if (!$address) {
            throw $this->createNotFoundException('No such address');
        }

        $form = $this->createAddressForm($address);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('coderslab_contact_show', ['id' => $id]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route ("/{id}/deleteAddress/{addressId}",
     *        requirements={"id"="\d+", "addressId"="\d+"})
     * @Method ("GET")
     */
    public function deleteAction(Request $request, $id, $addressId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        if($contact->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('Cannot modify contacts that do not belong to you');
        }

        $address = $this->getDoctrine()->getRepository('CodersLabBundle:Address')->find($addressId);

        if (!$address) {
            throw $this->createNotFoundException('Address not found');
        }

        $contact->removeAddress($address);

        $entitymanager = $this->getDoctrine()->getManager();

        $entitymanager->remove($address);
        $entitymanager->flush();

        return $this->redirectToRoute('coderslab_contact_show', ['id' => $contact->getId()]);
    }

    private function createAddressForm($address)
    {
        $form = $this->createFormBuilder($address)
            ->add('city')
            ->add('street')
            ->add('houseNumber')
            ->add('appartamentNumber')
            ->add('Submit', 'submit')
            ->getForm();
        return $form;
    }
}
