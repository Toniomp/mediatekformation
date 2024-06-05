<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Description of FormationValidationsTest
 *
 * @author delah
 */
class FormationValidationsTest extends KernelTestCase {
    
       
    public function getFormation(): Formation{
        return (new Formation())
                ->setTitle("PhpMyAdmin")
                ->setVideoId("123456789ab");
    }
    
    publiC function testValidPublishedAt() {
        $visite = $this->getFormation()->setPublishedAt(new DateTime("2024-09-18"));
        $this->assertErrors($visite, 0);
    }
    
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}
