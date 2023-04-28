<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

/**
 * JWT User
 * 
 * Data Transfert Object of a user authenticated by a JWT Token
 */
class JWTUser implements JWTUserInterface
{
    /**
     * @param integer $iat the date of issued (in timestamp)
     * @param integer $exp the expiration time (in timestamp)
     * @param array $roles list of user roles
     * @param string $id unique id of user
     * @param string $username username of user
     * @param string $requestIp user ip
     */
    public function __construct(
        private int $iat,
        private int $exp,
        private int $id,
        private string $username,
        private array $roles,
        private string $requestIp
    ) {
    }

    /**
     * Create an User from the payload given by JWT
     *
     * @param int $id
     * @param array $payload
     * @return self
     */
    public static function createFromPayload($id, array $payload): self
    {
        return new self(
            $payload['iat'],
            $payload['exp'],
            $payload['id'],
            $payload['username'],
            $payload['roles'],
            $payload['requestIp'],
        );
    }

    /**
     * Get Data Payload as Array
     *
     * @return array
     */
    public function toArray(): array
    {
        $payload['iat'] = $this->iat;
        $payload['exp'] = $this->exp;
        $payload['roles'] = $this->roles;
        $payload['id'] = $this->id;
        $payload['username'] = $this->username;
        $payload['requestIp'] = $this->requestIp;

        return $payload;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    /**
     * @see UserInterface
     * @return array|Collection<int, Role>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * Get the value of iat
     * 
     * iat is the date of issued (in timestamp)
     */
    public function getIat()
    {
        return $this->iat;
    }

    /**
     * Get the value of exp
     * 
     * exp is the expiration time (in timestamp)
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of email
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of requestIp
     */
    public function getRequestIp()
    {
        return $this->requestIp;
    }
}
