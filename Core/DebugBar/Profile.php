<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar;


use OxidEsales\Eshop\Core\Registry;

class Profile
{
    /** @var Profile-ID */
    protected $profileId;

    /**
     * Speichert das Profil in einer Datei ab
     *
     * @param string $html
     *
     * @return bool
     */
    public function saveDebugBarHTML(string $html): bool
    {
        return (bool) file_put_contents($this->getFilePath(), $html);
    }

    /**
     * Gibt den Pfad zur HTML-Datei zurück
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->getSaveDir() . '/' . $this->getProfileId() . '.html';
    }

    /**
     * Gibt das HTML der zuvor gespeicherten DebugBar zurück
     *
     * @return string
     */
    public function getDebugBarHTML(): string
    {
        if ($this->exists()) {
            return file_get_contents($this->getFilePath());
        }

        return '';
    }


    /**
     * Gibt die Profile-ID zurück
     *
     * @return string
     */
    public function getProfileId(): string
    {
        if ($this->profileId === null) {
            $this->setProfileId('profile_' . time() . '_' . uniqid());
        }

        return $this->profileId;
    }

    /**
     * Setzt die Profile-ID des Profils
     *
     * @param string $profileId
     */
    public function setProfileId(string $profileId)
    {
        $this->profileId = $profileId;
    }

    /**
     * Gibt den Pfad zum Speicher-Ort der Profile zurück
     *
     * @return string
     */
    protected function getSaveDir(): string
    {
        $dir = Registry::getConfig()->getDeubgBarProfileDir();

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    /**
     * Prüft, ob das gewünschte Profil existiert
     *
     * @return bool
     */
    public function exists(): bool
    {
        return file_exists($this->getFilePath());
    }
}