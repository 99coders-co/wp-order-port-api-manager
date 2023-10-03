<?php
global $product_sku;
if($product_sku){
  $api_integration = new OrderportAPI();
  $resp=$api_integration->callListOfAllProductsApi();
  $responseObj=json_decode($resp);
  $productResp="";
  if(count($responseObj->Data->Products)>0){

  	 for($i=0;$i<count($responseObj->Data->Products);$i++){
  	 	if(!$productResp){
  	 		if($responseObj->Data->Products[$i]->Opsku==$product_sku){
  	 			$productResp=$responseObj->Data->Products[$i];
						
  	           }
  	       }
  		}
  	}
 }else{

 }


if($productResp){ ?>
<div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained">
  <div class="wp-block-group__inner-container">
  	 <?php if($productResp->Image->Large){ ?>
        <img src="<?php echo $productResp->Image->Large; ?>">
        <?php } ?>

    <h2 class="wp-block-heading has-text-align-left"><?php echo $productResp->Title; ?></h2>
    	<?php if($productResp->RetailPrice){ ?>
    	<p class="has-text-align-left subheading"> Price :<?php echo $productResp->RetailPrice; ?></p> 
    	<?php } ?>
       <p class="has-text-align-left subheading">PartNo: <?php echo $productResp->PartNo; ?></p>
       <p class="has-text-align-left subheading">ProductType: <?php echo $productResp->ProductType; ?></p>
       <p class="has-text-align-left subheading">PartNo: <?php echo $productResp->PartNo; ?></p>

      <?php if($productResp->Overview){ ?> <p>Overview:  <?php echo $productResp->Overview; ?></p> <?php } ?>
     
    <?php 
    if($productResp->ProductPageUrl){
    	$actualUrl=explode("https:///",$productResp->ProductPageUrl);
    	} 

    if($actualUrl[1]){ ?>    
  
    <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
      <div class="wp-block-button">
        <a class="wp-block-button__link wp-element-button" target="_blank" href="https://chrisjamescellars.orderport.net/<?php echo $actualUrl[1]; ?>">Book Now</a>
      </div>
    </div>
    <?php } ?>
   
</div>
<?php 

}else{

	echo '<p class="has-text-align-left subheading">Invalid Product Sku!! Try Again </p>';
}
