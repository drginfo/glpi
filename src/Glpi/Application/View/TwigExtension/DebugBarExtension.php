<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2018 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

namespace Glpi\Application\View\TwigExtension;

use Twig_Extension;
use Glpi\Application\DebugBar;
use Glpi\Application\Router;

class DebugBarExtension extends Twig_Extension
{
    /**
     * @var DebugBar
     */
    private $debugBar;

    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router, DebugBar $debugBar)
    {
        $this->router = $router;
        $this->debugBar = $debugBar;

        $renderer = $this->debugBar->getJavascriptRenderer();
        $path = preg_replace('/(public)?\/(index\.php)?$/', '$1', $this->router->getBasePath());
        $renderer->setBaseUrl($path . '/lib/debugbar');
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('debug_head', [$this, 'debugBarHead'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('debug_bar', [$this, 'debugBarContents'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Render debug bar.
     *
     * @return string
     */
    public function debugBarHead(): string
    {
        // TODO Render this only for debug mode
        $renderer = $this->debugBar->getJavascriptRenderer();

        // Manually include highlightjs to not render all vendors
        $renderer->setIncludeVendors(false);
        $html = <<<HTML
            <script type="text/javascript" src="{$renderer->getBaseUrl()}/vendor/highlightjs/highlight.pack.js"></script>
            <link rel="stylesheet" href="{$renderer->getBaseUrl()}/vendor/highlightjs/styles/github.css" type="text/css" media="screen" />
HTML;

        $html .= $renderer->renderHead();

        return $html;
    }

    /**
     * Render debug bar.
     *
     * @return string
     */
    public function debugBarContents(): string
    {
        // TODO Render this only for debug mode
        $renderer = $this->debugBar->getJavascriptRenderer();
        return $renderer->render();
    }
}
