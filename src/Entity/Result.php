<?php
/**
 * PHP version 7.2
 * src\Entity\Result.php
 *
 * @category Entities
 * @package  MiW\Results\Entity
 * @author   Javier Gil <franciscojavier.gil@upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Result
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name    = "results",
 *     indexes = {
 *          @ORM\Index(name="FK_USER_ID_idx", columns={ "user_id" })
 *     }
 * )
 */
class Result implements \JsonSerializable
{
    /**
     * Result id
     *
     * @var integer
     *
     * @ORM\Column(
     *     name     = "id",
     *     type     = "integer",
     *     nullable = false
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    private $id;

    /**
     * Result value
     *
     * @var integer
     *
     * @ORM\Column(
     *     name     = "result",
     *     type     = "integer",
     *     nullable = false
     *     )
     */
    private $result;

    /**
     * Result user
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(
     *          name                 = "user_id",
     *          referencedColumnName = "id",
     *          onDelete             = "cascade"
     *     )
     * })
     */
    private $user;

    /**
     * Result time
     *
     * @var \DateTime
     *
     * @ORM\Column(
     *     name     = "time",
     *     type     = "datetime",
     *     nullable = false
     *     )
     */
    private $time;

    /**
     * Result constructor.
     *
     * @param int $result result
     * @param User $user user
     * @param \DateTime $time time
     */
    public function __construct(
        int $result = 0,
        User $user = null,
        \DateTime $time = null
    )
    {
        $this->id = 0;
        $this->result = $result;
        $this->user = $user;
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Implements __toString()
     *
     * @return string
     * @link   http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        return sprintf(
            'id: %3d result: %3d time: %s user: [%s]',
            $this->id,
            $this->result,
            $this->getTimeFormatted(),
            $this->user
        );
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link   http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since  5.4.0
     */
    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->id,
            'result' => $this->result,
            'time' => $this->getTimeFormatted(),
            'user' => $this->user
        );
    }

    /**
     * @return int
     */
    public function getResult(): int
    {
        return $this->result;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    public function getTimeFormatted(): string {
        return $this->time->format('Y-m-d H:i:s');
    }

    /**
     * @param int $result
     */
    public function setResult(int $result): void
    {
        $this->result = $result;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }

    public function merge(Result $result): void
    {
        $this->setResult($result->getResult() ?? $this->getResult());
        $this->setTime($result->getTime() ?? $this->getTime());
    }

}