<?php

namespace AppBundle\Traits;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Description of TimestampableEntity
 *
 * @author eduardoledo
 */
trait TimestampableEntityTrait
{

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return School
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return School
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

}
