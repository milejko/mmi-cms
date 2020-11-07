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

    /**
     * Pobiera menu
     * @return NavigationConfigElement
     */
    public function __construct()
    {
        $this->addElement((new NavigationConfigElement('admin-menu'))
            ->setLabel('menu.index.index')
            ->setModule('cmsAdmin')
            ->addChild((new NavigationConfigElement)
                ->setModule('cmsAdmin')
                ->setDisabled()
                ->setAction('password')
                ->setLabel('menu.index.password'))
            ->addChild((new NavigationConfigElement)
                ->setModule('cmsAdmin')
                ->setAction('login')
                ->setIcon('fa-unlock-alt')
                ->setLabel('menu.index.login')
                ->setDisabled())
            ->addChild((new NavigationConfigElement)
                ->setLabel('menu.container')
                ->setIcon('fa-cog')
                ->setUri('#')
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.category.container')
                    ->setIcon('fa-image')
                    ->setUri('#')
                    ->setModule('cmsAdmin')
                    ->setController('category')
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.category.tree')
                        ->setIcon('fa-pencil')
                        ->setModule('cmsAdmin')
                        ->setController('category')
                        ->setAction('tree')
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.category.edit')
                            ->setDisabled()
                            ->setModule('cmsAdmin')
                            ->setController('category')
                            ->setAction('edit')
                            ->addChild((new NavigationConfigElement)
                                ->setLabel('menu.category.edit.widget')
                                ->setDisabled()
                                ->setModule('cmsAdmin')
                                ->setController('categoryWidgetRelation')
                                ->setAction('add'))
                            ->addChild((new NavigationConfigElement)
                                ->setLabel('menu.category.edit.widget')
                                ->setDisabled()
                                ->setModule('cmsAdmin')
                                ->setController('categoryWidgetRelation')
                                ->setAction('edit'))))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.category.index')
                        ->setModule('cmsAdmin')
                        ->setIcon('fa-table')
                        ->setController('category'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.categoryAcl')
                        ->setIcon('fa-key')
                        ->setModule('cmsAdmin')
                        ->setController('categoryAcl')))
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.text.container')
                    ->setIcon('fa-align-left')
                    ->setUri('#')
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.text.index')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('text'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.text.edit')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('text')
                        ->setAction('edit')))
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.mail.container')
                    ->setIcon('fa-envelope-o')
                    ->setUri('#')
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.mail.index')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('mail'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.mail.preview')
                        ->setDisabled()
                        ->setModule('cmsAdmin')
                        ->setController('mail')
                        ->setAction('preview'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.mail.send')
                        ->setIcon('fa-paper-plane')
                        ->setModule('cmsAdmin')
                        ->setController('mail')
                        ->setAction('send'))
                    ->addChild(
                        (new NavigationConfigElement)
                            ->setLabel('menu.mailDefinition.container')
                            ->setIcon('fa-clone')
                            ->setUri('#')
                            ->addChild((new NavigationConfigElement)
                                ->setLabel('menu.mailDefinition.index')
                                ->setIcon('fa-table')
                                ->setModule('cmsAdmin')
                                ->setController('mailDefinition'))
                            ->addChild((new NavigationConfigElement)
                                ->setLabel('menu.mailDefinition.edit')
                                ->setIcon('fa-plus')
                                ->setModule('cmsAdmin')
                                ->setController('mailDefinition')
                                ->setAction('edit'))
                    )
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.mailServer.container')
                        ->setIcon('fa-server')
                        ->setUri('#')
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.mailServer.index')
                            ->setIcon('fa-table')
                            ->setModule('cmsAdmin')
                            ->setController('mailServer'))
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.mailServer.edit')
                            ->setIcon('fa-plus')
                            ->setModule('cmsAdmin')
                            ->setController('mailServer')
                            ->setAction('edit'))))
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.stat.container')
                    ->setIcon('fa-pie-chart')
                    ->setUri('#')
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.stat.index')
                        ->setIcon('fa-line-chart')
                        ->setModule('cmsAdmin')
                        ->setController('stat'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.stat.label')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('stat')
                        ->setAction('label'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.stat.edit')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('stat')
                        ->setAction('edit')))
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.contact.container')
                    ->setIcon('fa-comments')
                    ->setUri('#')
                    ->addChild(
                        (new NavigationConfigElement)
                            ->setLabel('menu.contact.index')
                            ->setIcon('fa-table')
                            ->setModule('cmsAdmin')
                            ->setController('contact')
                    )
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.contact.edit')
                        ->setDisabled()
                        ->setModule('cmsAdmin')
                        ->setController('contact')
                        ->setAction('edit'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.contact.subject')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('contact')
                        ->setAction('subject'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.contact.editSubject')
                        ->setIcon('fa-plus')
                        ->setModule('cmsAdmin')
                        ->setController('contact')
                        ->setAction('editSubject')))
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.auth.container')
                    ->setIcon('fa-users')
                    ->setUri('#')
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.auth.index')
                        ->setIcon('fa-table')
                        ->setModule('cmsAdmin')
                        ->setController('auth'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.auth.edit')
                        ->setIcon('fa-user-plus')
                        ->setModule('cmsAdmin')
                        ->setController('auth')
                        ->setAction('edit'))
                    ->addChild(
                        (new NavigationConfigElement)
                            ->setLabel('menu.acl')
                            ->setIcon('fa-key')
                            ->setModule('cmsAdmin')
                            ->setController('acl')
                    ))
                ->addChild((new NavigationConfigElement)
                    ->setLabel('menu.system.container')
                    ->setIcon('fa-cogs')
                    ->setUri('#')
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.cron.container')
                        ->setIcon('fa-calendar')
                        ->setUri('#')
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.cron.index')
                            ->setIcon('fa-table')
                            ->setModule('cmsAdmin')
                            ->setController('cron'))
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.cron.edit')
                            ->setIcon('fa-plus')
                            ->setModule('cmsAdmin')
                            ->setController('cron')
                            ->setAction('edit')))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.file.index')
                        ->setIcon('fa-file')
                        ->setModule('cmsAdmin')
                        ->setController('file'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.config')
                        ->setIcon('fa-list')
                        ->setModule('cmsAdmin')
                        ->setController('config'))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.tag.container')
                        ->setIcon('fa-tag')
                        ->setUri('#')
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.tag.index')
                            ->setIcon('fa-table')
                            ->setModule('cmsAdmin')
                            ->setController('tag'))
                        ->addChild((new NavigationConfigElement)
                            ->setLabel('menu.tag.edit')
                            ->setIcon('fa-plus')
                            ->setModule('cmsAdmin')
                            ->setController('tag')
                            ->setAction('edit')))
                    ->addChild((new NavigationConfigElement)
                        ->setLabel('menu.cache')
                        ->setModule('cmsAdmin')
                        ->setIcon('fa-trash')
                        ->setController('cache')))));
    }
}
