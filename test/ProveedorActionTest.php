<?php

use PHPUnit\Framework\TestCase;

include(__DIR__ . '/../php/dbconnection.php');

class ProveedorActionTest extends TestCase
{
    private $con;

    protected function setUp(): void
    {
        // Mock the database connection
        $this->con = $this->createMock(mysqli::class);

        // Mock POST data
        $_POST = [];
    }

    public function testCreateActionSuccess()
    {
        $_POST = [
            'action' => 'create',
            'Nombre_Proveedor' => 'Proveedor Test',
            'Direccion_Proveedor' => '123 Test St',
            'Telefono_Proveedor' => '1234567890',
            'Email_Proveedor' => 'test@proveedor.com'
        ];

        // Mock the query result
        $this->con->method('query')->willReturn(true);

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/proveedor_action.php';
        $output = ob_get_clean();

        $response = json_decode($output, true);

        $this->assertEquals(1, $response['status']);
        $this->assertEquals('Proveedor registrado correctamente', $response['msg']);
        $this->assertEquals($_POST, $response['data']);
    }

    public function testCreateActionFailure()
    {
        $_POST = [
            'action' => 'create',
            'Nombre_Proveedor' => 'Proveedor Test',
            'Direccion_Proveedor' => '123 Test St',
            'Telefono_Proveedor' => '1234567890',
            'Email_Proveedor' => 'test@proveedor.com'
        ];

        // Mock the query result to simulate a failure
        $this->con->method('query')->willReturn(false);
        $this->con->method('error')->willReturn('Simulated error');

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/proveedor_action.php';
        $output = ob_get_clean();

        $response = json_decode($output, true);

        $this->assertEquals(0, $response['status']);
        $this->assertStringContainsString('Error registrando proveedor', $response['msg']);
    }
}
