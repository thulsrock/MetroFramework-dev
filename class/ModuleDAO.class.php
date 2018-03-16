<?php

interface ModuleDAO {
	function getList( String $obj, array $args = NULL );
	function save( array $vo, String $status );
}