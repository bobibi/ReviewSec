<?php
namespace ReviewSec\Model\Entity\Amazon;

class Product
{
    public $ItemID; //itemId 	A positive integer that uniquely identifies an item
    public $Name; //name 	Standard name of the item
    public $SalePrice; //salePrice 	Selling price for the item in USD
    public $CategoryPath; //categoryPath 	Breadcrumb for the item. This string describes the category level hierarchy that the item falls under.
    public $ImageURL; //mediumImage 	Medium size image for the item in jpeg format with dimensions 180 x 180 pixels
    public $URL; //productTrackingUrl 	Deep linked URL that directly links to the product page of this item on walmart.com, and uniquely identifies the affiliate sending this request via a linkshare tracking id |LSNID|. The LSNID parameter needs to be replaced with your linkshare tracking id, and is used by us to correctly attribute the sales from your channel on walmart.com. Actual commission numbers will be made available through your linkshare account.
    public $AverageRating; //customerRating 	Average customer rating out of 5
    public $NumberOfTotalReviews; //numReviews 	Number of customer reviews available on this item on Walmart.com
    public $Status;

    public function exchangeArray($data)
    {
        $this->ItemID = (! empty($data['ItemID'])) ? $data['ItemID'] : null;
        $this->AverageRating = (! empty($data['AverageRating'])) ? $data['AverageRating'] : null;
        $this->Name = (! empty($data['Name'])) ? $data['Name'] : null;
        $this->ImageURL = (! empty($data['ImageURL'])) ? $data['ImageURL'] : null;
        $this->SalePrice = (! empty($data['SalePrice'])) ? $data['SalePrice'] : null;
        $this->CategoryPath = (! empty($data['CategoryPath'])) ? $data['CategoryPath'] : null;
        $this->Status = (! empty($data['Status'])) ? $data['Status'] : null;
        $this->NumberOfTotalReviews = (! empty($data['NumberOfReviews'])) ? $data['NumberOfReviews'] : null;
        $this->URL = (! empty($data['URL'])) ? $data['URL'] : null;
    }
}