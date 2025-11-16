<?php
/**
 * Solar system calculator functionality.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

namespace KhaSolar;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Calculator class.
 *
 * Handles solar system size calculations.
 */
class Calculator {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_shortcode( 'kha_solar_calculator', array( $this, 'calculator_shortcode' ) );
	}

	/**
	 * Calculate solar system requirements.
	 *
	 * @param float $daily_usage      Daily energy usage in kWh.
	 * @param float $sunlight_hours   Average daily sunlight hours (optional).
	 * @param float $system_efficiency System efficiency (optional).
	 * @return array
	 * @since 1.0.0
	 */
	public function calculate( $daily_usage, $sunlight_hours = null, $system_efficiency = null ) {
		// Get default values from settings if not provided.
		if ( null === $sunlight_hours ) {
			$sunlight_hours = floatval( get_option( 'kha_solar_avg_sunlight_hours', 4.5 ) );
		}

		if ( null === $system_efficiency ) {
			$system_efficiency = floatval( get_option( 'kha_solar_system_efficiency', 0.8 ) );
		}

		// Ensure valid values.
		$daily_usage       = floatval( $daily_usage );
		$sunlight_hours    = floatval( $sunlight_hours );
		$system_efficiency = floatval( $system_efficiency );

		if ( $daily_usage <= 0 || $sunlight_hours <= 0 || $system_efficiency <= 0 ) {
			return array(
				'error' => __( 'Invalid input values.', 'kha-solar' ),
			);
		}

		// Calculate required system size in kW.
		// Formula: (Daily Usage / Sunlight Hours / System Efficiency).
		$system_size_kw = $daily_usage / $sunlight_hours / $system_efficiency;

		// Convert to Watts.
		$system_size_w = $system_size_kw * 1000;

		// Calculate number of panels (assuming 450W panels).
		$panel_wattage = 450;
		$panel_count   = ceil( $system_size_w / $panel_wattage );

		// Calculate actual system size based on panel count.
		$actual_system_size_kw = ( $panel_count * $panel_wattage ) / 1000;

		// Calculate estimated monthly production.
		$monthly_production = $actual_system_size_kw * $sunlight_hours * 30 * $system_efficiency;

		// Calculate estimated monthly savings (assuming 3000 VND per kWh).
		$electricity_rate = floatval( get_option( 'kha_solar_electricity_rate', 3000 ) );
		$monthly_savings  = $monthly_production * $electricity_rate;

		// Calculate estimated annual savings.
		$annual_savings = $monthly_savings * 12;

		// Recommended products based on system size.
		$recommended_products = $this->get_recommended_products( $actual_system_size_kw );

		return array(
			'daily_usage'            => $daily_usage,
			'system_size_kw'         => round( $system_size_kw, 2 ),
			'actual_system_size_kw'  => round( $actual_system_size_kw, 2 ),
			'panel_count'            => $panel_count,
			'panel_wattage'          => $panel_wattage,
			'monthly_production'     => round( $monthly_production, 2 ),
			'monthly_savings'        => round( $monthly_savings, 0 ),
			'annual_savings'         => round( $annual_savings, 0 ),
			'sunlight_hours'         => $sunlight_hours,
			'system_efficiency'      => $system_efficiency,
			'recommended_products'   => $recommended_products,
		);
	}

	/**
	 * Get recommended products based on system size.
	 *
	 * @param float $system_size_kw System size in kW.
	 * @return array
	 * @since 1.0.0
	 */
	private function get_recommended_products( $system_size_kw ) {
		// Query products with power capacity meta.
		$args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => 5,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_kha_product_power',
					'value'   => array( $system_size_kw * 0.8, $system_size_kw * 1.2 ),
					'type'    => 'NUMERIC',
					'compare' => 'BETWEEN',
				),
			),
		);

		$query = new \WP_Query( $args );

		$products = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$product_id = get_the_ID();
				$price      = get_post_meta( $product_id, '_kha_product_price', true );
				$power      = get_post_meta( $product_id, '_kha_product_power', true );

				$products[] = array(
					'id'    => $product_id,
					'title' => get_the_title(),
					'url'   => get_permalink(),
					'image' => get_the_post_thumbnail_url( $product_id, 'kha-product-thumbnail' ),
					'price' => $price ? kha_solar_format_price( $price ) : '',
					'power' => $power,
				);
			}
			wp_reset_postdata();
		}

		// If no products found in range, get general solar panel products.
		if ( empty( $products ) ) {
			$args = array(
				'post_type'      => 'kha_product',
				'posts_per_page' => 5,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'kha_product_cat',
						'field'    => 'slug',
						'terms'    => 'solar-panels',
					),
				),
			);

			$query = new \WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$product_id = get_the_ID();
					$price      = get_post_meta( $product_id, '_kha_product_price', true );

					$products[] = array(
						'id'    => $product_id,
						'title' => get_the_title(),
						'url'   => get_permalink(),
						'image' => get_the_post_thumbnail_url( $product_id, 'kha-product-thumbnail' ),
						'price' => $price ? kha_solar_format_price( $price ) : '',
					);
				}
				wp_reset_postdata();
			}
		}

		return $products;
	}

	/**
	 * Calculator shortcode.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function calculator_shortcode() {
		ob_start();
		kha_solar_get_template( 'calculator.php' );
		return ob_get_clean();
	}
}
