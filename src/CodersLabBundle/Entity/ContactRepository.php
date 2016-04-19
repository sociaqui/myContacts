<?php

namespace CodersLabBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends EntityRepository
{
    public function findByParameters(array $parameters)
    {
        return $this
            ->getEntityManager()
            ->createQuery("SELECT contact FROM CodersLabBundle:Contact contact
                                    LEFT JOIN contact.addresses address
                                    LEFT JOIN contact.emails email
                                    LEFT JOIN contact.phones phone
                                    WHERE COALESCE(contact.name,'') LIKE :name
                                    AND COALESCE(contact.surname,'') LIKE :surname
                                    AND COALESCE(contact.description,'') LIKE :description
                                    AND COALESCE(address.city,'') LIKE :city
                                    AND COALESCE(email.address,'') LIKE :email
                                    AND COALESCE(phone.number,'') LIKE :phone")
            ->setParameter('name', '%' . $parameters['name'] . '%')
            ->setParameter('surname', '%' . $parameters['surname'] . '%')
            ->setParameter('description', '%' . $parameters['description'] . '%')
            ->setParameter('city', '%' . $parameters['city'] . '%')
            ->setParameter('email', '%' . $parameters['email'] . '%')
            ->setParameter('phone', '%' . $parameters['phone'] . '%')
            ->getResult();
    }
}
