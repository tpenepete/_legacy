<?php

$pdf->ImageSVG(dfls.'img/header2.svg',38.541,2,0,0);


$pdf->addTTFfont(dfls.'fonts/helveticaltstd.otf', '', '', 32);

//////////////////////////////////////////////////////////////////////////////////////

$pdf->SetFont('helveticaltstd', '', 11, '', true);
$pdf->SetTextColor(114,134,135);
$pdf->setFontSpacing(0.3);

$t = "<p style=\"line-height:12pt; text-align:center;\">";
$t.= "This form shows all the details of your order and gives a base representation of how your wristbands will look after<br />";
$t.= "they have been manufactured. Please check this form for any mistakes and make changes with a pen in the white areas.<br />";
$t.= "If there are no changes, simply sign and fax or email a scanned copy of this form back us.";
$t.= "</p>";
$pdf->writeHTMLCell(220,10,7,75.5,$t);

$t = "<p style=\"text-align:left;\">*All bracelet and ring materials, custom molds, screens, and inks are produced in China.</p>";
$pdf->writeHTMLCell(220,10,17,254,$t);

$t = "<p style=\"line-height:12.4pt; text-align:left;\">";
$t.= "All orders subject to possible customs inspection,<br />";
$t.= "thus delivery times are estimated only and are not guaranteed.<br />";
$t.= "Signature may be required upon delivery.";
$t.= "</p>";
$pdf->writeHTMLCell(220,10,17,239,$t);

$t = "<p style=\"font-size:14pt; line-height:18pt; text-align:left;\">";
$t.= "Approval Signature........................ Date.........<br />";
$t.= "Print Name....................................................";
$t.= "</p>";
$pdf->writeHTMLCell(0,10,127,239,$t);

$pdf->SetTextColor(255,0,0);
$pdf->writeHTMLCell(0,10,165,236.2,"<b style=\"font-size:16pt;\">X</b>");

//////////////////////////////////////////////////////////////////////////////////////

$pdf->SetFont('badaboombbi', '', 20, '', true);
$pdf->SetTextColor(242,171,38);
$pdf->setFontSpacing(0.1);


$pdf->SetFont('helveticaltstdcompressed', '', 14, '', true);
$html = '<p color="#D92929">Ship to:</p>';
$pdf->writeHTMLCell(0,10,38.541,18,$html);
$html = '<p color="#D92929">Wristband Style:</p>';
$pdf->writeHTMLCell(0,10,85,18,$html);
$html = '<p color="#D92929">Price:</p>';
$pdf->writeHTMLCell(0,10,145,18,$html);

$pdf->ImageSVG(dfls.'img/footer3.svg',17,260,0,0);

$pdf->SetFont('badaboombbi', '', 12, '', true);
$pdf->SetTextColor(254,254,254);
$pdf->setFontSpacing(0);
$pdf->setTextShadow(array(
	'enabled'=>true,
	'depth_w'=>0.2,
	'depth_h'=>0.2,
	'color'=>array(74,94,92),
	'opacity'=>1,
	'blend_mode'=>'Normal'
));

$html = '<p stroke="0.1" fill="true" strokecolor="#4a5e5c" style="line-height:11pt;">AMAZING WRISTBANDS<br />4025 WILLOWBEND, STE 310<br />HOUSTON, TEXAS 77025</p>';
$pdf->writeHTMLCell(0,10,62,262,$html);

$html = '<p stroke="0.1" fill="true" strokecolor="#4a5e5c" style="line-height:11pt;">TEL: 800-269-0910<br />FAX: 713-589-8611<br />E-MAIL: OFFICE@AMAZINGWRISTBANDS.COM</p>';
$pdf->writeHTMLCell(0,10,104,262,$html);

$pdf->SetFont('badaboombbi', '', 11.5, '', true);
$pdf->setTextShadow(array(
	'enabled'=>true,
	'depth_w'=>0.2,
	'depth_h'=>0.2,
	'color'=>array(92,56,38),
	'opacity'=>1,
	'blend_mode'=>'Normal'
));
$pdf->SetTextColor(242,171,38);
$html = '<p stroke="0.1" fill="true" strokecolor="#5c3826" style="line-height:10.4pt;">HOURS:<br />MON-FRI - 7:00 AM - 9:00 PM<br />SATURDAY - 10:00 AM - 4:00 PM<br />SUNDAY - CLOSED</p>';
$pdf->writeHTMLCell(0,10,169,262,$html);

$pdf->setTextShadow(array(
	'enabled'=>false,
	'depth_w'=>0.2,
	'depth_h'=>0.2,
	'color'=>array(92,56,38),
	'opacity'=>1,
	'blend_mode'=>'Normal'
));


