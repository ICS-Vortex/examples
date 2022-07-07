<?php


namespace App\Entity\Model;


class Weather
{
    private Wind $wind;
    private string $nameRu;
    private bool $enableFog;
    private Season $season;

    /**
     * @return Wind
     */
    public function getWind(): ?Wind
    {
        return $this->wind;
    }

    /**
     * @param Wind $wind
     */
    public function setWind(Wind $wind): void
    {
        $this->wind = $wind;
    }

    /**
     * @return string
     */
    public function getNameRu(): ?string
    {
        return $this->nameRu;
    }

    /**
     * @param string $nameRu
     */
    public function setNameRu(string $nameRu): void
    {
        $this->nameRu = $nameRu;
    }

    /**
     * @return bool
     */
    public function isEnableFog(): ?bool
    {
        return $this->enableFog;
    }

    /**
     * @param bool $enableFog
     */
    public function setEnableFog(bool $enableFog): void
    {
        $this->enableFog = $enableFog;
    }

    /**
     * @return Season
     */
    public function getSeason(): ?Season
    {
        return $this->season;
    }

    /**
     * @param Season $season
     */
    public function setSeason(Season $season): void
    {
        $this->season = $season;
    }
}