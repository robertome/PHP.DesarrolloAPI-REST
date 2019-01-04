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

namespace App\Dto;


use App\Util\Validation;
use Symfony\Component\HttpFoundation\Request;

class ResultDto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $result;

    /**
     * @var string
     */
    private $userId;


    /**
     * ResultDto constructor.
     *
     * @param int $id
     * @param string $result result
     * @param string $userId user
     */
    public function __construct(
        int $id = 0,
        ?string $result = '',
        ?string $userId = ''
    )
    {
        $this->id = $id;
        $this->result = $result;
        $this->userId = $userId;
    }

    /**
     *
     */
    public function validate()
    {
        Validation::notBlank($this->result, 'result');
        Validation::notBlank($this->userId, 'userId');

        Validation::numeric($this->result, 'result');
        Validation::numeric($this->userId, 'userId');
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    public static function fromRequest(Request $request): ResultDto
    {
        $json = json_decode($request->getContent(), true);

        return new ResultDto(
            $json['id'] ?? 0,
            $json['result'] ?? null,
            $json['userId'] ?? null
        );
    }

}
