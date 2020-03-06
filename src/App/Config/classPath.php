<?php
return [
	Give_Roles::class                      => GIVE_PLUGIN_DIR . 'includes/class-give-roles.php',
	Give_API::class                        => GIVE_PLUGIN_DIR . 'includes/api/class-give-api.php', // Need to confirm.
	Give_Admin_Settings::class             => GIVE_PLUGIN_DIR . 'includes/admin/class-admin-settings.php',
	Give_Emails::class                     => GIVE_PLUGIN_DIR . 'includes/emails/class-give-emails.php',
	Give_Email_Template_Tags::class        => GIVE_PLUGIN_DIR . 'includes/emails/class-give-email-tags.php',
	Give_HTML_Elements::class              => GIVE_PLUGIN_DIR . 'includes/admin/class-give-html-elements.php',
	Give_Tooltips::class                   => GIVE_PLUGIN_DIR . 'includes/class-give-tooltips.php',
	Give_Notices::class                    => GIVE_PLUGIN_DIR . 'includes/class-notices.php',
	Give_Logging::class                    => GIVE_PLUGIN_DIR . 'includes/class-give-logging.php',
	Give_Async_Process::class              => GIVE_PLUGIN_DIR . 'includes/class-give-async-process.php',
	Give_Scripts::class                    => GIVE_PLUGIN_DIR . 'includes/class-give-scripts.php',
	Give_Sequential_Donation_Number::class => GIVE_PLUGIN_DIR . 'includes/payments/class-give-sequential-donation-number.php',
	Give_Comment::class                    => GIVE_PLUGIN_DIR . 'includes/class-give-comment.php',
	Give_Session::class                    => GIVE_PLUGIN_DIR . 'includes/class-give-session.php',

	// Database table aliases
	Give_DB_Payment_Meta::class            => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-payment-meta.php',
	Give_DB_Logs::class                    => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-logs.php',
	Give_DB_Log_Meta::class                => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-logs-meta.php',
	Give_DB_Form_Meta::class               => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-form-meta.php',
	Give_DB_Sequential_Ordering::class     => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-sequential-ordering.php',
	Give_DB_Sessions::class                => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-sessions.php',
	Give_DB_Donors::class                  => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-donors.php',
	Give_DB_Donor_Meta::class              => GIVE_PLUGIN_DIR . 'includes/database/class-give-db-donor-meta.php',
];
