<?php

// SPDX-FileCopyrightText: 2025 Benno Bielmeier <git@bbenno.com>
//
// SPDX-License-Identifier: EUPL-1.2

/**
 * Plugin Name: Country Flags
 * Description: Shortcode [flag country="de" size="64" alt="Germany"] that renders SVG country flags (circle style).
 * Version:     1.0.0
 * Author:      Benno Bielmeier
 * License:     EUPL-1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

final class CFS_Country_Flags_Shortcode {
	const DEFAULT_SIZE = 64;
	const MIN_SIZE     = 8;
	const MAX_SIZE     = 1024;

	public static function init(): void {
		add_shortcode( 'flag', array( __CLASS__, 'shortcode' ) );
	}

	/**
	 * Shortcode handler.
	 *
	 * Usage:
	 *   [flag country="de"]
	 *   [flag country="gb" size="96" alt="United Kingdom"]
	 *   [flag code="us" class="inline-flag" title="USA"]
	 *
	 * @param array<string,string> $atts
	 * @return string
	 */
	public static function shortcode( $atts ): string {
		$atts = shortcode_atts(
			array(
				'country' => '',
				'code'    => '',
				'size'    => (string) self::DEFAULT_SIZE,
				'alt'     => '',           // accessibility text; empty → decorative
				'title'   => '',
				'class'   => '',
			),
			$atts,
			'flag'
		);

		// Accept either country= or code=; normalise.
		$code = strtolower( trim( $atts['country'] ?: $atts['code'] ) );

		// Basic validation (circle-flags supports e.g. "gb", "gb-eng", "eu").
		if ( $code === '' || ! preg_match( '/^[a-z0-9-]{2,12}$/', $code ) ) {
			return '<!-- flag: invalid or missing country code -->';
		}

		// Constrain size to sane bounds.
		$size = (int) $atts['size'];
		if ( $size < self::MIN_SIZE ) {
			$size = self::MIN_SIZE; }
		if ( $size > self::MAX_SIZE ) {
			$size = self::MAX_SIZE; }

		// Base URL (filterable so you can switch to local assets later).
		$base = 'https://hatscripts.github.io/circle-flags/flags/';
		/** Allow replacement with local static files, e.g. plugins_url('flags/', __FILE__) */
		$base = apply_filters( 'cfs_flag_base_url', $base );

		// Build URL safely.
		$src = trailingslashit( $base ) . rawurlencode( $code ) . '.svg';

		// Compose attributes.
		$classes = trim( 'cfs-flag ' . (string) $atts['class'] );
		$alt     = (string) $atts['alt'];
		$title   = (string) $atts['title'];

		$attr = array(
			'src'            => esc_url( $src ),
			'width'          => (string) $size,
			'height'         => (string) $size,
			'loading'        => 'lazy',
			'decoding'       => 'async',
			'referrerpolicy' => 'no-referrer',
			'class'          => esc_attr( $classes ),
			'style'          => 'vertical-align:middle; display:inline-block;',
			'alt'            => esc_attr( $alt ),
		);

		if ( $title !== '' ) {
			$attr['title'] = esc_attr( $title );
		}

		// If decorative (no alt text provided), mark as hidden from AT.
		if ( $alt === '' ) {
			$attr['aria-hidden'] = 'true';
			// Keep empty alt="" to meet HTML accessibility expectations.
		}

		/**
		 * Final chance to alter <img> attributes (array of name => value).
		 * Example:
		 *   add_filter('cfs_flag_img_attrs', function($a){ $a['draggable']='false'; return $a; });
		 */
		$attr = apply_filters( 'cfs_flag_img_attrs', $attr, $code, $size );

		// Serialise attributes.
		$html_attrs = '';
		foreach ( $attr as $name => $value ) {
			// Boolean attributes: allow aria-hidden="true" style; skip nulls.
			if ( $value === null || $value === false ) {
				continue; }
			$html_attrs .= ' ' . $name . '="' . $value . '"';
		}

		return '<img' . $html_attrs . ' />';
	}
}

CFS_Country_Flags_Shortcode::init();

/**
 * Small CSS helper for classic editor / front-end.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		$css = '.cfs-flag{image-rendering:auto; max-width:100%; height:auto;}';
		wp_register_style( 'cfs-flag-inline', false );
		wp_enqueue_style( 'cfs-flag-inline' );
		wp_add_inline_style( 'cfs-flag-inline', $css );
	}
);
