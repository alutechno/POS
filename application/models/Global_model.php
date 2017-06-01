<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_model extends CI_Model
{
    
    function get_menu_name($id)
    {
        $query = $this->db->query("select * from inv_outlet_menus where id=".$id."");

		if ($query->num_rows() > 0)
	{
            foreach ($query->result() as $row)
            {
                    $name= $row->name;
            }
			 return $name;
	}

    }
    
    
    function get_outlet_name($id)
    {
        $query = $this->db->query("select * from inv_outlet_menus where id=".$id."");

		if ($query->num_rows() > 0)
	{
            foreach ($query->result() as $row)
            {
                    $name= $row->name;
            }
			 return $name;
	}

    }
    
    function get_tot_food($table_id)
    {
        //select sum(amount) as tot from pos_outlet_order_detil WHERE table_id= and outlet_id= and menu_class_id=1
            $query = $this->db->query("select sum(amount) as total from pos_outlet_order_detil WHERE is_void=0 and table_id=".$this->uri->segment(3)." and outlet_id=".$this->session->userdata('outlet')."  and menu_class_id=1");

					if ($query->num_rows() > 0)
	{
            foreach ($query->result() as $row)
            {
                    $total= $row->total;
            }
            
            return $total;
	}
    }
    
    
     function get_tot_beverages($table_id)
    {
        //select sum(amount) as tot from pos_outlet_order_detil WHERE table_id= and outlet_id= and menu_class_id=1
            $query = $this->db->query("select sum(amount) as total from pos_outlet_order_detil WHERE  is_void=0 and table_id=".$this->uri->segment(3)." and outlet_id=".$this->session->userdata('outlet')."  and menu_class_id=2");
		if ($query->num_rows() > 0)
	{
            foreach ($query->result() as $row)
            {
                    $total= $row->total;
            }
            
            return $total;
	}
        
    }
    
    //select table_id, closed_bill from pos_outlet_order_detil where outlet_id=1 GROUP BY table_id,closed_bill

         function get_table_available3($table_id,$outlet_id)
    {
        //select sum(amount) as tot from pos_outlet_order_detil WHERE table_id= and outlet_id= and menu_class_id=1
            $closed_bill=1;
             $query = $this->db->query("select closed_bill from pos_outlet_order_detil where outlet_id=".$outlet_id." and table_id=".$table_id." GROUP BY closed_bill");
            // echo $this->db->last_query();exit;
		if ($query->num_rows() > 0)
	{
            foreach ($query->result() as $row)
            {
                    $closed_bill= $row->closed_bill;
            }
            
            return $closed_bill;
	}
        
    }


       function get_table_available($table_id,$outlet_id)
    {
        //select sum(amount) as tot from pos_outlet_order_detil WHERE table_id= and outlet_id= and menu_class_id=1
            $closed_bill=1;
             $query = $this->db->query("select closed_bill from pos_outlet_order_detil where outlet_id=".$outlet_id." and table_id=".$table_id." GROUP BY closed_bill");

            foreach ($query->result() as $row)
            {
                    $closed_bill= $row->closed_bill;
            }
            
            return $closed_bill;
        
      
    }
    
    
    function get_no_bill($table_id)
    {
        //select order_no  from pos_outlet_order_detil where table_id=1 and closed_bill=0 group by order_no
 
        $query = $this->db->query("select order_no  from pos_outlet_order_header where table_no=".$table_id." and outlet_id=".$this->session->userdata('outlet')." group by order_no");

            foreach ($query->result() as $row)
            {
                $order_no= $row->order_no;
            }
            
            return $order_no;
            
    }

    function get_meal_time()
    {
        date_default_timezone_set('Asia/Jakarta');

       $query = $this->db->query(" select id from ref_meal_time where '".date('H:i')."' BETWEEN start_time and end_time limit 1 ");

            foreach ($query->result() as $row)
            {
                $strid= $row->id;
            }
            
            return $strid;

    }

    function get_meal_time_name()
    {
        date_default_timezone_set('Asia/Jakarta');

       $query = $this->db->query("select name from ref_meal_time where '".date('H:i')."' BETWEEN start_time and end_time limit 1 ");
//echo $this->db->last_query();
            foreach ($query->result() as $row)
            {
                    $strname= $row->name;
            }
            
            return $strname;

    }

    function guest()
    {

        $query=$this->db->query("SELECT 
        `a`.`id` AS `folio_id`,
        CONCAT(`b`.`first_name`, `b`.`last_name`, ',') AS `guest_name`,
        `d`.`name` AS `room_type`,
        `e`.`code` AS `room_no`,
        CONCAT(`e`.`fo_status`, `e`.`hk_status`) AS `room_status`,
        `a`.`arrival_date` AS `arrival_date`,
        `a`.`check_in_time` AS `check_in_time`,
        `a`.`departure_date` AS `departure_date`,
        `a`.`check_out_time` AS `check_out_time`,
        `a`.`num_of_nights` AS `num_of_nights`,
        `a`.`num_of_pax` AS `num_of_pax`,
        `a`.`num_of_child` AS `num_of_child`,
        `a`.`reservation_status` AS `reservation_status`,
        `g`.`code` AS `room_rate_code`,
        `a`.`room_rate_amount` AS `room_rate_amount`,
        `a`.`discount_amount` AS `discount_amount`,
        `k`.`name` AS `cust_segment`,
        `n`.`name` AS `nationality`,
        `a`.`reservation_type` AS `reservation_type`,
        `a`.`mice_id` AS `mice_id`,
        `t`.`closing_amount` AS `balance`,
        `c`.`name` AS `company_name`,
        `f`.`name` AS `vip_type`,
        `a`.`cancellation_type_id` AS `cancellation_type_id`,
        `r`.`name` AS `cancellation_type_name`
    FROM
        (((((((((((((`media`.`fd_guest_folio` `a`
        LEFT JOIN `media`.`mst_customer` `b` ON ((`a`.`customer_id` = `b`.`id`)))
        LEFT JOIN `media`.`mst_cust_company` `c` ON ((`a`.`cust_company_id` = `c`.`id`)))
        LEFT JOIN `media`.`ref_room_type` `d` ON ((`a`.`room_type_id` = `d`.`id`)))
        LEFT JOIN `media`.`mst_room` `e` ON ((`a`.`room_id` = `e`.`id`)))
        LEFT JOIN `media`.`ref_vip_type` `f` ON ((`a`.`vip_type_id` = `f`.`id`)))
        LEFT JOIN `media`.`mst_room_rate` `g` ON ((`a`.`room_rate_id` = `g`.`id`)))
        LEFT JOIN `media`.`mst_room_rate_line_item` `h` ON (((`a`.`room_type_id` = `h`.`room_type_id`)
            AND (`a`.`room_rate_id` = `h`.`room_rate_id`))))
        LEFT JOIN `media`.`ref_payment_method` `i` ON ((`a`.`payment_type_id` = `i`.`id`)))
        LEFT JOIN `media`.`ref_segment_type` `k` ON ((`a`.`segment_type_id` = `k`.`id`)))
        LEFT JOIN `media`.`ref_country` `n` ON ((`a`.`origin_country_id` = `n`.`ID`)))
        LEFT JOIN `media`.`ref_cancellation_type` `r` ON ((`a`.`cancellation_type_id` = `r`.`id`)))
        LEFT JOIN `media`.`fd_mice_reservation` `s` ON ((`a`.`mice_id` = `s`.`id`)))
        LEFT JOIN `media`.`fd_mice_deposit` `t` ON ((`a`.`mice_id` = `t`.`mice_id`)))
    WHERE
        (`a`.`reservation_status` = '4')");
            return $query;

    }




    
}
