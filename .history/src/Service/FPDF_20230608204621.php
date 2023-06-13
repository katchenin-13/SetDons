<?php
 
namespace Project\Bundle\MainBundle\Pdf;
 
 
use fpdf;
 
class f extends FPDF
{
 
 
 
function Header()
{
     global $titre;
 
 
    $this->Image(__DIR__.'/../../../../../web/images/logo.png',10,6,50);  
 
    $this->SetX(80);
     
    $this->SetFont('Times','',35);
     
    $this->SetDrawColor(255,255,255); 
     
    $this->SetTextColor(0,128,128);
     
    $this->SetFillColor(255,255,255);
     
    $w = $this->GetStringWidth('Liste de toutes les Banques');  
     
    $a = $w-20;
     
    $this->Cell($w,9,utf8_decode('Liste de toutes les Banques'),1,1,'C',true);
     
    $this->Ln(10);
     
    $this->SetLineWidth(0);
      
    $this->SetDrawColor(0,0,0);
     
    $this->Line(90,30,90+$a,30);
     
    $this->Ln(20);
}
 
function Footer()
{
    // Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Arial italique 8
    $this->SetFont('Arial','I',8);
    // Couleur du texte en gris
    $this->SetTextColor(128);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
 
 
// Tableau coloré
function FancyTable($data)
{
 
    $this->SetX(40);
 
    $this->SetFillColor(133,133,133);
    $this->SetTextColor(255);
    $this->SetDrawColor(220);
    $this->SetLineWidth(0);
    $this->SetFont('Times','B');
 
 
    $this->Cell(20,7,utf8_decode('Numero'),1,0,'C',true);
    $this->Cell(200,7,utf8_decode('Libellé de la Rubrique'),1,0,'C',true);
      
     
    $this->Ln();
    // Restauration des couleurs et de la police
     
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Times');
    $this->SetDrawColor(220);
    // Données
    $fill = false;
    foreach($data as $row)
    {
        $this->SetX(40);
        $this->SetFont('Times');
        $this->Cell(20,6,utf8_decode($row->getId()),'1',0,'L',$fill);
        $this->Cell(200,6,utf8_decode($row->getBqLib()),'1',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
    }
     
 
   
     
}
}
 