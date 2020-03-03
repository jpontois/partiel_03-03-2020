<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Validate;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="login",message="Le login est déjà utilisé par un autre profil")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Validate\Regex("/^[a-zA-Z0-9_]{3,16}$/",message="L'email est invalide")
     * @Validate\NotBlank(message="L'email doit être renseigné")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     * @Validate\Length(
     *      min="4", minMessage="Le mot de passe doit faire au minimum 4 caractères",
     *      max="255", maxMessage="Nous ne sommes malheuresement pas en mesure d'assurer de telles niveau de sécurité. Aller, un petit effort, un mot de passe de 255 caractères max c'est déjà suffisant non ? ;)"
     * )
     * */
    private $password;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt() {
        return null;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    public function getUserName() {}
    public function eraseCredentials() {}
}
