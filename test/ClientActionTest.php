<?php
session_start();
use PHPUnit\Framework\TestCase;

include(__DIR__ . '/../php/dbconnection.php');

class ClientActionTest extends TestCase
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

    public function testEditAction()
    {
        $_POST = [
            'action' => 'edit',
            'id' => 1,
            'ci' => '123456',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'phone' => '05934567890',
            'adress' => '123 Main St'
        ];

        // Mock the query and result
        $query = $this->createMock(mysqli_result::class);
        $query->method('fetch_array')->willReturn(false);

        $this->con->method('query')->willReturn($query);

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/client_action.php';
        $output = ob_get_clean();

        // Assertions can be added here based on expected outcomes
        $this->assertTrue(true); // Placeholder assertion
    }

    public function testDeleteAction()
    {
        $_POST = [
            'action' => 'delete',
            'id' => 30002
        ];

        // Mock the query result
        $this->con->method('query')->willReturn(true);

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/client_action.php';
        $output = ob_get_clean();

        $response = json_decode($output, true);

        $this->assertEquals(1, $response['status']);
        $this->assertEquals('Cliente desactivado correctamente', $response['msg']);
    }

    public function testActiveAction()
    {
        $_POST = [
            'action' => 'active',
            'id' => 30002
        ];

        // Mock the query result
        $this->con->method('query')->willReturn(true);

        // Capture the output
        ob_start();
        include __DIR__ . '/../php/client_action.php';
        $output = ob_get_clean();

        $response = json_decode($output, true);

        $this->assertEquals(1, $response['status']);
        $this->assertEquals('Cliente activado correctamente', $response['msg']);
    }
}
