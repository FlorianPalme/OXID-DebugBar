<?php
/**
 * @author Florian Palme <info@florian-palme.de>
 * @package
 */
declare(strict_types=1);

namespace FlorianPalme\DebugBar\Tests;


use FlorianPalme\DebugBar\Core\DebugBar\Tabber;

class TestData
{
    /**
     * @return Tabber
     */
    public static function Tabber(): Tabber
    {
        /** @var Tabber $tabber */
        $tabber = oxNew(Tabber::class);
        $tabber->setId('_unittests_tabber');

        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('_unittests_tab1');
        $tab->setTitle('Unittests Title');
        $tab->setContent('Unittests Content');
        $tabber->addTab($tab);

        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('_unittests_tab2');
        $tab->setTitle('Unittests Title 2');
        $tab->setContent('Unittests Content 2');
        $tabber->addTab($tab);

        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('_unittests_tab3');
        $tab->setTitle('Unittests Title 3');
        $tab->setContent('Unittests Content 3');
        $tabber->addTab($tab);

        return $tabber;
    }


    /**
     * @return Tabber\Tab
     */
    public static function Tab(): Tabber\Tab
    {
        /** @var Tabber\Tab $tab */
        $tab = oxNew(Tabber\Tab::class);
        $tab->setKey('_unittests_tab');
        $tab->setTitle('Unittests Title');
        $tab->setContent('Unittests Content');

        return $tab;
    }
}