<?php

/**
 * Mmi Framework (https://github.com/milejko/mmi.git)
 *
 * @link       https://github.com/milejko/mmi.git
 * @copyright  Copyright (c) 2010-2016 Mariusz Miłejko (http://milejko.com)
 * @license    http://milejko.com/new-bsd.txt New BSD License
 */

namespace CmsAdmin\App;

use Mmi\Navigation\NavigationConfig;
use Mmi\Navigation\NavigationConfigElement;

/**
 * Konfiguracja nawigatora
 */
class CmsNavigationConfig extends NavigationConfig
{
    public const MAIN_MENU_ID = 'admin-menu';
    public const CMS_ELEMENT_ID = 'admin-menu-cms';
    public const CONTENT_ELEMENT_ID = 'admin-menu-content';
    public const MAIL_ELEMENT_ID = 'admin-menu-mail';
    public const USER_ELEMENT_ID = 'admin-menu-user';
    public const SYSTEM_ELEMENT_ID = 'admin-menu-system';

    /**
     * Pobiera menu
     * @return NavigationConfigElement
     */
    public function __construct()
    {
        $this->addElement(
            (new NavigationConfigElement(self::MAIN_MENU_ID))
                ->setLabel('menu.index.index')
                ->setModule('cmsAdmin')
                ->addChild(
                    (new NavigationConfigElement())
                        ->setModule('cmsAdmin')
                        ->setDisabled()
                        ->setAction('password')
                        ->setLabel('menu.index.password')
                )
                ->addChild(
                    (new NavigationConfigElement())
                        ->setModule('cmsAdmin')
                        ->setAction('login')
                        ->setIcon('fa-unlock-alt')
                        ->setLabel('menu.index.login')
                        ->setDisabled()
                )
                ->addChild(
                    (new NavigationConfigElement(self::CMS_ELEMENT_ID))
                        ->setLabel('menu.container')
                        ->setIcon('fa-cog')
                        ->setUri('#')
                        ->addChild(
                            (new NavigationConfigElement(self::CONTENT_ELEMENT_ID))
                                ->setLabel('menu.category.container')
                                ->setIcon('fa-image')
                                ->setUri('#')
                                ->setModule('cmsAdmin')
                                ->setController('category')
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.category.index')
                                        ->setIcon('fa-pencil')
                                        ->setModule('cmsAdmin')
                                        ->setController('category')
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.category.move')
                                                ->setDisabled()
                                                ->setModule('cmsAdmin')
                                                ->setController('category')
                                                ->setAction('move')
                                        )
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.category.edit')
                                                ->setDisabled()
                                                ->setModule('cmsAdmin')
                                                ->setController('category')
                                                ->setAction('edit')
                                                ->addChild(
                                                    (new NavigationConfigElement())
                                                        ->setLabel('menu.category.edit.widget')
                                                        ->setDisabled()
                                                        ->setModule('cmsAdmin')
                                                        ->setController('categoryWidgetRelation')
                                                        ->setAction('add')
                                                )
                                                ->addChild(
                                                    (new NavigationConfigElement())
                                                        ->setLabel('menu.category.edit.widget')
                                                        ->setDisabled()
                                                        ->setModule('cmsAdmin')
                                                        ->setController('categoryWidgetRelation')
                                                        ->setAction('edit')
                                                )
                                        )
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.category.search')
                                        ->setIcon('fa-search')
                                        ->setModule('cmsAdmin')
                                        ->setController('category')
                                        ->setAction('search')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.categoryTrash')
                                        ->setIcon('fa-trash')
                                        ->setModule('cmsAdmin')
                                        ->setController('categoryTrash')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.categoryAcl')
                                        ->setIcon('fa-key')
                                        ->setModule('cmsAdmin')
                                        ->setController('categoryAcl')
                                )
                        )
                        ->addChild(
                            (new NavigationConfigElement(self::MAIL_ELEMENT_ID))
                                ->setLabel('menu.mail.container')
                                ->setIcon('fa-envelope-o')
                                ->setUri('#')
                                ->setModule('cmsAdmin')
                                ->setController('mail')
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.mail.index')
                                        ->setIcon('fa-table')
                                        ->setModule('cmsAdmin')
                                        ->setController('mail')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.mail.preview')
                                        ->setDisabled()
                                        ->setModule('cmsAdmin')
                                        ->setController('mail')
                                        ->setAction('preview')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.mail.send')
                                        ->setIcon('fa-paper-plane')
                                        ->setModule('cmsAdmin')
                                        ->setController('mail')
                                        ->setAction('send')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.mailDefinition.container')
                                        ->setIcon('fa-clone')
                                        ->setUri('#')
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.mailDefinition.index')
                                                ->setIcon('fa-table')
                                                ->setModule('cmsAdmin')
                                                ->setController('mailDefinition')
                                        )
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.mailDefinition.edit')
                                                ->setIcon('fa-plus')
                                                ->setModule('cmsAdmin')
                                                ->setController('mailDefinition')
                                                ->setAction('edit')
                                        )
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.mailServer.container')
                                        ->setIcon('fa-server')
                                        ->setUri('#')
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.mailServer.index')
                                                ->setIcon('fa-table')
                                                ->setModule('cmsAdmin')
                                                ->setController('mailServer')
                                        )
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.mailServer.edit')
                                                ->setIcon('fa-plus')
                                                ->setModule('cmsAdmin')
                                                ->setController('mailServer')
                                                ->setAction('edit')
                                        )
                                )
                        )
                        ->addChild(
                            (new NavigationConfigElement(self::USER_ELEMENT_ID))
                                ->setLabel('menu.auth.container')
                                ->setIcon('fa-users')
                                ->setUri('#')
                                ->setModule('cmsAdmin')
                                ->setController('auth')
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.auth.index')
                                        ->setIcon('fa-table')
                                        ->setModule('cmsAdmin')
                                        ->setController('auth')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.auth.edit')
                                        ->setIcon('fa-user-plus')
                                        ->setModule('cmsAdmin')
                                        ->setController('auth')
                                        ->setAction('edit')
                                )
                        )
                        ->addChild(
                            (new NavigationConfigElement(self::SYSTEM_ELEMENT_ID))
                                ->setLabel('menu.system.container')
                                ->setIcon('fa-cogs')
                                ->setUri('#')
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.cron.container')
                                        ->setIcon('fa-calendar')
                                        ->setUri('#')
                                        ->setModule('cmsAdmin')
                                        ->setController('cron')
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.cron.index')
                                                ->setIcon('fa-table')
                                                ->setModule('cmsAdmin')
                                                ->setController('cron')
                                        )
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.cron.edit')
                                                ->setIcon('fa-plus')
                                                ->setModule('cmsAdmin')
                                                ->setController('cron')
                                                ->setAction('edit')
                                        )
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.file.index')
                                        ->setIcon('fa-file')
                                        ->setModule('cmsAdmin')
                                        ->setController('file')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.config')
                                        ->setIcon('fa-list')
                                        ->setModule('cmsAdmin')
                                        ->setController('config')
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.tag.container')
                                        ->setIcon('fa-tag')
                                        ->setUri('#')
                                        ->setModule('cmsAdmin')
                                        ->setController('tag')
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.tag.index')
                                                ->setIcon('fa-table')
                                                ->setModule('cmsAdmin')
                                                ->setController('tag')
                                        )
                                        ->addChild(
                                            (new NavigationConfigElement())
                                                ->setLabel('menu.tag.add')
                                                ->setIcon('fa-plus')
                                                ->setModule('cmsAdmin')
                                                ->setController('tag')
                                                ->setAction('edit')
                                        )
                                )
                                ->addChild(
                                    (new NavigationConfigElement())
                                        ->setLabel('menu.cache')
                                        ->setModule('cmsAdmin')
                                        ->setIcon('fa-trash')
                                        ->setController('cache')
                                )
                        )
                )
        );
    }
}
