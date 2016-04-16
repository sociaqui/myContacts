<?php

namespace CodersLabBundle\Controller;

use CodersLabBundle\Entity\Address;
use CodersLabBundle\Entity\Contact;
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
    public function newAddressAction(Request $request)
    {
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
    public function createAddressAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);
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
    public function modifyAddressAction(Request $request, $id, $addressId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Address')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
        }

        $form = $this->createContactForm($contact);

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/modifyAddress/{addressId}",
     *        requirements={"id"="\d+", "addressId"="\d+"})
     * @Method ("POST")
     * @Template("CodersLabBundle:Contact:form.html.twig")
     */
    public function saveAddressChangesAction(Request $request, $id, $addressId)
    {
        $contact = $this
            ->getDoctrine()
            ->getRepository('CodersLabBundle:Contact')
            ->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('No such contact');
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
     * @Route ("/{id}/deleteAddress/{addressId}",
     *        requirements={"id"="\d+", "addressId"="\d+"})
     * @Method ("GET")
     */
    public function deleteAddressAction(Request $request, $id, $addressId)
    {
        $contact = $this->getDoctrine()->getRepository('CodersLabBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Contact not found');
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
