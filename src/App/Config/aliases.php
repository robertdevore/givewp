<?php
return [
	'roles'                  => Give_Roles::class,
	'api'                    => Give_API::class,
	'give_settings'          => Give_Admin_Settings::class,
	'emails'                 => Give_Emails::class,
	'email_tags'             => Give_Email_Template_Tags::class,
	'html'                   => Give_HTML_Elements::class,
	'tooltips'               => Give_Tooltips::class,
	'notices'                => Give_Notices::class,
	'logs'                   => Give_Logging::class,
	'async_process'          => Give_Async_Process::class,
	'scripts'                => Give_Scripts::class,
	'seq_donation_number'    => Give_Sequential_Donation_Number::class,
	'comment'                => Give_Comment::class,
	'session'                => Give_Session::class,

	// Database table aliases
	'payment_meta'           => Give_DB_Payment_Meta::class,
	'log_db'                 => Give_DB_Logs::class,
	'logmeta_db'             => Give_DB_Log_Meta::class,
	'form_meta'              => Give_DB_Form_Meta::class,
	'sequential_donation_db' => Give_DB_Sequential_Ordering::class,
	'session_db'             => Give_DB_Sessions::class,
	'donors'                 => Give_DB_Donors::class,
	'donor_meta'             => Give_DB_Donor_Meta::class,
];
