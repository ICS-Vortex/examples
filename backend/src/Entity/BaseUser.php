<?php

namespace App\Entity;

use App\Includes\Timestamp;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * BaseUser
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class BaseUser implements UserInterface, Serializable, PasswordAuthenticatedUserInterface
{
    use Timestamp;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     * @Groups({"server", "api_tournaments", "api_open_servers", "api_profile", "api_online", "api_sorties", "api_dogfights", "api_races", "api_admin","tournament_coupons"})
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string")
     * @Groups({"api_admin","server", "api_tournaments", "api_open_servers", "api_profile", "api_online", "api_sorties", "api_dogfights", "api_races","tournament_coupons"})
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", nullable=true, unique=true)
     * @Groups({"api_admin","server", "api_tournaments", "api_profile", "api_profile","tournament_coupons"})
     */
    protected $email;
    /**
     * @var string
     * @ORM\Column(name="country", type="string", length=64, nullable=true)
     * @Groups({"api_admin","server", "api_tournaments", "api_open_servers", "api_profile", "api_online", "api_sorties", "api_dogfights", "api_races","tournament_coupons"})
     */
    protected $country;
    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_tournaments", "api_profile"})
     */
    protected $location;
    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     * @Groups({"api_admin","api_profile"})
     */
    protected $facebookId;
    /**
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", nullable=true)
     * @Groups({"api_admin","api_profile"})
     */
    protected $googleId;
    /**
     * @ORM\Column(name="avatar", type="string", nullable=true)
     * @Groups({"api_admin","api_open_servers", "api_profile", "api_dogfights"})
     */
    protected $avatar;
    protected $file;
    /**
     * @Vich\UploadableField(mapping="avatar_images", fileNameProperty="avatar")
     * @var File
     */
    private $avatarFile;

    /**
     * @var string
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;
    /**
     *
     * @ORM\Column(name="password", type="string", nullable=true)
     */
    protected $password;
    /**
     * @var string
     * @ORM\Column(name="name", type="string", nullable=true)
     * @Groups({"api_admin","server", "api_tournaments", "api_open_servers", "api_profile", "api_dogfights"})
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="surname", type="string", nullable=true)
     * @Groups({"api_admin","server", "api_tournaments", "api_open_servers", "api_profile", "api_dogfights"})
     */
    protected $surname;
    /**
     * @var string
     * @ORM\Column(name="roles", type="json")
     * @Groups("api_admin","server")
     */
    protected $roles = [];
    /**
     * @var boolean
     * @ORM\Column(name="enabled", type="boolean")
     * @Groups({"api_open_servers"})
     */
    protected $enabled = false;
    /**
     * @var string
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    protected $phone;
    /**
     * @var string
     * @ORM\Column(name="address", type="string", nullable=true)
     */
    protected $address;

    protected $oldPassword;

    /**
     * @return mixed
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @param mixed $oldPassword
     */
    public function setOldPassword($oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return empty($this->avatar) ? 'avatar.png' : $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @param $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function addRole($role)
    {
        $roles = $this->roles;
        $roles[] = $role;
        $this->roles = array_unique($roles);
    }

    public function removeRole($role)
    {
        $roles = $this->roles;
        foreach ($roles as $key => $row) {
            if ($row === $role) {
                unset($roles[$key]);
            }
        }
        $this->roles = array_unique($roles);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /** @see \Serializable::serialize() */
    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->enabled,
        ]);
    }

    /** @param $serialized
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->enabled,
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }


    public function isAccountNonExpired() : bool
    {
        return true;
    }

    public function isAccountNonLocked() : bool
    {
        return $this->enabled;
    }

    public function isCredentialsNonExpired() : bool
    {
        return true;
    }

    public function isEnabled() : bool
    {
        return $this->enabled;
    }

    public function isActive() : bool
    {
        return $this->enabled;
    }


    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getFullName()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function getNickname()
    {
        return $this->username;
    }

    public function setNickname($nickname)
    {
        $this->setUsername($nickname);
    }

    public function isEqualTo(UserInterface $user)
    {
        return $user->getId() === $this->id;
    }

    public function getCallsign()
    {
        return $this->username;
    }

    public function setCallsign($nickname)
    {
        $this->setUsername($nickname);
    }

    public function setEnabled($enabled = true)
    {
        $this->enabled = $enabled;
    }

    /**
     */
    public function getRoles()
    {
        return $this->roles ?? [];
    }

    /**
     * @return string|null
     */
    public function getFacebookId(): ?string
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId(string $facebookId): void
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string|null
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
    }


    /**
     * @return File|null
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * @param File|null $image
     */
    public function setAvatarFile(File $image = null): void
    {
        $this->avatar = $image;
        if ($image) {
            $this->setUpdatedAt(new DateTime('now'));
        }
    }
}
