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

		$iline1 = $upper - 0.8 * $this->lineHeight;

		$right = $this->pageWidth - $this->rightMargin;
	
		
		$icol = $this->pageWidth / 2;
		$ccol = $this->cols[0] + 4; //left

		$cncol = $ccol + 120;

		$c2col =  $right-40; //right
		

		//HEADER START HERE	
		// Logo
		$this->row = $upper+20;
		$logo = company_path() . "/images/" . $this->company['coy_logo'];
		if ($this->company['coy_logo'] != '' && file_exists($logo)){
			$this->AddImage($logo, $ccol, $this->row, 0, 50);
		}
		$this->row = $upper+60;
		$head_col = $ccol+50;
		$this->fontSize += 4;
		$this->Font('bold');
		$this->TextWrap($ccol, $this->row, $this->pageWidth - $ccol, $this->company['coy_name'], 'center');
		$this->Font('');
		$this->fontSize -= 4;
		foreach($coyFooter as $content){
			$this->NewLine();
			$this->TextWrap($ccol, $this->row, $this->pageWidth - $ccol, $content, 'center');
		}
		$this->row = $upper+10;
		$this->Line1($this->row,0,$ccol,$this->pageWidth - $ccol);
		// Document title
		$this->NewLine(2);
		$this->SetTextColor(0, 0, 0);
		$this->fontSize += 2;
		$this->Font('bold');
		$this->TextWrap($ccol, $this->row, $this->pageWidth - $this->rightMargin - $ccol - 20, $this->title, 'center');
		$this->fontSize -= 2;
		//HEADER ENDS HERE

		//----------------------------------------------CONTENT START HERE----------------------------------------------
		
		$this->Font('U');
		$this->NewLine(2);
		$content_row_start = $this->row;
		$this->Text($ccol, 'CUSTOMER :');

		$this->Font('');
		$this->NewLine();
		$this->Text($ccol, $Addr1['name']);
		$this->NewLine();
		$this->TextWrapLines($ccol, $box_right, $Addr1['address']);
		$content_head_end = $this->row;

		$this->row = $content_row_start;
		$head_end = $this->pageWidth-200;

		foreach($sc_right as $head=>$content){

			$this->TextWrap($ccol, $this->row, $head_end, $head, 'right');
			$this->Text($head_end+50, ':');
			$this->Text($head_end+55, $content);
			//$this->TextWrap($ccol, $this->row, $this->rightMargin-$ccol, $content, 'right');
			$this->NewLine();
		}

		//agreement
		$this->row = $content_head_end;
		$this->NewLine();
		foreach($sc_agreement as $text){
			$this->Text($ccol, $text,$c2col);
			$this->NewLine();
		}

		//tbl data
		$this->NewLine();
		foreach($sc_tbl as $h_val=>$c_val){

			$this->Font('bold');

			$this->Text($ccol, $h_val,$cncol);

			$this->Text($cncol, ":");
			
			$this->Font();
			$this->Text($cncol+15, $c_val);

			$this->NewLine();
		}

		//OTHER TERMS AND CONDITIONS
		$this->NewLine();
		$this->Font('bold');
		$this->Text($ccol,"OTHER TERMS AND CONDITIONS:");

		$this->NewLine();
		$this->Font();

		$this->TextWrapLines($ccol, $c2col, $sc_other_terms);
		
		
		
		


		//----------------------------------------------CONTENT ENDS HERE----------------------------------------------
		$this->NewLine(2);
		$this->TextWrapLines($ccol, $c2col, $sc_footer_text);
		$this->Line1($this->row,0,$ccol,$this->pageWidth - $ccol);
		

		//signature
		$this->NewLine(2);
		$this->Text($ccol, "For ".$this->company['coy_name']);
		$this->Text($cncol+150, "For ".$Addr1['name']);
		$this->NewLine(4);
		$this->Line1($this->row,0,$ccol,$ccol+150);
		$this->Line1($this->row,0,$cncol+150,$cncol+300);
		$this->NewLine();

		$this->Text($ccol, "Sales Dept.");
		$this->Text($cncol+150, "Authorized Signatory");
		


	
		

?>