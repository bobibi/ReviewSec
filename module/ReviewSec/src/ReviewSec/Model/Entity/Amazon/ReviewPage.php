<?php
namespace ReviewSec\Model\Entity\Amazon;

class ReviewPage
{
	public $ASIN;
	public $PageNumber;
	public $Token;
	public $TokenExpired;

	public function exchangeArray($data)
	{
		$this->ASIN     = (!empty($data['ASIN'])) ? $data['ASIN'] : null;
		$this->PageNumber = (!empty($data['PageNumber'])) ? $data['PageNumber'] : null;
		$this->Token  = (!empty($data['Token'])) ? $data['Token'] : null;
		$this->TokenExpired     = (!empty($data['TokenExpired'])) ? $data['TokenExpired'] : null;
	}
}