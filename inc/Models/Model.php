<?php
namespace Themepaste\ShippingManager\Models;

defined( 'ABSPATH' ) || exit;

abstract class Model {
	/**
	 * Option key for a model
	 *
	 * @since 1.1.0
	 *
	 * @var string
	 */
	protected string $option_key = '';

	/**
	 * All the settings stay here
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected array $settings = [];

	/**
	 * Validation error messages
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected array $validation_messages = [];

	/**
	 * Initializes the model
	 *
	 * @since 1.1.0
	 *
	 * @param $option_key
	 */
	public function __construct( $option_key ) {
		$this->option_key = $option_key;
	}

	/**
	 * Validation rules will go here and push error messages to $validation_messages
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function is_valid(): bool {
		return true;
	}

	/**
	 * Saves option to WordPress options
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function save(): bool {
		$saved = false;
		if ( $this->is_valid() ) {
			$saved = update_option( $this->option_key, $this->settings );
		}
		return $saved;
	}

	/**
	 * Fetches data form data store
	 *
	 * @since TSM_SINCE
	 *
	 * @return $this
	 */
	public function fetch(): self {
		$data = get_option( $this->option_key, [] );
		$this->settings = array_merge( $this->settings, $data );
		return $this;
	}

	/**
	 * Get all settings or a single settings from data
	 *
	 * @since TSM_SINCE
	 *
	 * @param string $key
	 *
	 * @return array|mixed|string
	 */
	public function get( string $key = '' ) {
		if ( ! empty( $key ) ) {
			return $this->settings[ $key ] ?? '';
		} else  {
			return $this->settings;
		}
	}

	/**
	 * Set value for model
	 *
	 * @since TSM_SINCE
	 *
	 * @param array $data
	 *
	 * @return $this
	 */
	public function set( array $data ): self {
		foreach( $data as $key => $value ) {
			if ( isset( $this->settings[ $key ] ) ) {
				$this->settings[ $key ] = $value;
			}
		}
		return $this;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @since TSM_SINCE
	 *
	 * @return array
	 */
	public function get_fields(): array {
		return array_keys( $this->settings );
	}
}
