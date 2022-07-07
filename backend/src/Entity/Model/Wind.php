<?php


namespace App\Entity\Model;


class Wind
{
    private Condition $atGround;
    private Condition $at2000;
    private Condition $at8000;

    /**
     * @return Condition
     */
    public function getAtGround(): Condition
    {
        return $this->atGround;
    }

    /**
     * @param Condition $atGround
     */
    public function setAtGround(Condition $atGround): void
    {
        $this->atGround = $atGround;
    }

    /**
     * @return Condition
     */
    public function getAt2000(): Condition
    {
        return $this->at2000;
    }

    /**
     * @param Condition $at2000
     */
    public function setAt2000(Condition $at2000): void
    {
        $this->at2000 = $at2000;
    }

    /**
     * @return Condition
     */
    public function getAt8000(): Condition
    {
        return $this->at8000;
    }

    /**
     * @param Condition $at8000
     */
    public function setAt8000(Condition $at8000): void
    {
        $this->at8000 = $at8000;
    }
}