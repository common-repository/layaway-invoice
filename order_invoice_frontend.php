<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

           
/***********Add custom column into layaway invoice table***********/

// Add custom column to the post list table
function laya_invc_custom_column_to_post_list($columns) {
    if (current_user_can('manage_options')) {     

            $columns['post_status']   = 'Status';
            $columns['customer_name'] = 'Name';
            $columns['phone_number']  = 'Number';
            $columns['customer_mail'] = 'Email';
            $columns['order_amount']  = 'Total Amount';
            $columns['remaining_amount']  = 'Rem.. amount';
            $columns['last_date_of_payment']  = 'Expire date';
            $columns['last_trasaction_date_and_amount']  = 'Payment history';
    }
            return $columns;
}
add_filter('manage_laya_invc_order_posts_columns', 'laya_invc_custom_column_to_post_list');

    function laya_invc_gcode_s_r() {
       
           $laya_invc_gcode_sr_e = get_option('laya_invc_gcode_s',true);
           
           if ($laya_invc_gcode_sr_e) {
               
                $laya_invc_gcode_sr_d = base64_decode($laya_invc_gcode_sr_e);
           }
           

            echo esc_html($laya_invc_gcode_sr_d);
       }


 /***********************************************/
// Populate custom column with data
function laya_invc_populate_custom_column_data($column, $post_id) {
     
     if (current_user_can('read')) {     
       
            //plugin base color
            $base_color         = esc_html(get_option('laya_invc_layaway_base_color'));
                
        
            if ($column === 'post_status') {

                   $transaction_id    = esc_html(get_post_meta($post_id, '_transaction_id', true)); 
                 
            
                     if(empty($transaction_id))
                    {
                        
                        echo esc_html(get_post_meta($post_id,'status', true));
                    }
                    else {
                             //update status  completed when order have done before
                        update_post_meta( $post_id,'status','completed');
                       echo esc_html(get_post_meta($post_id,'status', true));

                    }
                   
                   
            }
            if ($column === 'customer_name') {

                     $customer_first_name = esc_html(get_post_meta($post_id,'first_name', true));
                     $customer_last_name  = esc_html(get_post_meta($post_id,'last_name', true));

                   echo esc_html($customer_first_name).esc_html($customer_last_name);

            }
             if ($column === 'customer_mail') {

                
                   echo esc_html(get_post_meta($post_id,'email', true));

            }
            if ($column === 'phone_number') {
                   echo esc_html(get_post_meta($post_id,'phone_number', true));

            }
            if ($column === 'order_amount') {
                     echo  '$'.esc_html(get_post_meta($post_id,'amount', true));

            }

             if ($column === 'remaining_amount') {
                $customer_remining_amount = esc_html(get_post_meta($post_id,'customer_payment', true));
                       
                //check if order order status is active or new created invoice
                    $check_order_status       = esc_html(get_post_meta($post_id,'status', true));

                    if ($check_order_status=='active') {
                        
                        echo'';
                    }else{

                     if(empty($customer_remining_amount))
                       {
                    
                     echo '$0';
                     
                       }else{
                   
                     echo  '$'.esc_html($customer_remining_amount);
                       }
 
                    }

            }

            if ($column === 'last_date_of_payment') {

                  $invoice_expire_date= esc_html(get_post_meta($post_id, '_invoice_expiration_date', true));
                  if (!empty( $invoice_expire_date)) {
                      
                    echo esc_html($invoice_expire_date);
                  }

            }
           
             if ($column === 'last_trasaction_date_and_amount') {

                  $last_trasaction_date_and_amount_in__json  = get_post_meta($post_id, '_new_transaction_date_and_amount', true);
                  $last_trasaction_date_and_amount_in__decode =json_decode($last_trasaction_date_and_amount_in__json,true);

                  if (!empty($last_trasaction_date_and_amount_in__json)) {
        
                     foreach ($last_trasaction_date_and_amount_in__decode as $trasaction_date => $trasaction_amount) 
                     { 
                        $trasaction_date_with_time = strtotime($trasaction_date);
                        $trasaction_date_without_time    = gmdate('Y-m-d', $trasaction_date_with_time); 
                        echo '<div>';
                        echo '<span id="trasc_date">'. esc_html($trasaction_date_without_time). '</span>&#x279B;<span style="color:'.esc_html($base_color).'!important" id="trasc_amount">$' . esc_html($trasaction_amount). '</span>';
                        echo '</div>';
                }
            }
        }
    }
}
add_action('manage_laya_invc_order_posts_custom_column', 'laya_invc_populate_custom_column_data', 10, 2);


function laya_invc_cc_li(){

 if (function_exists('laya_invc_ginvc_res_non_res_li') && function_exists('laya_invc_ctlip')) {

  $laya_invc_g_li = laya_invc_ginvc_res_non_res_li();
  $laya_invc_t_cinvc  = laya_invc_ctlip('laya_invc_order');
  $laya_invc_monce_r_c = laya_invc_com_acs($laya_invc_t_cinvc,$laya_invc_g_li);

}                 


 return $laya_invc_monce_r_c;

}

/*************Add order invoice page into my account page ********************/

function laya_invc_order_invoice_set_up_nav_item($nav_items)
{    
    if (current_user_can('read')) {     

            // Add Order Invoice in my account dashboard before logout page
            $tail_items = array_merge(
                ['layaway-invoice' => 'Layaway Invoice'],
                array_splice( $nav_items, -1, 1 )
            );

            // Add order invoice page and return list of pages in array on dashboard
            return array_merge($nav_items, $tail_items);
      }

         return $nav_items;      
}


//query to get menu items 
add_filter( 'woocommerce_account_menu_items', 'laya_invc_order_invoice_set_up_nav_item' );

function laya_invc_order_invoice_add_query_var($query_vars)
{
       
      if (current_user_can('read')) {     

            $query_vars['layaway-invoice'] = 'layaway-invoice';

       }
            return $query_vars;
}


//Add order invoice page endpoint 
add_filter( 'woocommerce_get_query_vars', 'laya_invc_order_invoice_add_query_var' );

    function laya_invc_ginvc_res_non_res_li() {
          
        $laya_invc_d = get_option('laya_invc_gcode_d',true);
        $laya_invc_s = get_option('laya_invc_gcode_s',true);

        
        if (!empty($laya_invc_d) && !empty($laya_invc_s)) {
      
            $decoded_d = base64_decode($laya_invc_d);
            $decoded_s = base64_decode($laya_invc_s);

          
            if (is_numeric($decoded_d) && is_numeric($decoded_s)) {
                $laya_invc_t_a_invc = $decoded_d + $decoded_s;
                return $laya_invc_t_a_invc;
            }

       }
    }

?>