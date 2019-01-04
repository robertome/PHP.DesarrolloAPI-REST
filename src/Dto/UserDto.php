<?php
/**
 * Created by PhpStorm.
 * User: rmartine
 * Date: 30/12/2018
 * Time: 18:10
 */

namespace App\Dto;


use App\Util\Validation;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserDto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var boolean
     */
    private $isAdmin;

    /**
     * @var string
     */
    private $password;

    /**
     * UserDto constructor.
     *
     * @param int $id id
     * @param string $username username
     * @param string $email email
     * @param string $password password
     * @param bool $enabled enabled
     * @param bool $isAdmin isAdmin
     */
    public function __construct(
        int $id = 0,
        ?string $username = '',
        ?string $email = '',
        ?string $password = '',
        bool $enabled = true,
        bool $isAdmin = false
    )
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->enabled = $enabled;
        $this->isAdmin = $isAdmin;
    }

    /**
     *
     */
    public function validate()
    {
        Validation::notBlank($this->username, 'username');
        Validation::notBlank($this->email, 'email');
        Validation::notBlank($this->password, 'password');
    }

    /**
     * @param User $user
     * @return User
     */
    public function toEntity(User $user): User
    {
        $user->setUsername($this->username);
        $user->setPassword($this->password);
        $user->setEmail($this->email);
        $user->setEnabled($this->enabled);
        $user->setIsAdmin($this->isAdmin);

        return $user;
    }

    public static function fromRequest(Request $request): UserDto
    {
        $json = json_decode($request->getContent(), true);

        return new UserDto(
            $json['id'] ?? 0,
            $json['username'] ?? null,
            $json['email'] ?? null,
            $json['password'] ?? null,
            $json['enabled'] ?? false,
            false
        );
    }

}