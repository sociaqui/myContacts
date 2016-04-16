<?php

namespace CodersLabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Address
 *
 * @ORM\Table(name="addresses")
 * @ORM\Entity(repositoryClass="CodersLabBundle\Entity\AddressRepository")
 */
class Address
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=150)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=120)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="houseNumber", type="string", length=10)
     */
    private $houseNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="appartamentNumber", type="string", length=10)
     */
    private $appartamentNumber;

    /**
     * @ORM\ManyToOne(targetEntity="Contact", inversedBy="addresses")
     */
    private $contact;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set houseNumber
     *
     * @param string $houseNumber
     * @return Address
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    /**
     * Get houseNumber
     *
     * @return string 
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * Set appartamentNumber
     *
     * @param string $appartamentNumber
     * @return Address
     */
    public function setAppartamentNumber($appartamentNumber)
    {
        $this->appartamentNumber = $appartamentNumber;

        return $this;
    }

    /**
     * Get appartamentNumber
     *
     * @return string 
     */
    public function getAppartamentNumber()
    {
        return $this->appartamentNumber;
    }

    /**
     * Set contact
     *
     * @param \CodersLabBundle\Entity\Contact $contact
     * @return Address
     */
    public function setContact(\CodersLabBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \CodersLabBundle\Entity\Contact 
     */
    public function getContact()
    {
        return $this->contact;
    }
}
