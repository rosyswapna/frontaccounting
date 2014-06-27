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


		$this->row = $this->pageHeight - $this->topMargin-40;

		$upper = $this->row - 2 * $this->lineHeight;
		$lower = $this->bottomMargin + 8 * $this->lineHeight;

		$right = $this->pageWidth - $this->rightMargin;
	
		
		$icol = $this->pageWidth / 2;
		$ccol = $this->cols[0] + 4;
		$cncol = $ccol + 70;

		$c2col = $ccol-250;
		$cn2col = $c2col + 70;

		$ccol2 = $icol / 2;
		$mcol = $icol + 8;
		$mcol2 = $this->pageWidth - $ccol2;
		$cols = count($this->cols);

		//-------------------------address box start here-------------------------
		$box_right = $ccol + 250;
		$left = $ccol;

		$iline1 = $upper - 2.8 * $this->lineHeight;
		$iline2 =  $iline1 - 4.5 * $this->lineHeight;

		$this->Line1($iline1,0,$ccol,$ccol + 250);
		$this->Line1($iline2,0,$ccol,$ccol + 250);

		$this->LineTo($box_right, $iline1 ,$box_right, $iline2);
		$this->LineTo($left, $iline1 ,$left, $iline2);

		$this->row = $iline1 - $this->lineHeight;
		$adrcol=$ccol+10;
		//$this->NewLine();
		$this->Text($adrcol, $Addr1['name'], $adrcol);
		$this->NewLine();
		$this->TextWrapLines($adrcol, $box_right, $this->formData['address']);
		
		//-------------------------address box ends here-------------------------
		

		//-------------------------------------table start here-----------------------

		//margin left for table data
		$mltbl = $ccol+3;

		//end points for table right and left ends.
		$rightEnd = $this->pageWidth - $this->rightMargin;
		$leftEnd = $ccol;

		//table rows points
		$iline3 = $iline2 - 7.5 * $this->lineHeight;
		$iline4 = $iline3 - 2.5 * $this->lineHeight;
		$iline5 = $iline4 - 1.5 * $this->lineHeight;
		$iline6 = $iline5 - 8 * $this->lineHeight;
		$iline7 = $iline6 - 1.5 * $this->lineHeight;
		$iline8 = $iline7 - 1.5 * $this->lineHeight;
		$iline9 = $iline8 - 1.5 * $this->lineHeight;

		//table column's points
		$inner1 = $leftEnd+90;
		$inner2 = $inner1+120;
		$inner3 = $inner2+90;
		$inner4 = $inner3+90;
		//array for table data
		$columns = array($leftEnd,$inner1,$inner2,$inner3,$inner4,$rightEnd);

		//table horizontal lines
		$this->Line1($iline3,0,$ccol);
		$this->Line1($iline4,0,$ccol);
		$this->Line1($iline5,0,$ccol);
		$this->Line1($iline6,0,$ccol);
		$this->Line1($iline7,0,$ccol);
		$this->Line1($iline8,0,$inner3);
		$this->Line1($iline9,0,$ccol);

		
		//table vertical left and right end lines
		$this->LineTo($rightEnd, $iline3 ,$rightEnd, $iline9);
		$this->LineTo($leftEnd, $iline3 ,$leftEnd, $iline9);

		//table vertical inner lines
		$this->LineTo($inner1, $iline4 ,$inner1, $iline6);
		$this->LineTo($inner2, $iline4 ,$inner2, $iline6);
		$this->LineTo($inner3, $iline4 ,$inner3, $iline9);
		$this->LineTo($inner4, $iline4 ,$inner4, $iline9);
		//-------------------------------------table ends here-----------------------
		
	
		// Company Logo
		//$this->NewLine();
		$this->row = $upper+20;
		$logo = company_path() . "/images/" . $this->company['coy_logo'];
		if ($this->company['coy_logo'] != '' && file_exists($logo))
		{

			$this->AddImage($logo, $ccol, $this->row, 0, 80);
		}
		else
		{
			$this->fontSize += 4;
			$this->Font('bold');
			$this->Text($ccol, $this->company['coy_name'], $icol);
			$this->Font();
			$this->fontSize -= 4;
		}

		// Document title
		//$this->row -=25;
		$this->NewLine(2);
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

		$this->Text($ccol, _("Date :"),$cncol);
		$this->Text($cncol-40, sql2date($this->formData['document_date']));

		$this->Text($c2col, _("Contract No : "),$cn2col);
		$this->Text($cn2col, $this->formData['contract_no'],$mcol);

		$this->NewLine();
		$this->Text($ccol, _("To,"));

		$this->Text($c2col, _("Invoice No   :"),$cn2col);
		$this->Text($cn2col , $this->formData['trans_no'],$mcol);
		$this->NewLine();
		
		// address
		/*
		$this->row = $iline1 - $this->lineHeight;
		$adrcol=$ccol+10;
		//$this->NewLine();
		$this->Text($adrcol, $Addr1['name'], $adrcol);
		$this->NewLine();
		$this->TextWrapLines($adrcol, $box_right, $this->formData['address'],$iline2);
		*/

		$this->row = $iline2 - $this->lineHeight-10;
		$tmp = $this->row;
		//data from other details
		foreach($info_hleft as $info_header => $info_content)
		{
			
			$this->Text($ccol, $info_header,$cncol);
			
			$this->Text($cncol, $info_content,$mcol);
			$this->NewLine();
		}

		$this->row = $tmp;
		foreach($info_hright as $info_header => $info_content)
		{
			$this->Text($c2col, $info_header,$cn2col);
			$this->Text($cn2col, $info_content);
			$this->NewLine();
		}

		//payment terms
		$this->row = $iline3+3;
		$this->Font('U');
		$this->Text($ccol, _("PAYMENT TERMS :"),$cn2col);
		$this->row = $iline3 - $this->lineHeight;
		$this->Font();
		$this->Text($mltbl,$Payment_Terms);

		// table headers
		$this->row = $iline4 - $this->lineHeight -1;
		$this->Font('bold');
		$count = count($this->headers);
		for ($i = 0; $i < $count; $i++){
			$dcol1 = $columns[$i];
			$j = $i+1;
			$dcol2 = $columns[$j]-$dcol1;
			
			$this->TextWrap($dcol1, $this->row, $dcol2, $this->headers[$i], 'center');
		}
		$this->Font();

		// table data
		$this->row = $iline5 - 20;
		$this->TextWrap($columns[0], $this->row, $columns[1]-$columns[0], $this->formData['container_no'], 'center');
		//items
		$this->row = $iline5 - 20;
		foreach ($items as $item) {
			$j=0;
			for ($i = 1; $i <= count($item); $i++){
				$dcol1 = $columns[$i];
				$k = $i+1;
				$dcol2 = $columns[$k]-$dcol1;
				$this->TextWrap($dcol1, $this->row, $dcol2, $item[$j], 'center');
				$j++;
			}
			$this->NewLine();
			
		}


		// table totals
		$rows = array($iline6 - $this->lineHeight -1,$iline7 - $this->lineHeight -1,$iline8 - $this->lineHeight -1);
		$i = 0;
		foreach($table_totals as $info_header => $info_content){
			$this->row = $rows[$i];
			$hcol1 = $inner3;
			$hcol2 = $inner4 - $inner3;
			$this->TextWrap($hcol1, $this->row, $hcol2, $info_header, 'center');

			$dcol1 = $inner4;
			$dcol2 = $rightEnd - $inner4;
			$this->TextWrap($dcol1, $this->row, $dcol2, $info_content, 'center');
			$i++;
		}

		//table total in figures
		if($words!= ""){
			$this->row = $iline7 - $this->lineHeight - 1;
			$this->TextWrap($leftEnd, $this->row, $inner3-$leftEnd-2, $words, 'left');
		}

		//CERTIFY
		$this->row = $iline9 - $this->lineHeight;
		$this->Text($ccol, $Certify);

		//BANK DETAILS
		$this->NewLine(2);
		$this->Font('U');
		$this->Text($ccol, "OUR BANK DETAILS");

		$this->Font();
		$this->NewLine(2);
		foreach($bank_details as $data){
			$this->TextWrapLines($ccol, $this->row, $data);
		}
		

		//THANKING
		$this->NewLine(2);
		$this->Text($ccol, "Thanking You,");

		$this->NewLine(4);
		$this->Text($ccol, "For, ".$this->company['coy_name']);


		//FOOTER
		$this->NewLine(4);
		$this->Line1($this->row,0,$this->leftMargin);
		foreach($coyFooter as $content){
			$this->NewLine();
			$this->TextWrap($ccol, $this->row, $this->rightMargin-$ccol, $content, 'center');
		}
		

		

?>