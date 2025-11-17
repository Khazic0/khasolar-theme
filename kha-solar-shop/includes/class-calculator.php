<?php
/**
 * Solar System Calculator Class
 *
 * Interactive calculator to help customers determine their solar system needs.
 *
 * @package KhaSolar
 * @since   1.0.0
 */

namespace KhaSolar;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculator class.
 */
class Calculator {

	/**
	 * Average electricity rate in Vietnam (VND per kWh).
	 *
	 * @var int
	 */
	private $electricity_rate = 2500;

	/**
	 * Average sun hours per day in Vietnam.
	 *
	 * @var float
	 */
	private $sun_hours = 4.5;

	/**
	 * System efficiency factor.
	 *
	 * @var float
	 */
	private $system_efficiency = 0.8;

	/**
	 * Panel area per kW (m²).
	 *
	 * @var int
	 */
	private $panel_area_per_kw = 7;

	/**
	 * Average cost per kW (VND).
	 *
	 * @var int
	 */
	private $cost_per_kw = 20000000;

	/**
	 * System lifespan in years.
	 *
	 * @var int
	 */
	private $system_lifespan = 25;

	/**
	 * Initialize hooks.
	 */
	public function init() {
		add_shortcode( 'kha_calculator', array( $this, 'calculator_shortcode' ) );
	}

	/**
	 * Calculate solar system requirements from monthly bill.
	 *
	 * @param int    $monthly_bill  Monthly electricity bill in VND.
	 * @param int    $roof_area     Available roof area in m².
	 * @param bool   $with_battery  Include battery storage.
	 * @return array Calculation results.
	 */
	public function calculate_system_size( $monthly_bill, $roof_area, $with_battery = false ) {
		// Convert monthly bill to kWh consumption.
		$consumption_kwh_monthly = $monthly_bill / $this->electricity_rate;
		$consumption_kwh_daily   = $consumption_kwh_monthly / 30;

		// Calculate recommended system size (kW).
		$recommended_system_kw = ( $consumption_kwh_daily / $this->sun_hours ) / $this->system_efficiency;

		// Round up to nearest 0.5 kW.
		$recommended_system_kw = ceil( $recommended_system_kw * 2 ) / 2;

		// Calculate panel area needed.
		$panel_area_needed = $recommended_system_kw * $this->panel_area_per_kw;

		// Check if roof area is sufficient.
		$area_sufficient = $panel_area_needed <= $roof_area;

		// If not enough space, adjust system size.
		if ( ! $area_sufficient ) {
			$recommended_system_kw = floor( ( $roof_area / $this->panel_area_per_kw ) * 2 ) / 2;
			$panel_area_needed     = $recommended_system_kw * $this->panel_area_per_kw;
		}

		// Calculate estimated investment.
		$estimated_investment = $recommended_system_kw * $this->cost_per_kw;

		// Add battery cost if requested (approximately 50% extra).
		if ( $with_battery ) {
			$estimated_investment *= 1.5;
		}

		// Calculate monthly savings.
		$actual_production_kwh_daily   = $recommended_system_kw * $this->sun_hours * $this->system_efficiency;
		$actual_production_kwh_monthly = $actual_production_kwh_daily * 30;

		// Savings limited by consumption.
		$savings_kwh_monthly = min( $actual_production_kwh_monthly, $consumption_kwh_monthly );
		$monthly_savings     = $savings_kwh_monthly * $this->electricity_rate;

		// Calculate ROI.
		$annual_savings = $monthly_savings * 12;
		$roi_years      = $annual_savings > 0 ? $estimated_investment / $annual_savings : 0;

		// Calculate total savings over system lifespan.
		$total_savings_lifetime = $annual_savings * $this->system_lifespan;

		// Get product recommendations.
		$recommended_products = $this->get_product_recommendations( $recommended_system_kw, $with_battery );

		return array(
			'consumption_kwh_monthly' => round( $consumption_kwh_monthly, 1 ),
			'consumption_kwh_daily'   => round( $consumption_kwh_daily, 1 ),
			'recommended_system_kw'   => round( $recommended_system_kw, 1 ),
			'panel_area_needed'       => round( $panel_area_needed, 0 ),
			'area_sufficient'         => $area_sufficient,
			'estimated_investment'    => round( $estimated_investment, -6 ), // Round to millions.
			'monthly_savings'         => round( $monthly_savings, -3 ), // Round to thousands.
			'annual_savings'          => round( $annual_savings, -3 ),
			'roi_years'               => round( $roi_years, 1 ),
			'total_savings_lifetime'  => round( $total_savings_lifetime, -6 ),
			'system_lifespan'         => $this->system_lifespan,
			'recommended_products'    => $recommended_products,
			'with_battery'            => $with_battery,
		);
	}

	/**
	 * Get product recommendations based on system size.
	 *
	 * @param float $system_kw    System size in kW.
	 * @param bool  $with_battery Include battery.
	 * @return array Product recommendations.
	 */
	public function get_product_recommendations( $system_kw, $with_battery = false ) {
		$recommendations = array(
			'inverter' => null,
			'panels'   => null,
			'battery'  => null,
		);

		// Find suitable inverter.
		$inverter_args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_power',
					'value'   => $system_kw * 1000, // Convert to W.
					'type'    => 'NUMERIC',
					'compare' => '>=',
				),
			),
			'tax_query'      => array(
				array(
					'taxonomy' => 'kha_product_cat',
					'field'    => 'slug',
					'terms'    => 'inverter',
				),
			),
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_power',
			'order'          => 'ASC',
		);

		$inverter_query = new \WP_Query( $inverter_args );

		if ( $inverter_query->have_posts() ) {
			$inverter_query->the_post();
			$recommendations['inverter'] = $this->format_product_data( get_the_ID() );
			wp_reset_postdata();
		}

		// Find suitable solar panels.
		$panels_args = array(
			'post_type'      => 'kha_product',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'tax_query'      => array(
				array(
					'taxonomy' => 'kha_product_cat',
					'field'    => 'slug',
					'terms'    => 'solar-panel',
				),
			),
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_price',
			'order'          => 'ASC',
		);

		$panels_query = new \WP_Query( $panels_args );

		if ( $panels_query->have_posts() ) {
			$panels_query->the_post();
			$panel_data = $this->format_product_data( get_the_ID() );

			// Calculate number of panels needed.
			$panel_power = get_post_meta( get_the_ID(), '_power', true );
			if ( $panel_power ) {
				$panel_count = ceil( ( $system_kw * 1000 ) / $panel_power );
				$panel_data['quantity']      = $panel_count;
				$panel_data['total_price']   = $panel_data['price_raw'] * $panel_count;
				$panel_data['total_price_formatted'] = kha_solar_format_price( $panel_data['total_price'] );
			}

			$recommendations['panels'] = $panel_data;
			wp_reset_postdata();
		}

		// Find battery if requested.
		if ( $with_battery ) {
			$battery_args = array(
				'post_type'      => 'kha_product',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'tax_query'      => array(
					array(
						'taxonomy' => 'kha_product_cat',
						'field'    => 'slug',
						'terms'    => 'battery',
					),
				),
				'orderby'        => 'meta_value_num',
				'meta_key'       => '_price',
				'order'          => 'ASC',
			);

			$battery_query = new \WP_Query( $battery_args );

			if ( $battery_query->have_posts() ) {
				$battery_query->the_post();
				$recommendations['battery'] = $this->format_product_data( get_the_ID() );
				wp_reset_postdata();
			}
		}

		return $recommendations;
	}

	/**
	 * Format product data for recommendations.
	 *
	 * @param int $product_id Product ID.
	 * @return array Product data.
	 */
	private function format_product_data( $product_id ) {
		$price      = get_post_meta( $product_id, '_price', true );
		$sale_price = get_post_meta( $product_id, '_sale_price', true );
		$final_price = $sale_price ? $sale_price : $price;

		return array(
			'id'              => $product_id,
			'title'           => get_the_title( $product_id ),
			'url'             => get_permalink( $product_id ),
			'image'           => get_the_post_thumbnail_url( $product_id, 'medium' ),
			'price'           => kha_solar_format_price( $final_price ),
			'price_raw'       => floatval( $final_price ),
			'power'           => get_post_meta( $product_id, '_power', true ),
			'on_sale'         => ! empty( $sale_price ),
			'quantity'        => 1,
		);
	}

	/**
	 * Calculate bundle total price.
	 *
	 * @param array $products Product recommendations.
	 * @return array Bundle pricing.
	 */
	public function calculate_bundle_price( $products ) {
		$total = 0;

		if ( ! empty( $products['inverter'] ) ) {
			$total += $products['inverter']['price_raw'];
		}

		if ( ! empty( $products['panels'] ) ) {
			$total += $products['panels']['total_price'];
		}

		if ( ! empty( $products['battery'] ) ) {
			$total += $products['battery']['price_raw'];
		}

		$discount_percent = 10; // 10% bundle discount.
		$discount_amount  = $total * ( $discount_percent / 100 );
		$bundle_price     = $total - $discount_amount;

		return array(
			'total_price'      => $total,
			'discount_percent' => $discount_percent,
			'discount_amount'  => $discount_amount,
			'bundle_price'     => $bundle_price,
			'total_formatted'  => kha_solar_format_price( $total ),
			'bundle_formatted' => kha_solar_format_price( $bundle_price ),
		);
	}

	/**
	 * Save lead data from calculator.
	 *
	 * @param array $lead_data Lead information.
	 * @return int|WP_Error Lead ID or error.
	 */
	public function save_lead( $lead_data ) {
		// Create lead as custom post type (you may want to create this).
		$lead_id = wp_insert_post(
			array(
				'post_type'   => 'kha_lead',
				'post_title'  => $lead_data['name'] . ' - ' . $lead_data['phone'],
				'post_status' => 'publish',
			)
		);

		if ( is_wp_error( $lead_id ) ) {
			return $lead_id;
		}

		// Save lead meta.
		update_post_meta( $lead_id, '_lead_name', sanitize_text_field( $lead_data['name'] ) );
		update_post_meta( $lead_id, '_lead_email', sanitize_email( $lead_data['email'] ) );
		update_post_meta( $lead_id, '_lead_phone', sanitize_text_field( $lead_data['phone'] ) );
		update_post_meta( $lead_id, '_lead_source', 'calculator' );
		update_post_meta( $lead_id, '_calculation_data', $lead_data['calculation_data'] );
		update_post_meta( $lead_id, '_lead_date', current_time( 'mysql' ) );

		return $lead_id;
	}

	/**
	 * Calculator shortcode.
	 *
	 * @return string Calculator HTML.
	 */
	public function calculator_shortcode() {
		ob_start();
		include KHA_PLUGIN_DIR . 'templates/calculator.php';
		return ob_get_clean();
	}
}
