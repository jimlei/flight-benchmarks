<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * World
 *
 * @ORM\Table(name="World")
 * @ORM\Entity
 */
class World
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="randomNumber", type="integer")
     */
    private $randomNumber;


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
     * Set randomNumber
     *
     * @param integer $randomNumber
     *
     * @return World
     */
    public function setRandomNumber($randomNumber)
    {
        $this->randomNumber = $randomNumber;

        return $this;
    }

    /**
     * Get randomNumber
     *
     * @return integer
     */
    public function getRandomNumber()
    {
        return $this->randomNumber;
    }
}
