Feature: Registro de clientes

  Scenario: Registro exitoso de un nuevo cliente
    Given que estoy autenticado
    And que estoy en la página de registro de clientes
    When ingreso "1724569874" en el campo "ci_cliente"
    And ingreso "Juan" en el campo "nombre_cliente"
    And ingreso "Perez" en el campo "apellido_cliente"
    And ingreso "0978805846" en el campo "telefono_cliente"
    And ingreso "25 de noviembre y maldonado" en el campo "direccion_cliente"
    And hago clic en el botón "Registrar Cliente"
    Then debería ver "Registro exitoso"

  Scenario: Registro fallido de un cliente con cédula duplicada
    Given que estoy autenticado
    And que estoy en la página de registro de clientes
    When ingreso "1724569874" en el campo "ci_cliente"
    And ingreso "Carlos" en el campo "nombre_cliente"
    And ingreso "Lopez" en el campo "apellido_cliente"
    And ingreso "0978805847" en el campo "telefono_cliente"
    And ingreso "Otra dirección" en el campo "direccion_cliente"
    And hago clic en el botón "Registrar Cliente"
    Then debería ver "El cliente ya existe. Intentalo de nuevo con otra cedula de identidad"

  Scenario: Registro fallido de un cliente con campos vacíos
    Given que estoy autenticado
    And que estoy en la página de registro de clientes
    When ingreso "" en el campo "ci_cliente"
    And ingreso "" en el campo "nombre_cliente"
    And ingreso "" en el campo "apellido_cliente"
    And ingreso "" en el campo "telefono_cliente"
    And ingreso "" en el campo "direccion_cliente"
    And hago clic en el botón "Registrar Cliente"
    Then debería ver "Por favor corrija los errores para continuar"

  Scenario: Registro fallido de un cliente con cédula inválida
    Given que estoy autenticado
    And que estoy en la página de registro de clientes
    When ingreso "123" en el campo "ci_cliente"
    And ingreso "Maria" en el campo "nombre_cliente"
    And ingreso "Gomez" en el campo "apellido_cliente"
    And ingreso "0978805848" en el campo "telefono_cliente"
    And ingreso "Alguna dirección" en el campo "direccion_cliente"
    And hago clic en el botón "Registrar Cliente"
    Then debería ver "Formato invalido"
