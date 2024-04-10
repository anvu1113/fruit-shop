<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>V-Fruit</title>  
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>
  <div class="header-pdf">
    <div class="content-pdf">    
      <div class="info-vfruit">
        <div class="fl w50p">
          <div><strong style="font-size: 18px; color: #4F46E5">V-Fruit</strong></div>
          <div>
            <strong>Store address: </strong>{!! $address !!}
          </div>
        </div>
        <div class="fr w50p text-right">
          <div><strong>Website:</strong> {{ $website }}</div>
          <div><strong>Email:</strong> {{ $email }}</div>
          <div><strong>Phone:</strong> {{ $phone }}</div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div> 
  <div class="container-pdf">
    <div class="content-pdf">
      <div style="margin-top: 10px">
        <div class="fl w100p color-orange text-center" style="font-size: 22px"><strong>{{ $title }}</strong></div>
        <div class="clear"></div>
      </div>
      <div class="info-client">       
        <div class="fl w50p text-left">
          <div><strong>Name: </strong> <strong>{{ $customer_name }}</strong></div>
          <div<strong>Phone: </strong> {{ $customer_phone }}</div>        
          <div><strong>Address: </strong>{{ $customer_address }}</div>        
        </div>
        <div class="clear"></div>
      </div>        
      <table class="table-info">
        <thead>
          <tr>
            <td width="35px" class="text-right">No</td>
            <td class="text-left">Category</td>
            <td>Item</td>           
            <td>Unit</td>           
            <td width="55px" class="text-right">Quantity</td>
            <td class="text-right">Price</td>
            <td class="text-right">Amount</td>          
          </tr>
        </thead>
        <tbody>      
          @foreach ($invoice_details as $key => $detail)
            <tr>
              <td class="text-right"> {{  $key+1 }}</td>
              <td>{{ $detail['category'] }}</td>                     
              <td>{{ $detail['name'] }}</td> 
              <td>{{ $detail['unit'] }}</td> 
              <td class="text-right">{{ $detail['quantity'] }}</td>
              <td class="text-right">{{ $detail['price'] }}</td>
              <td class="text-right">{{ $detail['amount'] }}</td>             
            </tr>              
          @endforeach 
        </tbody>
        <tfoot>
          <tr>
            <td class="text-right td-head" colspan="6">Total</td>
            <td class="text-right td-head">{{ $total_invoice }}</td>           
          </tr>         
        </tfoot>
      </table>      
    </div>
  </div>  
</body>
</html>

<style>
 @page {
  margin-top: 10px;
  margin-bottom: 0px
 }
 body {    
  margin: 0;
  font-family: 'Open Sans', sans-serif;
  font-size: 13px;
  line-height: 14px;
}
* {
  box-sizing: border-box;
}
.table-info-bank {
    page-break-inside: avoid; 
}
img {
  max-width: 100%;
}
.container-pdf {
  margin-bottom: 100px; 
} 
.content-pdf {
  width: 100%;
  max-width: 1200px;
  padding: 0 15px;
  margin: 0 auto;
  position: relative;
}
.fl {
  float: left;
}
.fr {
  float: right;
}
.w50p{
  width: 50%;
}
.w100p{
  width: 100%;
}
.text-center {
  text-align: center;
}
.text-right {
  text-align: right;
}
.color-orange {
  color: #e76859;
}
.color-red {
  color: red;
}
.clear {
  clear: both;
}
.header-pdf .ntp-logo {
  width: 230px;
  height: 100px;
  padding: 8px 0 8px 0;
  /* position: relative; */
}
.header-pdf .ntp-logo .ntp-slogan {
  /* display: block; */
  font-size: 13px;
  line-height: 14px;
  white-space: nowrap;
  color: #333333;
}
.header-pdf {
  top: 0;
  left: 0;
  top: -60px;
  width: 100%;
  background: #fff;
  z-index: 10;
}
.container-pdf {
  flex: 1; 
}
.info-vfruit {
  border-bottom: 3px solid #4F46E5;
  padding-bottom: 5px;
}
.info-client:after { 
  bottom: 0;
  left: 0;
  content: "";
  border-bottom: 3px solid #4F46E5;
  width: 30%;
}
table.table-info {
   width: 100%;   
   border-collapse: collapse;
   margin: 10px 0;
   font-size: 13px;
   line-height: 16px;
}
.table-info td {
  border: 1px dotted #666666;
  padding: 3px 3px;
  height: 35px;
  vertical-align: middle;
}

.table-info td:first-child {
  border-left: 1px solid #666666;
}
.table-info td:last-child {
  border-right: 1px solid #666666;
}
.table-info tbody tr:last-child td {
  border-bottom: 1px solid #666666;
}
.table-info thead td {
  font-weight: bold;  
  vertical-align: middle;
  background-color: #4F46E5;
  color: #fff;
  border: 1px solid #666666;
}
.table-info tfoot td {
  border: 1px solid #666666;
  font-weight: bold;
}
.table-info tfoot td.td-head {
  background-color: #4F46E5;
  color: #fff;
}
.table-info tfoot td.size-normal {
  font-weight: normal;
}
.table-info tfoot td.no-border-left {
  border-left-color: transparent;
}
.table-info tfoot td.no-border-bottom {
  border-bottom-color: transparent;
}
.table-info tfoot td.no-border-right {
  border-right-color: transparent;
}

</style>