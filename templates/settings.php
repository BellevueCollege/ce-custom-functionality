<?php
   // Set options property
   $this->options = get_option( CE_Plugin_Config::get_options_var_name() );
?>
<div class="wrap">
        <h2>Continuing Education Custom Functions Settings</h2>           
        <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields(CE_Plugin_Config::get_options_group_name());
        do_settings_sections(CE_Plugin_Config::get_options_menu_slug());
        submit_button(); 
        ?>
        </form>
</div>