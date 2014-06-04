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

		$iline1 = $upper - 4.1 * $this->lineHeight;
		$iline2 = $iline1 - 5 * $this->lineHeight;

		$iline3 = $iline2 - 7.5 * $this->lineHeight;
		$iline4 = $iline3 - 2.5 * $this->lineHeight;
		$iline5 = $iline4 - 1.5 * $this->lineHeight;
		$iline6 = $iline5 - 8 * $this->lineHeight;
		$iline7 = $iline6 - 1.5 * $this->lineHeight;
		$iline8 = $iline7 - 1.5 * $this->lineHeight;
		$iline9 = $iline8 - 1.5 * $this->lineHeight;	
		
		$icol = $this->pageWidth / 2;
		$ccol = $this->cols[0] + 4;
		$cncol = $ccol + 70;

		$c2col = $ccol-250;
		$cn2col = $c2col + 70;

		$ccol2 = $icol / 2;
		$mcol = $icol + 8;
		$mcol2 = $this->pageWidth - $ccol2;
		$cols = count($this->cols);

		//margin left for table data
		$mltbl = $ccol+3;

		$this->Line1($iline1,0,$ccol,$ccol + 250);
		$this->Line1($iline2,0,$ccol,$ccol + 250);

		$right = $ccol + 250;
		$left = $ccol;

		$this->LineTo($right, $iline1 ,$right, $iline2);
		$this->LineTo($left, $iline1 ,$left, $iline2);

		$this->Line1($iline3,0,$ccol);
		$this->Line1($iline4,0,$ccol);
		$this->Line1($iline5,0,$ccol);
		$this->Line1($iline6,0,$ccol);
		$this->Line1($iline7,0,$ccol);
		$this->Line1($iline8,0,$ccol);
		$this->Line1($iline9,0,$ccol);

		$this->LineTo($ccol, $iline3 ,$ccol, $iline9);
		
		// Company Logo
		$this->NewLine();
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
		$this->row -=25;
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
		$this->Text($cn2col , $this->formData['contract_no'],$mcol);
		$this->NewLine();
		
		// address1
		$adrcol=$ccol+10;
		$this->NewLine();
		$this->Text($adrcol, $Addr1['name'], $adrcol);
		$this->NewLine();
		$this->TextWrapLines($adrcol, $this->row, $Addr1['address']);

		$this->NewLine(2);
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
		$this->Font('');
		$this->Text($mltbl,$Payment_Terms);


		//table heading
		

		

		

		
		

		


		

		


?>