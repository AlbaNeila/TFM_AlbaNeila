<?php
class seleniumLoginTest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://localhost/TFM_AlbaNeila/CODIGO/');
        $this->shareSession(true);
    }

    public function testTitle()
    {
        $this->url('./EjerciciosPaleograficos/view/login.php');
        $this->assertEquals('Acceso UBUPal', $this->title());
    }
    
    public function testInputs(){
        $this->url('./EjerciciosPaleograficos/view/login.php');
        $inputs = $this->elements($this->using('css selector')->value('input'));
        $this->assertEquals(3, count($inputs));
    }
    
    public function testLinks(){
        $this->url('./EjerciciosPaleograficos/view/login.php');
        $links = $this->elements($this->using('css selector')->value('a'));
        $this->assertEquals(2, count($links));
    }
    
    public function testSelectLanguage(){
        $this->url('./EjerciciosPaleograficos/view/login.php');
        $this->select($this->byId('languageSelect'))->selectOptionByValue('1'); 
        $this->assertEquals('UBUPal Access', $this->title());
    }
    
    public function testAbout(){
        $this->url('./EjerciciosPaleograficos/view/login.php');
        $about = $this->byCssSelector('label[class="labelAbout"]')->click();
        $this->assertEquals('UBUPal', $this->title());
        $link = $this->elements($this->using('css selector')->value('a'));
        $link[0]->click();
        $this->assertEquals('Acceso UBUPal', $this->title());
    }
    
    public function testRegister(){
        $this->url('./EjerciciosPaleograficos/view/login.php');
        $links = $this->elements($this->using('css selector')->value('a'));
        $links[0]->click();
        $this->assertEquals('Registro UBUPal', $this->title());
        $inputs = $this->elements($this->using('css selector')->value('input'));
        $this->assertEquals(9, count($inputs));

    }

    


}
?>