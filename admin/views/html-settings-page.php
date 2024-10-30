<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="kp-sip-calculator-header">
	<h1 class="kp-sip-calculator-text"><?php esc_html_e( 'SIP Calculator', 'kp-sip-calculator' ); ?></h1>
</div>
<div id="kp-sip-calculator-admin" class="wrap">  

	<!-- flex container -->
	<div id="kp-sip-calculator-admin-container">

		<div id="kp-sip-calculator-admin-header">

			<!-- header -->
			<div class="kp-sip-calculator-admin-block">               

				<!-- menu -->
				<div id="kp-sip-calculator-menu">
					
						<!-- options -->
						<a href="#" rel="options-general" class="active"><span class="dashicons dashicons-dashboard"></span><?php esc_html_e( 'General', 'kp-sip-calculator' ); ?></a>
						<a href="#customize" rel="options-customize"><span class="dashicons dashicons-admin-appearance"></span><?php esc_html_e( 'Customize', 'kp-sip-calculator' ); ?></a>                        

						<!-- spacer -->
						<hr style="border-top: 1px solid #f2f2f2; border-bottom: 0px; margin: 10px 0px;" />

						<!-- tools -->
						<a href="#tools" rel="tools-plugin"><span class="dashicons dashicons-admin-tools"></span><?php esc_html_e( 'Tools', 'kp-sip-calculator' ); ?></a>                   
					
						<!-- support -->
						<a href="#support" rel="support-support"><span class="dashicons dashicons-editor-help"></span><?php esc_html_e( 'Support', 'kp-sip-calculator' ); ?></a>

				</div>
			</div>

		</div>

		<div style="flex-grow: 1;">
			<div class="kp-sip-calculator-admin-block">

				<!-- version number -->
				<span id="kp-sip-calculator-version" class="kp-sip-calculator-mobile-hide"><?php esc_html_e( 'Version', 'kp-sip-calculator' ); ?> <?php echo esc_html( KP_SIP_CALCULATOR_VERSION ); ?></span>
				
				<!-- main settings form -->
				<form method="post" id="kp-sip-calculator-options-form" enctype="multipart/form-data" data-kp-sip-calculator-option="options">

					<!-- options -->
					<div id="kp-sip-calculator-options">

						<!-- general -->
						<section id="options-general" class="section-content active">
							<?php
								Kp_Sip_Calculator_Settings::settings_header( esc_html__( 'General', 'kp-sip-calculator' ), 'dashicons-dashboard' );
								Kp_Sip_Calculator_Settings::settings_section( 'kp_sip_calculator_options', 'general' );
							?>
						</section>

						<!-- CSS -->
						<section id="options-customize" class="section-content">
							<?php
								Kp_Sip_Calculator_Settings::settings_header( esc_html__( 'Customize', 'kp-sip-calculator' ), 'dashicons-admin-appearance' );
								Kp_Sip_Calculator_Settings::settings_section( 'kp_sip_calculator_options', 'customize' );
							?>
						</section>                          
						
					</div>

					<!-- tools -->
					<div id="kp-sip-calculator-tools">

						<section id="tools-plugin" class="section-content">
							<?php
								Kp_Sip_Calculator_Settings::settings_header( esc_html__( 'Tools', 'kp-sip-calculator' ), 'dashicons-admin-tools' );
								Kp_Sip_Calculator_Settings::settings_section( 'kp_sip_calculator_tools', 'tools' );
							?>
						</section>

					</div>

					<!-- save button -->
					<div id="kp-sip-calculator-save" style="margin-top: 20px;">
						<?php Kp_Sip_Calculator_Settings::action_button( 'save_settings', __( 'Save Changes', 'kp-sip-calculator' ) ); ?>
					</div>

				</form>               

				<!-- support -->
				<section id="support-support" class="section-content">
					<?php require_once KP_SIP_CALCULATOR_DIR . '/admin/views/html-support-page.php'; ?>
				</section>				
			</div>
		</div>
	</div>
</div>
