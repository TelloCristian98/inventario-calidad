<?php

use PHPUnit\Framework\TestCase;

include(__DIR__ . '/../php/dbconnection.php');

class ProductActionTest extends TestCase
{
    private $con;

    protected function setUp(): void
    {
        // Mock the database connection
        $this->con = $this->createMock(mysqli::class);

        // Mock session variables
        $_SESSION['idUser'] = 10001;

        // Mock POST data
        $_POST = [];
    }

    public function testEditActionWithExistingProduct()
    {
        $_POST = [
            'action' => 'edit',
            'id' => 1,
            'nombre_producto' => 'Existing Product'
        ];

        // Mock the query and result
        $query = $this->createMock(mysqli_result::class);
        $query->method('fetch_array')->willReturn(['Id_Producto' => 2]);

        $this->con->method('query')->willReturn($query);

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/product_action.php';
        $output = ob_get_clean();

        $response = json_decode($output, true);

        $this->assertEquals(0, $response['status']);
        $this->assertEquals('El material ya esta registrado!', $response['msg']);
    }

    public function testEditActionWithNewProduct()
    {
        $_POST = [
            'action' => 'edit',
            'id' => 1,
            'nombre_producto' => 'New Product'
        ];

        // Mock the query and result
        $query = $this->createMock(mysqli_result::class);
        $query->method('fetch_array')->willReturn(false);

        $this->con->method('query')->willReturn($query);

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/product_action.php';
        $output = ob_get_clean();

        // Assertions can be added here based on expected outcomes
        $this->assertTrue(true); // Placeholder assertion
    }
}
