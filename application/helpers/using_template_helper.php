<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


    if ( ! function_exists('show'))
    {
        function  show($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_page_view', $data);
        }
    }
    
    
        if ( ! function_exists('datatables'))
    {
        function  datatables($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_datatables_view', $data);
        }
    }
    
    
       if ( ! function_exists('form'))
    {
        function  form($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_form_view', $data);
        }
    }
    
     if ( ! function_exists('form_col2'))
    {
        function  form_col2($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_form_col2_view', $data);
        }
    }
    
    
    
      if ( ! function_exists('report'))
    {
        function  report($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_report_view', $data);
        }
    }
    
    
      if ( ! function_exists('tables'))
    {
        function  tables($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_tables_view', $data);
        }
    }
    
    
    if ( ! function_exists('pesan'))
    {
        function  pesan($view, $data)
        {
           global $template;
           $ci = &get_instance();
           $data['view'] = $view;
           $ci->load->view('templates/'.$template.'/template_pesan_view', $data);
        }
    }
    
    
    
    
/* End of file using_template.php */
/* Location: ./system/application/helpers/using_template.php */