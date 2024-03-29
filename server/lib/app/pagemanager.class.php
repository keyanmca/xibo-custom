<?php
/*
 * Xibo - Digital Signage - http://www.xibo.org.uk
 * Copyright (C) 2006-2014 Daniel Garner
 *
 * This file is part of Xibo.
 *
 * Xibo is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version. 
 *
 * Xibo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Xibo.  If not, see <http://www.gnu.org/licenses/>.
 */ 
defined('XIBO') or die("Sorry, you are not allowed to directly access this page.<br /> Please press the back button in your browser.");


class PageManager
{
    private $db;
    private $user;

    private $p;
    private $q;

    private $page;
    private $path;
    private $ajax;
    private $userid;
    private $authed;
    private $thePage;
    
    // Maintain a list of pages/functions we are allowed to get to without going through the authentication system
    private $nonAuthedPages = array('index', 'clock');
    private $nonAuthedFunctions = array('login', 'logout', 'GetClock', 'About');
	
    function __construct(database $db, user $user, $page)
    {
        $this->db =& $db;
        $this->user =& $user;
        $this->path = 'lib/pages/' . $page . '.class.php';
        $this->page = $page . 'DAO';
        $this->p = $page;

        $this->ajax = Kit::GetParam('ajax', _REQUEST, _BOOL, false);
        $this->q = Kit::GetParam('q', _REQUEST, _WORD);
        $this->userid = $this->user->userid;
        
        // Default not authorised
        $this->authed = false;

        // Create a theme
		new Theme($db, $user);
		Theme::SetPagename($this->p);
    }
	
    /**
     * Checks the Security of the logged in user
     * @return 
     */
    public function Authenticate()
    {
        $user =& $this->user;
        
        // The user MUST be logged in unless they are trying to assess some of the public facing pages
        if (in_array($this->p, $this->nonAuthedPages) && in_array($this->q, $this->nonAuthedFunctions))
        {
            // Automatically authed
            $this->authed = true;
        }
        else
        {
            // User MUST be logged in.
            if (!$user->attempt_login($this->ajax))
                return false;

            $this->authed = $user->PageAuth($this->p);
        }
    }
	
    /**
     * Renders this page
     * @return 
     */
    public function Render()
    {
        $db 	=& $this->db;
        $user 	=& $this->user;

        if (!$this->authed)
            throw new Exception(__('You do not have permission to access this page.'));
        
        // Check the requested pages exits before trying to load it
        //   this check should be redundant, because the page should have been validated against the pages in the DB first.
        //   do it just in case...
        if (!file_exists($this->path))
            throw new Exception(__('The requested page does not exist'));
        
        // Load the file in question
        if (!class_exists($this->page)) 
            require_once($this->path);

        // Create the requested page
        $this->thePage = new $this->page($db, $user);

        // Are we calling a method
        if ($this->q != '') 
        {
            // Check the method exists
            if (method_exists($this->thePage, $this->q)) 
            {
                // Call the method
                $function = $this->q;
                $reloadLocation = $this->thePage->$function();
            }
            else 
                trigger_error($this->p . ' does not support the function: ' . $this->q, E_USER_ERROR);

            if ($this->ajax) 
                exit;

            // once we have dealt with it, reload the page      	
            Kit::Redirect($reloadLocation);
        }
        else 
        {
            // Display a page instead
			Theme::Render('header');
			
			$this->thePage->displayPage();
			
			Theme::Render('footer');
        }

        // Clear the session message
        $_SESSION['message'] = '';
    }
}
?>