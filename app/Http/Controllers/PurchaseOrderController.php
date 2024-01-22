<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

class PurchaseOrderController extends Controller
{
    public function showForm()
    {
        return view('upload-pdf');
    }

    public function processPdf(Request $request){
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:10240',
        ]);

        $pdfPath = $request->file('pdf')->path();

        $path = 'c:/Program Files/Git/mingw64/bin/pdftotext';
        $text = (new Pdf())
            ->getText($pdfPath, $path);



        $keyValuePairs = $this->parseTextContent($text);
        $jsonContent = json_encode($keyValuePairs, JSON_PRETTY_PRINT);
        return ($jsonContent);

        return response()->json(['data' => $jsonContent]);
    }


    private function parseTextContent($text)
    {
        $lines = explode("\n", $text);
        $keyValuePairs = [];


        // $company_details = explode('Phone', $lines[0], 2);
        $company_details = explode('d ', $lines[0], 2);


        $company_address = array(
            'name' => $company_details[0],
            'address' => $company_details[1]
        );
        
        $ship_to = array(
            'name' => $lines[2],
            'address1' => $lines[3],
            'address2' => $lines[4]
        );

        $phones = explode('Phone', $lines[6], 2);
        $empty_check = explode(':', $phones[1], 2);
        $mails = explode(':', $empty_check[1], 2);

        $contact = array(
            'name' => $phones[0],
            'phone' => $empty_check[0],
            'mail' => $mails[1],
        );

        
        $purchase_order_details = explode(' ', $lines[10], 2);
        $vendor_no = explode('.', $purchase_order_details[1], 2);

        $sapace = explode(' ', $vendor_no[1], 2);
        $order = explode(' ', $sapace[1], 2);
        $order_date = explode(' ', $order[1], 2);
        $delivery = explode(' ', $order_date[1], 2);
        $delivery_date = explode(' ', $delivery[1], 2);


        $purchase_order_data = explode(' ', $lines[12], 2);
        $no_value = explode(' ', $purchase_order_data[0], 2);
        $vendor_no_value = explode(' ', $purchase_order_data[1], 2);
        $order_date_value = explode(' ', $vendor_no_value[1], 2);
        $order_time_value = explode(' ', $order_date_value[1], 2);

        $purchase_order = array(
            'purchase_order' => $lines[8],
            'purchase_order_no'=>$purchase_order_details[0],
            'vendor_no'=>$vendor_no[0],
            'order_date'=> $order[0].' '.$order_date[0],
            'delivery_date'=> $delivery[0].' '.$order_date[0],
            'delivery_time'=> $delivery_date[1],
            'no_value'=>$no_value[0],
            'vendor_no_value'=>$vendor_no_value[0],
            'order_date_value'=>$order_date_value[0],
            'order_time_value'=>$order_time_value[0],
        );
         
        $order_details = explode('au.', $lines[14], 2);
        $order_contact = explode(': ', $order_details[1], 2);
        $order_contact_name = explode(':', $order_contact[1], 2);

        $contact_order_details=array(
            'order_details'=>$order_details[0],
            'order_contact'=>$order_contact[0],
            'order_contact_name'=>$order_contact_name[0],
            'order_contact_email'=>$order_contact_name[1],
        );


        
        $vendor_title = explode('r', $lines[18], 2);
        $vendor_item_no = explode('.', $lines[22], 2);
        $quantity  = explode(' UOM', $lines[32], 2);
        $uom = explode('Quantity ', $lines[32], 2);
        $total = explode(' ', $lines[39], 2);
        $weight = explode(' ', $lines[41], 2);
        $price = explode(' ', $total[1], 2);
        $per = explode(' ', $price[1], 2);
        $unit_aud = explode(' ', $weight[1], 2);
        
        $table_head=array(
            'no'=>$lines[20],
            'vendor_item_no'=>$vendor_title[0].' '.$vendor_item_no[0],
            'description'=>$vendor_item_no[1],
            'quantity'=>$quantity[0],
            'uom'=>$uom[1],
            'qty_uom'=>$lines[36],
            'total_weight'=>$total[0].' '.$weight[0],
            'price_per_unit_aud'=>$price[0].' '.$per[0].' '.$unit_aud[0].' '.$unit_aud[1],
            'total_price_aud'=>$total[0].' '.$price[0].' '.$unit_aud[1],
        );

        $roy_one_no = explode(' ', $lines[24], 2);
        $roy_two_no = explode(' ', $roy_one_no[1], 2);

        $roy_one_quantity = explode(' ', $lines[33], 2);
        $roy_one_uom = explode(' ', $roy_one_quantity[1], 2);
        $roy_two_quantity = explode(' ', $roy_one_uom[1], 2);

        $roy_three_quantity = explode(' ', $lines[34], 2);
        $roy_four_quantity = explode(' ', $lines[59], 2);
        $roy_one_qty_uom = explode(' ', $lines[61], 2);
        $roy_total_weight = explode(' ', $lines[45], 2);        

        $table_roy_one=array(
            'no'=>$roy_one_no[0],
            'vendor_item_no'=>'',
            'description'=>$lines[27],
            'quantity'=>$roy_one_quantity[0],
            'uom'=>$roy_one_uom[0],
            'qty_uom'=>$roy_one_qty_uom[0],
            'total_weight'=>$roy_total_weight[0],
            'price_per_unit_aud'=>$lines[47],
            'total_price_aud'=>$lines[49]
        );

        $table_roy_two=array(
            'no'=>$roy_two_no[0],
            'vendor_item_no'=>'',
            'description'=>$lines[28],
            'quantity'=>$roy_two_quantity[0],
            'uom'=>$roy_one_uom[0],
            'qty_uom'=>$roy_one_qty_uom[0],
            'total_weight'=>'',
            'price_per_unit_aud'=>$lines[51],
            'total_price_aud'=>$lines[53]

        );

        $table_roy_three=array(
            'no'=>$roy_two_no[1],
            'vendor_item_no'=>'',
            'description'=>$lines[29],
            'quantity'=>$roy_three_quantity[0],
            'uom'=>$roy_one_uom[0],
            'qty_uom'=>$roy_one_qty_uom[0],
            'total_weight'=>$roy_total_weight[1],
            'price_per_unit_aud'=>$lines[55],
            'total_price_aud'=>$lines[57]

        );
        $table_roy_four=array(
            'no'=>$lines[25],
            'vendor_item_no'=>'',
            'description'=>$lines[30],
            'quantity'=>$roy_four_quantity[0],
            'uom'=>$roy_one_uom[0],
            'qty_uom'=>$roy_one_qty_uom[0],
            'total_weight'=>'',
            'price_per_unit_aud'=>$lines[63],
            'total_price_aud'=>$lines[65]
        );


        $table_data=array(
            'table_roy_one'=>$table_roy_one,
            'table_roy_two'=>$table_roy_two,
            'table_roy_three'=>$table_roy_three,
            'table_roy_four'=>$table_roy_four,
        
        );


        $total_aud = explode(' ', $lines[61], 2);        

        $data=array(
            'company_address'=>$company_address,
            'ship_to'=>$ship_to,
            'contact'=>$contact,
            'purchase_order'=>$purchase_order,
            'order_details'=>$contact_order_details,
            'critical_page'=>$lines[16],
            'table_head'=>$table_head,
            'table_data'=>$table_data,
            'total_aud'=>$total_aud[1],
            'total_aud_price'=>$lines[67],
            'chef_good_company'=>$lines[69],
            'australian_tax_office'=>$lines[71],
        );

        return $data;
    }
}
