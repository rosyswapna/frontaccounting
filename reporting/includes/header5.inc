<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/

/**
	Document blueprint use following parameters set in doctext.inc:

	$Addr1,	$Addr2 - address info
	$Payment_Terms - payment terms line
	$Footer - footer texts
	$this->company - company info
	$this->title - report title
	$this->formData - some other info
***/

		$this->fontSize = 8.1;


		$this->row = $this->pageHeight - $this->topMargin-40;

		$upper = $this->row - 2 * $this->lineHeight;
		$lower = $this->bottomMargin + 8 * $this->lineHeight;

		$right = $this->pageWidth - $this->rightMargin;
	
		
		$icol = $this->pageWidth / 2;
		$ccol = $this->cols[0] + 4;
		$cncol = $ccol + 150;

		$c2col = $ccol-250;
		$cn2col = $c2col + 70;

		$ccol2 = $icol / 2;
		$mcol = $icol + 8;
		$mcol2 = $this->pageWidth - $ccol2;
		$cols = count($this->cols);

		
	
		// Company Logo
		$this->row = $upper+20;
		$logo = company_path() . "/images/" . $this->company['coy_logo'];
		if ($this->company['coy_logo'] != '' && file_exists($logo)){
			$this->AddImage($logo, $ccol, $this->row, 0, 80);
		}
		else{
			$this->fontSize += 4;
			$this->Font('bold');
			$this->Text($ccol, $this->company['coy_name'], $icol);
			$this->Font();
			$this->fontSize -= 4;
		}

		// Document title
		$this->NewLine();
		$this->SetTextColor(0, 0, 0);
		$this->fontSize += 2;
		$this->Font('bold');
		$this->Font('U');
		$this->TextWrap($ccol, $this->row+10, $this->pageWidth - $this->rightMargin - $ccol - 20, $this->title, 'center');

		//DOCUMENT HEADER
		$this->Font();
		$this->fontSize -= 2;
		$this->NewLine();
		$this->SetTextColor(0, 0, 0);
		$adrline = $this->row;

		$this->Font('U');
		$this->Text($ccol, _("Contract No: ".$this->formData['contract_no']));

		$this->Font('');
		$this->Text($c2col, _(" Date: ").sql2date(	$this->formData['document_date']));
		

		//BUYER DETAILS
		$this->NewLine(2);
		$this->Text($ccol, _("BUYER :"));
		$this->NewLine();
		$adrcol=$ccol+50;
		$this->Text($adrcol, $Addr1['name'], $adrcol);
		$this->NewLine();
		$this->TextWrapLines($adrcol, $box_right, $this->formData['address']);


		//payment terms
		$this->NewLine(2);
		$this->Text($ccol, _("Payment Terms : ").$Payment_Terms);

		//SHIPMENT terms
		$this->NewLine(2);
		$this->Text($ccol, $Shipment_Terms);
		$this->Text($c2col, _(" Shipment Validity : ").sql2date($this->formData['shipment_validity_date']));

		//--------------------------------- table starts here---------------------------------
		$this->NewLine();
		$rightEnd = $this->pageWidth - $this->rightMargin;
		$leftEnd = $ccol;

		//horizontal points
		$hline1 = $this->row;
		$hline2 = $hline1 - 1.5 * $this->lineHeight;
		$hline3 = $hline2 - 2.5 * $this->lineHeight;
		//vertical points
		$rightEnd = $this->pageWidth - $this->rightMargin;
		$inner1 = $leftEnd+50;
		$inner2 = $inner1+200;
		$inner3 = $inner2+90;
		$inner4 = $inner3+90;
		$leftEnd = $ccol;
		$columns = array($leftEnd,$inner1,$inner2,$inner3,$inner4,$rightEnd);
		//margin left for table data
		$mltbl = $ccol+3;
		
		//table horizontal lines
		$this->Line1($hline1,0,$ccol);
		$this->Line1($hline2,0,$ccol);
		$this->Line1($hline3,0,$ccol);

		//table vertical left and right end lines
		$this->LineTo($rightEnd, $hline1 ,$rightEnd, $hline3);
		$this->LineTo($leftEnd, $hline1 ,$leftEnd, $hline3);
		$this->LineTo($inner1, $hline1 ,$inner1, $hline3);
		$this->LineTo($inner2, $hline1 ,$inner2, $hline3);
		$this->LineTo($inner3, $hline1 ,$inner3, $hline3);
		$this->LineTo($inner4, $hline1 ,$inner4, $hline3);

		// table headers
		$this->row = $hline1 - $this->lineHeight -1;
		$this->Font('bold');
		$count = count($sc_tbl_headers);
		for ($i = 0; $i < $count; $i++){
			$dcol1 = $columns[$i];
			$j = $i+1;
			$dcol2 = $columns[$j]-$dcol1;
			
			$this->TextWrap($dcol1, $this->row, $dcol2, $sc_tbl_headers[$i], 'center');
		}
		$this->Font();

		//table data
		$this->row = $hline2 - $this->lineHeight -3;
		$count = count($sc_item);
		for ($i = 0; $i < $count; $i++){
			$dcol1 = $columns[$i];
			$j = $i+1;
			$dcol2 = $columns[$j]-$dcol1;
			
			$this->TextWrap($dcol1, $this->row, $dcol2, $sc_item[$i], 'center');
		}


		//--------------------------------- table ends here---------------------------------


		//data after table
		$this->row = $hline3 - $this->lineHeight - 1;
		
		foreach($sc_aux_info1 as $aux_header => $aux_content)
		{
			
			$this->Text($ccol, $aux_header,$cncol);

			$this->Text($cncol, ":");
			
			$this->Text($cncol+5, $aux_content);

			$this->NewLine();
		}

		//attachments
		$this->Text($ccol,"Documents Required",$cncol);
		$this->Text($cncol, ":",$cncol+5);
		$i=1;
		foreach($documents as $document)
		{
			if($i%2 == 0){
				$this->Text($cncol+180, $i.") ".$document);
				$this->NewLine();
			}else{
				$this->Text($cncol+5, $i.") ".$document);
			}
			$i++;
			
		}
		$this->NewLine(2);

		//remarks
		$this->Text($ccol,"Remarks",$cncol);
		$this->Text($cncol, ":",$cncol+5);
		/*$i=1;
		foreach($remarks as $remark)
		{
			$this->TextWrapLines($cncol+5,$cncol+210, $i.") ".$remark);
			$this->NewLine();
			$i++;
			
		}*/
		$this->TextWrapLines($cncol+5,$cncol+210, $this->formData['remarks']);
		$this->NewLine();

		//drawer name&addr
		$this->Text($ccol,"Drawer's Name and Address",$cncol);
		$this->NewLine();
		foreach($sc_bank_details as $heading => $content)
		{

			$this->Text($ccol, $heading,$cncol);

			$this->Text($cncol, ":",$cncol);
			
			$this->Text($cncol+5, $content);

			$this->NewLine();
		}

		//text
		$this->NewLine(2);
		$this->Text($ccol,"Thank You,");
		$this->Text($cncol+150,"We confirm the above,");

		//signature
		$this->NewLine(3);
		$this->Line1($this->row,0,$ccol,$ccol+150);
		$this->Line1($this->row,0,$cncol+150,$cncol+300);
		$this->NewLine();
		$this->Text($ccol,$this->company['coy_name']);
		$this->Text($cncol+150,$Addr1['name']);



		//FOOTER
		$this->row = $lower - $this->bottomMargin-42;
		$this->Line1($this->row,0,$this->leftMargin);
		foreach($coyFooter as $content){
			$this->NewLine();
			$this->TextWrap($ccol, $this->row, $this->rightMargin-$ccol, $content, 'center');
		}



		
		

		
		

		

?>