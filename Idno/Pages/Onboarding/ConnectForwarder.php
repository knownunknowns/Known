<?php

    /**
     * User profile editing for onboarding
     */

    namespace Idno\Pages\Onboarding {

        class ConnectForwarder extends \Idno\Common\Page
        {

            function getContent()
            {

                $this->gatekeeper();

                $this->forward(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'begin/connect');

            }

        }

    }