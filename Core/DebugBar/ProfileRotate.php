<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */

namespace FlorianPalme\DebugBar\Core\DebugBar;


class ProfileRotate
{

    /** @var string Regex für den Datei-Filter */
    protected $regex = '/.*/';

    /** @var string Zu durchsuchender Pfad */
    protected $path = '/tmp';

    /** @var int Maximale Anzahl von Dateien im Ordner */
    protected $maxFiles = 30;

    /**
     * Setzt das Regex für den Datei-Filter
     *
     * @param string $regex
     *
     * @return ProfileRotate
     */
    public function setRegex(string $regex): ProfileRotate
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Setzt den zu durchsuchenden Pfad
     *
     * @param string $path
     *
     * @return ProfileRotate
     */
    public function setPath(string $path): ProfileRotate
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Setzt die maximale Anzahl an Dateien im Ordner
     *
     * @param int $maxFiles
     *
     * @return ProfileRotate
     */
    public function setMaxFiles(int $maxFiles): ProfileRotate
    {
        $this->maxFiles = $maxFiles;

        return $this;
    }

    /**
     * Rotiert die Dateien im angegebenen Ordner
     */
    public function rotate()
    {
        $foundFiles = $this->getProfiles();

        // Nicht mehr benötigte löschen
        $sliceLength = count($foundFiles) - $this->maxFiles ?: count($foundFiles);
        $sliced = array_slice($foundFiles, 0, $sliceLength);

        foreach ($sliced as $file) {
            unlink($this->path . '/' . $file);
        }
    }

    /**
     * Sucht und gibt alle gefundenen Profile zurück
     *
     * @return array
     */
    public function getProfiles()
    {
        $files = new \DirectoryIterator($this->path);
        $foundFiles = [];

        foreach ($files as $file) {
            if ($file->isDir() || $file->isDot()) continue;

            if (preg_match($this->regex, $file->getFilename())) {
                $foundFiles[$file->getMTime()] = $file->getFilename();
            }
        }

        // Nach Zeit sortieren
        ksort($foundFiles);

        return $foundFiles;
    }
}