<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{

    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     * message = "L'adresse email '{{ value }}' n'est pas une adresse email valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     * min = 8,
     * max = 4096,
     * minMessage = "Votre mot de passe doit contenir au minimum {{ limit }} caractères.",
     * maxMessage = "Votre mot de passe ne doit pas contenir plus de {{ limit }} caractères.",
     * allowEmptyString = false
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Regex(
    * pattern="#^/\d/#",
    * match=false,
    * message="Vous ne pouvez mettre de chiffres dans cette saisie"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Regex(
     * pattern="#^/\d/#",
     * match=false,
     * message="Vous ne pouvez mettre de chiffres dans cette saisie"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     * @Assert\Regex(
     * pattern="/^[0-9]*$/",
     * match=true,
     * message="Vous ne pouvez mettre de lettres dans cette saisie"
     * )
     */
    private $road_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $road;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Regex(
     * pattern="/^[0-9]*$/",
     * match=true,
     * message="Vous ne pouvez mettre de lettres dans cette saisie"
     * )
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Regex(
     * pattern="#^/\d/#",
     * match=false,
     * message="Vous ne pouvez mettre de chiffres dans cette saisie"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Regex(
     * pattern="/^[0-9]*$/",
     * match=true,
     * message="Vous ne pouvez mettre de lettres dans cette saisie"
     * )
     */
    private $phone_number;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $receipt_address;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getRoadNumber(): ?string
    {
        return $this->road_number;
    }

    public function setRoadNumber(?string $road_number): self
    {
        $this->road_number = $road_number;

        return $this;
    }

    public function getRoad(): ?string
    {
        return $this->road;
    }

    public function setRoad(?string $road): self
    {
        $this->road = $road;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getReceiptAddress(): ?string
    {
        return $this->receipt_address;
    }

    public function setReceiptAddress(?string $receipt_address): self
    {
        $this->receipt_address = $receipt_address;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }

}
