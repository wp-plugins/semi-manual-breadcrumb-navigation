<?php
	/*
	Plugin Name: Semimanual Breadcrumbs
	Plugin URI: http://www.asiden.dk/semi-manual-breadcrumb-navigation-plugin-for-wordpress/
	Description: Displays breadcrumbs as you want it.
	Author: Mads Jensen
	Version: 1.0
	Author URI: http://www.asiden.dk
	*/
	
	/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    */
	
	add_action('activate_semimanual_breadcrumbs.php', 'BreadcrumbsInstall');
	
	// Install the plugin
	function BreadcrumbsInstall() 
	{
	    global $wpdb;
	    
	    $structure = "CREATE TABLE asiden_breadcrumbs_categories (
		  id int(11) NOT NULL auto_increment,
		  category int(11) default NULL,
		  page int(11) default NULL,
		  PRIMARY KEY  (`id`)
		);";
	    
		$wpdb->query($structure);
		 
		$structure = "CREATE TABLE asiden_breadcrumbs_pages (
		  id int(11) NOT NULL auto_increment,
		  page int(11) default NULL,
		  parent_page int(11) default NULL,
		  PRIMARY KEY  (`id`)
		);";

		$wpdb->query($structure);
	}

	// Display breadcrumbs
	function DisplayBreadcrumbs($currentID, $wpdb)
	{
	 	if($currentID == 377) // Velkommen, special pages and posts, for example a static frontpage
	 	{
	 	 	// Don't show breadcrumbs here
		}
		else if($currentID == 77)
		{
			print("<a href='/'>Leksikon</a> > <a href='/kaeledyr/'>Kæledyr</a> > <a href='/hunde/'>Hunde</a> > <a href='http://www.asiden.dk/gode-sjove-og-soede-hundenavne/'>Gode, sjove og søde hundenavne</a> > Hundenavne A-G");
		}
		else if($currentID == 78)
		{
			print("<a href='/'>Leksikon</a> > <a href='/kaeledyr/'>Kæledyr</a> > <a href='/hunde/'>Hunde</a> > <a href='http://www.asiden.dk/gode-sjove-og-soede-hundenavne/'>Gode, sjove og søde hundenavne</a> > Hundenavne H-O");
		}
		else if($currentID == 79)
		{
			print("<a href='/'>Leksikon</a> > <a href='/kaeledyr/'>Kæledyr</a> > <a href='/hunde/'>Hunde</a> > <a href='http://www.asiden.dk/gode-sjove-og-soede-hundenavne/'>Gode, sjove og søde hundenavne</a> > Hundenavne P-Å");
		}
		else // everything else
		{
		 	if(is_page() == true)
			{
				$currentPageID = $currentID;
				$currentPageTitle = get_the_title($currentPageID);
				
				$query = "SELECT parent_page FROM asiden_breadcrumbs_pages WHERE page = $currentPageID";		
							
				$parentPageID = $wpdb->get_var($query);
				
				if(strlen($parentPageID) > 0)
				{
					$parentPageTitle = get_the_title($parentPageID);
					$parentPageUrl = get_permalink($parentPageID);
				
			 		print("<a href='/'>Leksikon</a> > <a href='$parentPageUrl'>$parentPageTitle</a> > $currentPageTitle");
			 	}
			 	else
			 	{
					// No parent page
					
					print("<a href='/'>Leksikon</a> > $currentPageTitle");
				}		
			}
			else // post
			{
				$currentPostID = $currentID;
				$currentPostTitle = get_the_title($currentPostID);
				
				$category = get_the_category();
				$currentCategoryID = $category[0]->cat_ID;
				
				$query = "SELECT page FROM asiden_breadcrumbs_categories WHERE category = $currentCategoryID";		
							
				$pageID = $wpdb->get_var($query);
				
				if(strlen($pageID) > 0)
				{
				 	$query = "SELECT parent_page FROM asiden_breadcrumbs_pages WHERE page = $pageID";		
							
					$parentPageID = $wpdb->get_var($query);
					
					if(strlen($parentPageID) > 0)
					{
					 	$pageTitle = get_the_title($pageID);
						$pageUrl = get_permalink($pageID);
					 
						$parentPageTitle = get_the_title($parentPageID);
						$parentPageUrl = get_permalink($parentPageID);
			  		
			 	 		print("<a href='/'>Leksikon</a> > <a href='$parentPageUrl'>$parentPageTitle</a> > <a href='$pageUrl'>$pageTitle</a> > $currentPostTitle");
			 	 	}
			 	 	else
			 	 	{
						// No parent page
						
						$pageTitle = get_the_title($pageID);
						$pageUrl = get_permalink($pageID);
			  		
			 	 		print("<a href='/'>Leksikon</a> > <a href='$pageUrl'>$pageTitle</a> > $currentPostTitle");
					}
			 	}
			}
		}
		
		if($currentID != 377)
		{
			print("<br/><br/>");
		}
	}
?>