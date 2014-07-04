<?php
class seleniumLoginTest extends PHPUnit_Extensions_SeleniumTestCase
{
    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://www3.ubu.es/ubupal/EjerciciosPaleograficos/view/login.php');
    }

    public function testTitle()
    {
        $this->open('http://www3.ubu.es/ubupal/EjerciciosPaleograficos/view/login.php');
        $this->assertTitle('Acceso UBUPal');
    }

}
?>