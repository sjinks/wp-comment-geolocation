<?php
namespace WildWolf\CommentGeolocation;

final class Plugin
{
	public static function instance()
	{
		static $self = null;

		if (!$self) {
			$self = new self();
		}

		return $self;
	}

	private function __construct()
	{
		if (\is_admin()) {
			\add_action('init',       [$this, 'init']);
			\add_action('admin_init', [$this, 'admin_init']);
		}
	}

	public function init()
	{
		\load_plugin_textdomain('wwipgeoc', /** @scrutinizer ignore-type */ false, \plugin_basename(\dirname(__DIR__)) . '/lang/');
	}

	public function admin_init()
	{
		if (\function_exists('geoip_country_code_by_name')) {
			\add_filter('manage_edit-comments_columns',  [$this, 'manage_edit_comments_columns']);
			\add_action('manage_comments_custom_column', [$this, 'manage_comments_custom_column'], 10, 2);
		}
	}

	public function manage_edit_comments_columns($columns)
	{
		return $columns + ['ww_ipgeo' => \__('IP Geolocation', 'wwipgeoc')];
	}

	public function manage_comments_custom_column($column, $comment_id)
	{
		if ('ww_ipgeo' == $column) {
			$ip      = \get_comment_author_IP($comment_id);
			$data    = (array)geoip_record_by_name($ip);
			$na      = \__('N/A', 'wwipgeoc');
			$code    = $data['country_code'] ?? '';
			$country = $data['country_name'] ?? $na;
			$city    = empty($data['city']) ? $na : $data['city'];

			\printf(
				\__('<strong style="vertical-align: middle;">%1$s</strong> %2$s<br/>%3$s, %4$s', 'wwipgeoc'),
				$ip,
				$code ? ('<img src="https://www.countryflags.io/' . \strtolower($code) . '/flat/24.png" alt="" style="vertical-align: middle"/>') : '',
				$country,
				$city
			);
		}
	}
}
