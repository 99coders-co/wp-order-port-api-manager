<?php 
    $api_integration = new OrderportAPI();
    // Call the API
    $resp=$api_integration->callListOfAllProductsApi();
    $responseObj=json_decode($resp);
    $html='<div class="filter__block">';
    $html.='<div class="sales-products-filter"><select class="filter-type" id="filter_val">';
    if(count($responseObj->Data->Groups)>0){
        
        for($j=0;$j<count($responseObj->Data->Groups);$j++){

            //echo "<pre>";print_r($responseObj->Data->Groups);die;

            $html.='<option value="'.$responseObj->Data->Groups[$j]->Name.'">'.$responseObj->Data->Groups[$j]->Name.'</option>';
            if($responseObj->Data->Groups[$j]->SubGroups){
            if(count($responseObj->Data->Groups[$j]->SubGroups)>0){
                for($k=0;$k<count($responseObj->Data->Groups[$j]->SubGroups);$k++){
                        $html.='<option value="'.$responseObj->Data->Groups[$j]->SubGroups[$k]->Name.'" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$responseObj->Data->Groups[$j]->SubGroups[$k]->Name.'</option>';
                   }
             }
            }
        }
    }

    $html.='</select></div></div>';


    ?>
<div class="wrap">
    <h2>OrderPort API Credentials Settings</h2>
    <form method="post" action="options.php">
        <?php settings_fields('order_port_api_plugin_settings'); ?>
        <?php do_settings_sections('orderport-api-plugin-settings'); 
      
          ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">ClientId</th>
                <td><input type="text" name="order_port_client_id" value="<?php echo esc_attr(get_option('order_port_client_id')); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">API Access Key</th>
                <td><input type="text" name="order_port_access_key" value="<?php echo esc_attr(get_option('order_port_access_key')); ?>" /></td>
            </tr>

             <tr valign="top">
                <th scope="row">API Token</th>
                <td><input type="text" name="order_port_token" value="<?php echo esc_attr(get_option('order_port_token')); ?>" /></td>
            </tr>

        </table>

        <?php submit_button(); ?>
    </form>

     <hr>

    <div class="">

    <h2>Shortcode Details</h2>
     Generate Shortcode: <div class='shortcode_box'><?php echo $html;?> <strong id="dynamic_shortcode">[orderport-products]</strong></div>
    <p> List of all product shortcode  : <strong> [orderport-products]</strong> </p>
    <p> Product Detail page shortcode  : <strong> [detail-info-shortcode]</strong> </p>
    <p> List of all product shortcode  : <strong> [list-shortcode]</strong> </p>
    
    <hr>
</div>

</div>
<style>
    .shortcode_box {
    display: flex;
    align-items: center;
    background-color: #fff;
    padding: 30px;
    margin-top: 10px;
}
strong#dynamic_shortcode {
    margin-left: 20px;
}
</style>
