<?php

/***************************************************************************/
/*
/* 	----------------------------------------------------------------------
/* 						DO NOT EDIT THIS FILE
/*	----------------------------------------------------------------------
/* 
/*  			Built by Darcy Clarke. http://themify.me
 * 				Updated by Elio Rivero
/*  			Copyright (C) 2010 Themify
/*
/***************************************************************************/

/* 	Database Functions
/***************************************************************************/

	
	/**
	 * Initialize DB - Create Row if needed
	 * @deprecated no longer used since release 1.1.5
	 * @since 1.0.0
	 * @package themify
	 */
	function init_db(){
		global $theme;
		//replaces old version using a raw sql query
		add_option( $theme['Name'] . '_themify_data', '', '', 'yes' );
	}
		
	/**
	 * Save Data
	 * @param Array $data
	 * @return String
	 * @since 1.0.0
	 * @package themify
	 */
	function set_data($data){
		global $theme;
		foreach($data as $name => $value){
			if('save' == $name || 'page' == $name){
				unset($data[$name]);	
			}
		}
		$option_value = base64_encode(serialize($data)); 
		update_option( $theme['Name'] . '_themify_data', $option_value);
		return $option_value;
	}
		
	/**
	 * Get Data
	 * @return String
	 * @since 1.0.0
	 * @package themify
	 */
	function get_data(){
		global $theme;
		$data = unserialize(base64_decode( get_option( $theme['Name'] . '_themify_data' ) ));
		if(is_array($data) && count($data) >= 1){
			foreach($data as $name => $value){
				$data[$name] = stripslashes($value);	
			}
		}
		return $data;
	}
	
?>