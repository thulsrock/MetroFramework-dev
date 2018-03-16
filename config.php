<?php

// db parameters
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'MetroFramework');
define('DB_CHARSET', 'UTF8');

// URLs
define( 'ROOT', 'http://' . $_SERVER['SERVER_NAME'] .'/MetroFramework-dev/' );
define( 'CLASS_DIR', 'class/' );
define( 'SOURCES', 'sources/' );
define( 'VENDOR', 'vendor/');
define( 'DOCUMENTS', 'docs/' );
define( 'JQUERY', SOURCES . 'JQuery/jquery-3.2.1.min.js' );
define( 'JQUERYUI', SOURCES . 'JQuery/jquery-ui.min.js' );
define( 'IMAGES', SOURCES . 'images/' );

// Components
define( 'CONTENT_DIR', 'content/' );
define( 'COMPONENT_DIR', CONTENT_DIR. 'component/' );
define( 'MODULE_DIR', CONTENT_DIR. 'modules/' );
define( 'STYLE_DIR', CONTENT_DIR . 'style/');

define( 'UPLOAD_ROOT', 'D:/www/MetroFramework-dev/uploads/' );
define( 'UPLOAD_DIR', 'uploads/' );
define( 'UPLOAD_REPORTS', 'D:/www/MetroFramework/reports/');
define( 'REPORT_DIR', 'reports/' );

// Debug & Test phase
define( 'DEBUG', FALSE );
define( 'ERROR_REPORTING', -1 );

define( 'TOTAL_ATTEMPT', 5 );
define( 'PASS_DEFAULT', "Provincia1" );
define( 'PASSWORD_MIN_LENGTH', 8 );
define( 'PASSWORD_MAX_LENGTH', 32 );

// Titles
define( 'COMPANY_NAME', 'Città Metropolitana di Bari' );
define( 'COMPANY_SUBTITLE', 'Ufficio Informatizzazione e Statistica' );
define( 'SITENAME', 'Strumento di analisi per il Nucleo di Gestione' );

define( 'DEFAULT_START_DATE', '2017-01-01' );
define( 'DEFAULT_END_DATE', '2019-12-31' );

define( 'CURRENT_YEAR', '2018');

# DEFAULT ACTIONS

define( "ELEMENT_VIEW",				"open");
define( "ELEMENT_NEW",				"new");
define( "ELEMENT_EDIT",				"edit");
define( "ELEMENT_DELETE",			"delete");

# MODULES

define( "CORE_DIR",					MODULE_DIR . "core/");
define( "CORE_DEFAULT_PAGE",		"front-page");

define( "DEPARTMENT_TABLE",			"department");
define( "DEPARTMENT_DIR",			MODULE_DIR. "department/");
define( "DEPARTMENT_DEFAULT_PAGE",	"department-list");

define( "TARGET_TABLE",				"target");
define( "TARGET_DIR",				MODULE_DIR. "target/");
define( "TARGET_DEFAULT_PAGE",		"target-list");
define( 'TARGET_NEW',				'target-new');
define( 'TARGET_EDIT',				'target-edit');
define( 'TARGET_DELETE',			ELEMENT_DELETE);

define( "TASK_TABLE",				"task");
define( "TASK_DIR",					MODULE_DIR. "task/");
define( "TASK_DEFAULT_PAGE",		"task-list");
define( 'TASK_NEW',					'task-new' );
define( 'TASK_EDIT',				'task-edit' );
define( "TASK_DELETE",				ELEMENT_DELETE);

define( "INDICATOR_TABLE",			"indicator");
define( "INDICATOR_DIR",			MODULE_DIR. "indicator/");
define( 'INDICATOR_OPEN',			ELEMENT_VIEW);
define( 'INDICATOR_VIEW',			'indicator-view');
define( 'INDICATOR_EDIT',			'indicator-edit');
define( "INDICATOR_ATTACHMENT_DELETE", "indicatorAttachmentDelete");

define( "FEATURE_TABLE",			"feature");

define( "USER_TABLE",				"user");
define( "USER_DIR",					MODULE_DIR. "user/");
define( "USER_DEFAULT_PAGE",		"user-list");
define( 'USER_NEW',					'user-new');
define( 'USER_EDIT',				'user-edit');
define( 'USER_EDIT_FEATURE',		'user-edit-feature');
define( 'USER_DELETE',				ELEMENT_DELETE);

define( "USERJOB_DELETE", "userjobDelete");




#PAGES AND FORMS

define( 'LOGIN_FORM', 'login');


define( '403', '403');
define( '404', '404');

define( 'HEADER_LOGO', IMAGES . 'logo.png');
define( 'PDF_LOGO', IMAGES . 'fullLogo.png');
define( 'HEADER_LOGO_TEXT', IMAGES . 'logoText.png');
define( 'PDF_TASK_LOGO', IMAGES . 'pdf.png');
define( 'PDF_TASK_MULTIPLE_LOGO', IMAGES . 'pdf_multiple.png');

define( 'EDIT_PAGE', 'edit');
define( 'VIEW_PAGE', 'view');



define( 'PASSWORD_CHANGE', 'password-change');

#TITLE LINKS
define( 'TARGETS_BY_DEPARTMENT', 'Lista degli obiettivi del Servizio' );
define( 'TASKS_BY_TARGET', 'Lista delle attività dell\'obiettivo' );

define( "ANONYMOUS", "Non autenticato.");

# PRIVILEGES
define( "TARGET_MONITOR", "target-monitor");
define( "TARGET_VIEW", "target-view");

define( "TASK_MONITOR", "task-monitor");

define( "NEW_VO", "new-virtual-object");
define( "EDIT_VO", "edit-virtual-object");

// Core functions

define( 'SYSTEM_CORE_FUNCIONS', array(
		'password'	=>	array(
				'Cambio password'	=>	'action=' . PASSWORD_CHANGE,
		),
		'manual'	=>	array(
				'Documentazione'	=>	'file=Documentazione.pdf',
		),
		'PEG'	=>	array(
				'Peg'		=>	'file=PEG_2017.pdf',
		),
		'logout'	=>	array(
				'Logout'			=>	'logout=true',
		)
) );
