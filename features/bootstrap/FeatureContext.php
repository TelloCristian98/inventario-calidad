<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use PHPUnit\Framework\Assert as Assertions;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
{
    /**
     * Initializes context.
     */
    public function __construct()
    {
    }

    /**
     * @Given que estoy autenticado
     */
    public function queEstoyAutenticado()
    {
        // Añade una cookie de sesión simulada
        $this->getSession()->setCookie('PHPSESSID', 'dummy_session_id');
        echo "Simulated session cookie set\n";
    }

    /**
     * @Given que estoy en la página de registro de clientes
     */
    public function queEstoyEnLaPaginaDeRegistroDeClientes()
    {
        $this->visit('/php/clientes_panel.php');
        echo "Visited registration page\n";
    }

    /**
     * @When ingreso :value en el campo :field
     */
    public function ingresoEnElCampo($value, $field)
    {
        $this->fillField($field, $value);
        echo "Filled field $field with value $value\n";
    }

    /**
     * @When hago clic en el botón :button
     */
    public function hagoClicEnElBoton($button)
    {
        $this->pressButton($button);
        echo "Clicked button $button\n";
    }

    /**
     * @Then debería ver :text
     */
    public function deberiaVer($text)
    {
        $this->assertSession()->pageTextContains($text);
        echo "Checked for text $text\n";
    }
}
